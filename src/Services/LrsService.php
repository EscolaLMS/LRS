<?php

namespace EscolaLms\Lrs\Services;

use EscolaLms\Courses\Models\Topic;
use EscolaLms\Lrs\Enums\XApiEnum;
use EscolaLms\Lrs\Services\Contracts\LrsServiceContract;
use Illuminate\Http\Request;
use Trax\Auth\Stores\Accesses\Access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LrsService implements LrsServiceContract
{
    public function launchParams(string $token, ?int $courseId = null, ?int $topicId = null): array
    {
        $access = Access::firstOrFail();
        $user = Auth::user();

        if ($topicId) {
            $topic = Topic::findOrFail($topicId);
            $courseId = $topic->lesson->course->getKey();
        }

        $token = explode(" ", $token);
        $token = array_pop($token);
        $fetch = route("cmi5.fetch") . "?token=" . $token;

        $result = [
            'endpoint' => $access->getXapiEndpointAttribute($courseId),
            'fetch' => $fetch,
            'actor' => [
                // 'mbox' => 'mailto:' . $user->email,
                'objectType' => 'Agent',
                'account' => [
                    'homePage' => "https://escolalms.com",
                    'name' => $user->email,
                ]
            ],
            'registration' => (string) Str::uuid(),
            'activityId' => $this->getActivityId($courseId, $topicId)
        ];

        $url = http_build_query([
            'endpoint' => $result['endpoint'],
            'fetch' => $result['fetch'],
            'actor' => json_encode($result['actor']),
            'registration' => $result['registration'],
            'activityId' => $result['activityId'],
        ]);

        $result['url'] = $url;
        $result['state'] = [
            'stateId' => 'LMS.LaunchData',
            'agent' => json_encode($result['actor']),
            'activityId' => $result['activityId'],
            'registration' => $result['registration'],
        ];

        return $result;
    }

    public function saveState(string $token, array $params): array
    {
        $putUrl = $params['endpoint'] . '/activities/state?' . http_build_query($params['state']);

        $putRequest = Request::create($putUrl, 'PUT', $params['state']);
        $putRequest->headers->set('Authorization', $token);
        $putRequest->headers->set('X-Experience-API-Version', XApiEnum::API_VERSION);

        $params['response'] = app()->handle($putRequest);

        return $params;
    }

    public function saveAgent(string $token, array $params): array
    {
        $post = $params['endpoint'] . '/agents/profile';

        $data = [
            'agent' => json_encode($params['actor']),
            'profileId' => 'cmi5LearnerPreferences',
        ];

        $request = Request::create($post, 'POST', $data);
        $request->headers->set('Authorization', $token);
        $request->headers->set('X-Experience-API-Version', XApiEnum::API_VERSION);

        $params['response'] = app()->handle($request);

        return $params;
    }

    private function getActivityId(?int $courseId = null, ?int $topicId = null): string
    {
        if ($courseId && $topicId) {
            return url("xapi/activities/course/{$courseId}/topic/{$topicId}");
        }
        elseif ($courseId) {
            return url("xapi/activities/course/{$courseId}");
        }

        return url("xapi/activities/preview");
    }
}
