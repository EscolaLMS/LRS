<?php

namespace EscolaLms\Lrs\Services;

use EscolaLms\Courses\Models\Topic;
use EscolaLms\Lrs\Services\Contracts\LrsServiceContract;
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
