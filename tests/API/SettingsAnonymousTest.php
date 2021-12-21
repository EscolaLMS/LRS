<?php

namespace Tests\APIs;

use EscolaLms\Settings\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Settings\Tests\TestCase;

class SettingsAnonymousTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {

        parent::setUp();
        Setting::firstOrCreate([
            'group' => 'currencies',
            'key' => 'default',
            'value' => 'USD',
            'public' => true,
            'enumerable' => true,
            'type' => 'text'
        ]);

        Setting::firstOrCreate([
            'group' => 'currencies',
            'key' => 'enum',
            'value' => json_encode(["USD", "EUR"]),
            'public' => true,
            'enumerable' => true,
            'type' => 'json'
        ]);

        Setting::firstOrCreate([
            'group' => 'currencies',
            'key' => 'description',
            'value' => "Lorem Ipsum",
            'public' => true,
            'enumerable' => false,
            'type' => 'text'
        ]);

        Setting::firstOrCreate([
            'group' => 'currencies',
            'key' => 'hidden',
            'value' => "Lorem Ipsum",
            'public' => false,
            'enumerable' => false,
            'type' => 'text'
        ]);

        Setting::firstOrCreate([
            'group' => 'config',
            'key' => 'name',
            'value' => "app.name",
            'public' => true,
            'enumerable' => true,
            'type' => 'config'
        ]);
    }
    /**
     * @test
     */
    public function test_anonymous_fetch()
    {

        $this->response = $this->json(
            'GET',
            '/api/settings'
        );
        $this->response->assertOk();

        $this->response->assertJsonFragment(['enum' => ["USD", "EUR"]]);
        $this->response->assertJsonFragment(['name' => 'Lorem IPSUM']);

        //  'public' => false,
        $this->response->assertJsonMissing(['hidden' =>  "Lorem Ipsum"]);

        //  'enumerable' => false,
        $this->response->assertJsonMissing(['description' =>  "Lorem Ipsum"]);
    }

    public function test_anonymous_show()
    {

        $this->response = $this->json(
            'GET',
            '/api/settings/currencies/description'
        );
        $this->response->assertOk();

        $this->response->assertJsonFragment(['value' => 'Lorem Ipsum']);
    }
}
