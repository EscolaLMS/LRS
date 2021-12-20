<?php

namespace Tests\APIs;

use EscolaLms\Lrs\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use EscolaLms\Lrs\Database\Seeders\LrsSeeder;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Courses\Models\Lesson;
use EscolaLms\Courses\Models\Topic;

class cmi5ApiTest extends TestCase
{
    use DatabaseTransactions;

    public $user;
    public $course;
    public $tutor;
    public $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LrsSeeder::class);

        $this->user = config('auth.providers.users.model')::factory()->create();
        $this->user->guard_name = 'api';
        $this->user->assignRole('student');

        $this->tutor = config('auth.providers.users.model')::factory()->create();
        $this->tutor->guard_name = 'api';
        $this->tutor->assignRole('tutor');

        $this->course = Course::factory()->create([
            'author_id' => $this->tutor->id,
            'base_price' => 1337,
            'active' => true,
        ]);

        $lesson = Lesson::factory()->create([
            'course_id' => $this->course->id,
        ]);

        $topic = Topic::factory()->create([
            'lesson_id' => $lesson->id,
            'json' => ['foo' => 'bar', 'bar' => 'foo'],
        ]);

        $this->course->users()->syncWithoutDetaching([$this->user->id]);

        $this->token = $this->user->createToken("EscolaLMS User Token")->accessToken;
    }

    public function test_get_course_lanuch_params()
    {

        $this->response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->json(
            'GET',
            '/api/cmi5/courses/' . $this->course->id
        );

        $this->response->assertOk();

        $this->response->assertJsonStructure([
            'data' => [
                "endpoint",
                "fetch",
                "actor",
                "registration",
                "activityId",
                "url"
            ]

        ]);
    }

    public function test_get_cmi5_fetch()
    {
        $token = "lorem-ipsum";
        $this->response = $this->postJson(
            '/api/cmi5/fetch?token=' . $token
        );

        $this->response->assertOk();

        $this->response->assertJsonStructure([
            'auth-token'
        ]);

        $this->response->assertJson([
            'auth-token' => $token
        ]);
    }
}
