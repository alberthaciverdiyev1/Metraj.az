<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaqPageTest extends TestCase
{
    public function test_faq_page_renders_properly(): void
    {
        $response = $this->get('/faq');

        $response->assertStatus(200);
        $response->assertSee('Tez-tez Verilən Suallar');
        $response->assertSee('KibrisKare.com nədir və necə işləyir?');
        $response->assertSee('Kömək Lazımdır?');
    }
}
