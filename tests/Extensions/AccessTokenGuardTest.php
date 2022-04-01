<?php

namespace EscolaLms\Lrs\Tests\Extensions;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Lrs\Extensions\AccessTokenGuard;
use EscolaLms\Courses\Models\User;
use EscolaLms\Lrs\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

class AccessTokenGuardTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = config('auth.providers.users.model')::factory()->create();
        $this->user->guard_name = 'api';
        $this->user->assignRole('student');
    }

    public function tokenDataProvider(): array
    {
        return [
            [
                'expire' => fn() => Passport::personalAccessTokensExpireIn(now()->addDay()),
                'assert' => fn($result) => $this->assertTrue($result)
            ],
            [
                'expire' => fn() => Passport::personalAccessTokensExpireIn(now()->subDay()),
                'assert' => fn($result) => $this->assertFalse($result)
            ],
        ];
    }

    /**
     * @dataProvider tokenDataProvider
     */
    public function testGuard($expireIn, $assert)
    {
        $expireIn();

        $this->token = $this->user->createToken("EscolaLMS User Token")->accessToken;

        $request = new Request();
        $request->headers->set('Authorization', "Basic {$this->token}");

        $guard = new AccessTokenGuard();
        $result = $guard->check(null, $request);

        $assert($result);
    }
}
