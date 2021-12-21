<?php

namespace EscolaLms\Lrs\Database\Seeders;

use Illuminate\Database\Seeder;
use Trax\Auth\Stores\Clients\ClientRepository;
use Trax\Auth\Stores\Owners\Owner;
use Trax\Auth\Stores\Accesses\AccessService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;


class LrsSeeder extends Seeder
{
    public function run()
    {

        $name = 'EscolaLMS';

        $owner = Owner::firstOrCreate([
            'name' => $name
        ], [
            'uuid' => (string) Str::uuid(),
            'meta' => []
        ]);

        $data = [
            "id" => null,
            "name" => $name,
            'permissions' => ['xapi-scope.all'],
            'owner_id' => $owner->id,
            "access" => [
                "id" => null,
                "credentials" => [
                    "username" => $name,
                    "password" => $name,
                ]
            ],
            "endpoint" => null
        ];

        $client_repo = App::make(ClientRepository::class);
        $client = $client_repo->create($data);

        $access_service = App::make(AccessService::class);

        $data = $data['access'];
        $data['client_id'] = $client->id;
        $data['type'] = 'basic_http';
        $data['name'] = $client->name;
        $data['cors'] = '*';

        $access_service->create($data);
    }
}
