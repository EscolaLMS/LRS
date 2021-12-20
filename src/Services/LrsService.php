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


        // 'http://localhost:8000/GolfExample_TCAPI/index.html?endpoint=http://localhost:1000/trax/api/18b34bd2-fe12-4e3f-a1fb-d95204da10e0/xapi/std&auth=Basic Y2xpZW50OmNsaWVudA==&actor={"mbox":"mailto:brian.miller@scorm.com","name":"Brian J. Miller","objectType":"Agent"}&registration=8175776f-masz chwil8717-457d-b122-285ced399a96'

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
