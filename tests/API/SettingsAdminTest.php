<?php

namespace Tests\APIs;

use EscolaLms\Settings\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Settings\Tests\TestCase;
use EscolaLms\Settings\Database\Seeders\PermissionTableSeeder;
use EscolaLms\Settings\Database\Seeders\DatabaseSeeder;

class SettingsAdminTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {

        parent::setUp();
        $this->seed(PermissionTableSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->user = config('auth.providers.users.model')::factory()->create();
        $this->user->guard_name = 'api';
        $this->user->assignRole('admin');
    }
    /**
     * @test
     */
    public function test_admin_fetch()
    {

        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/settings'
        );
        $this->response->assertOk();

        $this->response->assertJsonFragment(['data' => ["USD", "EUR"]]);
        $this->response->assertJsonFragment(['data' => 'Lorem IPSUM']);
        $this->response->assertJsonFragment(['current_page' => 1]);
    }

    public function test_admin_fetch_paginate()
    {

        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/settings?page=2'
        );
        $this->response->assertOk();

        $this->response->assertJsonFragment(['current_page' => 2]);
    }

    public function test_admin_fetch_search()
    {

        Setting::firstOrCreate([
            'group' => 'images',
            'key' => 'tutor',
            'value' => "tutor_avatar.jpg",
            'public' => true,
            'enumerable' => true,
            'type' => 'image'
        ]);

        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/settings?group=images'
        );
        $this->response->assertOk();

        $this->response->assertJsonFragment(['group' => 'images']);
    }

    public function test_admin_show()
    {

        $setting = Setting::first();
        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/settings/' . $setting->id
        );
        $this->response->assertOk();
    }

    public function test_not_found_show()
    {

        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/settings/9999'
        );
        $this->response->assertNotFound();
    }

    public function test_admin_groups()
    {

        $setting = Setting::first();
        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/settings/groups'
        );
        $this->response->assertOk();

        $this->assertTrue(in_array($setting->group, $this->response->getData()->data));
    }

    public function test_admin_update()
    {
        $setting = Setting::first();

        $input = [
            'group' => 'config',
            'key' => 'app.env',
            'value' => "app.env",
            'public' => true,
            'enumerable' => true,
            'type' => 'config'
        ];

        $this->response = $this->actingAs($this->user, 'api')->json(
            'PUT',
            '/api/admin/settings/' . $setting->id,
            $input
        );

        $this->response->assertOk();

        $this->assertEquals($input['value'], $this->response->getData()->data->value);


        $this->response = $this->actingAs($this->user, 'api')->json(
            'PUT',
            '/api/admin/settings/9999',
            $input
        );
        $this->response->assertNotFound();
    }

    public function test_admin_create()
    {
        $input = [
            'group' => 'config',
            'key' => 'app.env',
            'value' => "app.env",
            'public' => true,
            'enumerable' => true,
            'type' => 'config'
        ];

        $this->response = $this->actingAs($this->user, 'api')->json(
            'POST',
            '/api/admin/settings',
            $input
        );

        $this->response->assertOk();

        $this->assertEquals($input['value'], $this->response->getData()->data->value);
    }

    public function test_admin_delete()
    {
        $setting = Setting::first();

        $this->response = $this->actingAs($this->user, 'api')->json(
            'DELETE',
            '/api/admin/settings/' . $setting->id,
        );

        $this->response->assertOk();

        $this->response = $this->actingAs($this->user, 'api')->json(
            'DELETE',
            '/api/admin/settings/' . $setting->id,
        );

        $this->response->assertNotFound();
    }
}
