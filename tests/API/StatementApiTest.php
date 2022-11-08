<?php

namespace EscolaLms\Lrs\Tests\API;

use Carbon\Carbon;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Lrs\Database\Seeders\LrsPermissionSeeder;
use EscolaLms\Lrs\Database\Seeders\LrsSeeder;
use EscolaLms\Lrs\Models\Statement;
use EscolaLms\Lrs\Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable as AuthUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

class StatementApiTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers, WithFaker;

    private AuthUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LrsSeeder::class);
        $this->seed(LrsPermissionSeeder::class);
        $this->admin = $this->makeAdmin();
    }

    public function testGetStatements(): void
    {
        Statement::factory()->count(10)->create();
        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements');

        $this->assertStatementResponse($response, 10);
    }

    public function testGetStatementsUnauthorized(): void
    {
        $this->json('GET', 'api/admin/cmi5/statements')
            ->assertUnauthorized();
    }

    public function testGetStatementsForbidden(): void
    {
        $student = $this->makeStudent();
        $this->actingAs($student, 'api')
            ->json('GET', 'api/admin/cmi5/statements')
            ->assertForbidden();
    }

    public function testGetStatementsPagination(): void
    {
        Statement::factory()->count(25)->create();

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?per_page=10');
        $this->assertStatementResponse($response, 10);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?per_page=10&page=3');
        $this->assertStatementResponse($response, 5);
    }

    public function testGetStatementsOrderBy(): void
    {
        $startDate = Carbon::now()->subDay();
        $start = Statement::factory()->create(['created_at' => $startDate]);
        $endDate = Carbon::now()->addDay();
        $end = Statement::factory()->create(['created_at' => $endDate]);
        Statement::factory()->count(10)->create();

        $response = $this
            ->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?per_page=15&order_by=created_at&order=desc');

        $this->assertStatementResponse($response, 12);

        $data = $response->getData()->data;
        $this->assertEquals($end->created_at, Carbon::parse($data[0]->created_at));
        $this->assertEquals($start->created_at, Carbon::parse($data[count($data) - 1]->created_at));
    }

    public function testGetStatementsFilterByVerb(): void
    {
        $verb = [
            'id' => 'http://adlnet.gov/expapi/verbs/initialized',
            'display' => [
                'en-US' => "initialized"
            ]
        ];
        Statement::factory()->count(10)->create(['data' => $this->getData(null, null, null, $verb)]);
        Statement::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements');
        $this->assertStatementResponse($response, 15);
        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?verb=initialized');
        $this->assertStatementResponse($response, 10);
        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?verb=http://adlnet.gov/expapi/verbs/initialized');
        $this->assertStatementResponse($response, 10);
    }

    public function testGetStatementsFilterByAccount(): void
    {
        $actor = [
            'objectType' => 'Agent',
            'account' => [
                'homePage' => 'https://test.com',
                'name' => 'test@test.com',
            ]
        ];
        Statement::factory()->count(10)->create(['data' => $this->getData(null, $actor)]);
        Statement::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements');
        $this->assertStatementResponse($response, 15);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?account=test@test.com');
        $this->assertStatementResponse($response, 10);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?account=https://test.com');
        $this->assertStatementResponse($response, 10);
    }

    public function testGetStatementsFilterByRegistration(): void
    {
        $context = ['registration' => '123456789'];
        Statement::factory()->count(10)->create(['data' => $this->getData(null, null, $context)]);
        Statement::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements');
        $this->assertStatementResponse($response, 15);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?registration=123456789');
        $this->assertStatementResponse($response, 10);
    }

    public function testGetStatementsFilterByObject(): void
    {
        $object = [
            'objectType' => 'Test'
        ];
        Statement::factory()->count(10)->create(['data' => $this->getData(null, null, null, null, $object)]);
        Statement::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements');
        $this->assertStatementResponse($response, 15);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?object=Test');
        $this->assertStatementResponse($response, 10);
    }

    public function testGetStatementsFilterByVersion(): void
    {
        Statement::factory()->count(10)->create(['data' => $this->getData(null, null, null, null, null, '1.2.3')]);
        Statement::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements');
        $this->assertStatementResponse($response, 15);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?version=1.2.3');
        $this->assertStatementResponse($response, 10);
    }

    public function testGetStatementsFilterByDateFrom(): void
    {
        $dateFrom = Carbon::now()->subDays(5);
        Statement::factory()->count(5)->create([
            'created_at' => $dateFrom
        ]);
        Statement::factory()->count(10)->create([
            'created_at' => Carbon::now()->subMonth()
        ]);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?per_page=50');
        $this->assertStatementResponse($response, 15);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?date_from=' . $dateFrom);
        $this->assertStatementResponse($response, 5);
    }

    public function testGetStatementsFilterByDateTo(): void
    {
        $dateTo = Carbon::now()->subDays(5);
        Statement::factory()->count(5)->create([
            'created_at' => $dateTo
        ]);
        Statement::factory()->count(10)->create([
            'created_at' => Carbon::now()->addMonth()
        ]);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?per_page=50');
        $this->assertStatementResponse($response, 15);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?date_to=' . $dateTo);
        $this->assertStatementResponse($response, 5);
    }

    public function testGetStatementsFilterByDates(): void
    {
        $dateFrom = Carbon::now()->subDays(5);
        $dateTo = Carbon::now()->subDays(5);
        Statement::factory()->count(5)->create([
            'created_at' => $dateFrom
        ]);
        Statement::factory()->count(5)->create([
            'created_at' => $dateTo
        ]);
        Statement::factory()->count(20)->create([
            'created_at' => Carbon::now()->addMonth()
        ]);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?per_page=50');
        $this->assertStatementResponse($response, 30);

        $response = $this->actingAs($this->admin, 'api')
            ->json('GET', 'api/admin/cmi5/statements?date_from=' . $dateFrom . '&date_to=' . $dateTo);
        $this->assertStatementResponse($response, 10);
    }

    private function assertStatementResponse(TestResponse $response, int $count): void
    {
        $response->assertOk();
        $response->assertJsonCount($count, 'data');
        $response->assertJsonStructure([
            'data' => [[
                'id',
                'uuid',
                'data',
                'voided',
                'owner_id',
                'entity_id',
                'client_id',
                'access_id',
                'pending',
                'validation',
            ]]
        ]);
    }

    private function getData(
        ?string $id = null,
        ?array $actor = null,
        ?array $context = null,
        ?array $verb = null,
        ?array $object = null,
        ?string $version = null
    ): array
    {
        return [
            'id' => $id ?? (string) Str::uuid(),
            'actor' => $actor ?? [
                'objectType' => 'Agent',
                'account' => [
                    'homePage' => "https://escolalms.com",
                    'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
                ]
            ],
            'context' => $context ?? [
                'registration' => (string) Str::uuid(),
            ],
            'verb' => $verb ?? [
                'id' => "http://adlnet.gov/expapi/verbs/progressed",
                "display" => [
                    "en-US" => "progressed"
                ]
            ],
            'object' => $object ?? [
                'objectType' => 'Activity'
            ],
            'version' => $version ?? '1.0.0'
        ];
    }
}
