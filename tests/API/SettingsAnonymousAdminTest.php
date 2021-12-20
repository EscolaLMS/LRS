<?php

namespace Tests\APIs;

use EscolaLms\Settings\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Settings\Tests\TestCase;
use EscolaLms\Settings\Database\Seeders\PermissionTableSeeder;
use EscolaLms\Settings\Database\Seeders\DatabaseSeeder;

class SettingsAnonymousAdminTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {

        parent::setUp();
        $this->seed(PermissionTableSeeder::class);
        $this->seed(DatabaseSeeder::class);       
    }
    /**
     * @test
     */
    public function test_admin_anonymous_fetch()
    {

        $this->response = $this->json(
            'GET',
            '/api/admin/settings'
        );
        $this->response->assertUnauthorized();

    }

    public function test_admin_anonymous_fetch_paginate()
    {

        $this->response = $this->json(
            'GET',
            '/api/admin/settings?page=2'
        );
        $this->response->assertUnauthorized();

    }

    public function test_admin_anonymous_show()
    {

        $setting = Setting::first();
        $this->response = $this->json(
            'GET',
            '/api/admin/settings/' . $setting->id
        );
        $this->response->assertUnauthorized();
    }

    public function test_admin_anonymous_groups()
    {

        $setting = Setting::first();
        $this->response = $this->json(
            'GET',
            '/api/admin/settings/groups'
        );
        $this->response->assertUnauthorized();

    }

    public function test_admin_anonymous_update()
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

        $this->response = $this->json(
            'PUT',
            '/api/admin/settings/'.$setting->id,
            $input
        );
        
        $this->response->assertUnauthorized();

    }

    public function test_admin_anonymous_create()
    {
        $input = [
            'group' => 'config',
            'key' => 'app.env',
            'value' => "app.env",
            'public' => true,
            'enumerable' => true,
            'type' => 'config'
        ];

        $this->response = $this->json(
            'POST',
            '/api/admin/settings',
            $input
        );
        
        $this->response->assertUnauthorized();

    }

    public function test_admin_anonymous_delete()
    {
        $setting = Setting::first();

        $this->response = $this->json(
            'DELETE',
            '/api/admin/settings/'.$setting->id,
        );
        
        $this->response->assertUnauthorized();


    }
}
