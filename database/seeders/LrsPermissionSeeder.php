<?php

namespace EscolaLms\Lrs\Database\Seeders;

use EscolaLms\Lrs\Enums\LrsPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LrsPermissionSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::findOrCreate('admin', 'api');

        foreach (LrsPermissionEnum::asArray() as $const => $value) {
            Permission::findOrCreate($value, 'api');
        }

        $admin->givePermissionTo([
            LrsPermissionEnum::STATEMENT_LIST,
        ]);
    }
}
