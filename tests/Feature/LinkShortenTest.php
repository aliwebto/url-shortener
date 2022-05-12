<?php

namespace Tests\Feature;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LinkShortenTest extends TestCase
{
    /**
     * test creating link and assert success
     *
     * @return void
     */
    public function test_create()
    {
        $this->json('post', route('link-shorten.api.create'),['url' => 'https://google.com'])
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'success',
                    'response' => [
                        'link',
                        'full_link'
                    ]
                ]
            );
        $link = Url::where('url','https://google.com')->first();
        $this->assertNotNull($link);
    }
    /**
     * test validation required
     *
     * @return void
     */
    public function test_create_fails_url_required()
    {
        $this->json('post', route('link-shorten.api.create'),['url' => ''])
            ->assertStatus(400);
    }
    /**
     * test validation max
     *
     * @return void
     */
    public function test_create_fails_url_max()
    {
        $this->json('post', route('link-shorten.api.create'),['url' => 'https://google.com'.Str::random(270)])
            ->assertStatus(400);
    }
    /**
     * test validation url
     *
     * @return void
     */
    public function test_create_fails_not_a_url()
    {
        $this->json('post', route('link-shorten.api.create'),['url' => Str::random(20)])
            ->assertStatus(400);
    }

    /**
     * test redirect user to target
     *
     * @return void
     */
    public function test_redirect()
    {
        $url = 'http://'.Str::random(16).'.com';
        $urlCreate = Http::post(route('link-shorten.api.create',['url'=>$url]));

        $shortLink = $urlCreate->json()['response']['link'];

        $this->get($shortLink)->assertStatus(302)->assertLocation($url);
    }
}
