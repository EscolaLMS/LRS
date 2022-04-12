<?php

namespace EscolaLms\Lrs\Tests\API;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Lrs\Database\Seeders\LrsSeeder;
use EscolaLms\Lrs\Models\Statement;
use EscolaLms\Lrs\Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable as AuthUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatementApiTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers;

    private AuthUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LrsSeeder::class);
        $this->admin = $this->makeAdmin();
    }

    public function testGetStatements(): void
    {
        Statement::factory()->count(10)->create();
        $response = $this->actingAs($this->admin, 'api')->json('GET', 'api/cmi5/statements');

        $response->assertOk();
        $response->assertJsonCount(10,'data');
    }
}
