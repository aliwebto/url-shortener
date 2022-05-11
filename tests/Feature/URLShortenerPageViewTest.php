<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class URLShortenerPageViewTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_route_link_shortener()
    {
        $response = $this->get(route('link-shorten.index'));

        $response->assertStatus(200);
    }
}
