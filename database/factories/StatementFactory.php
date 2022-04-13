<?php

namespace EscolaLms\Lrs\Database\Factories;

use EscolaLms\Lrs\Models\Statement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Trax\Auth\Stores\Accesses\Access;
use Trax\Auth\Stores\Owners\Owner;

class StatementFactory extends Factory
{
    protected $model = Statement::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'data' => $this->getJsonData(),
            'voided' =>  false,
            'owner_id' => Owner::inRandomOrder()->first(),
            'entity_id' => null,
            'client_id' => null,
            'access_id' => Access::inRandomOrder()->first(),
            'pending' => false,
            'validation' => 1
        ];
    }

    private function getJsonData(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'actor' => [
                'objectType' => 'Agent',
                'account' => [
                    'homePage' => "https://escolalms.com",
                    'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
                ]
            ],
            'context' => [
                'registration' => (string) Str::uuid(),
            ],
            'verb' => [
                'id' => "http://adlnet.gov/expapi/verbs/progressed",
                "display" => [
                    "en-US" => "progressed"
                ]
            ],
            'object' => [
                'objectType' => 'Activity'
            ],
            'version' => '1.0.0'
        ];
    }
}
