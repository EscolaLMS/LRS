<?php

namespace EscolaLms\Lrs\Services;

use EscolaLms\Lrs\Services\Contracts\LrsServiceContract;
use Trax\Auth\Stores\Accesses\Access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use EscolaLms\Courses\Models\Course;

class LrsService implements LrsServiceContract
{
    public function launchParams(int $courseId, string $token): array
    {
        $access = Access::firstOrFail();
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        $token = explode(" ", $token);
        $token = array_pop($token);
        $topic = $course->lessons->first()->topics->first();

        $fetch = route("cmi5.fetch") . "?token=" . $token;

        $result = [
            'endpoint' => $access->getXapiEndpointAttribute($courseId),
            'fetch' => $fetch,
            'actor' => [
                "mbox" => "mailto:" . $user->email,
                'objectType' => 'Agent',
                'name' => "{$user->first_name} {$user->last_name}"
            ],
            'registration' => (string) Str::uuid(),
            'activityId' => url("xapi/activities/course/{$courseId}/topic/{$topic->id}")


        ];

        $url = http_build_query([
            'endpoint' => $result['endpoint'],
            'fetch' => $result['fetch'],
            'actor' => json_encode($result['actor']),
            'registration' => $result['registration'],
            'activityId' => $result['activityId'],
        ]);

        $result['url'] = $url;

        return $result;
    }
}
