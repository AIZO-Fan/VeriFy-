<?php

namespace Tests\Feature;

use Tests\TestCase;

class VerifyTest extends TestCase
{
    public function test_pages_load(): void
    {
        foreach (['/', '/detect-scam', '/how-it-works', '/modules', '/modules/1', '/scam-simulator'] as $url) $this->get($url)->assertOk();
    }
    public function test_unknown_module_is_not_found(): void
    {
        $this->get('/modules/99')->assertNotFound();
    }
    public function test_analysis_finds_indicators(): void
    {
        $this->post('/detect-scam', ['mode' => 'text', 'content' => 'Anda menang hadiah, kirim OTP sekarang'])->assertOk()->assertSee('Perlu kewaspadaan tinggi');
    }
    public function test_empty_input_is_rejected(): void
    {
        $this->post('/detect-scam', ['mode' => 'text', 'content' => ''])->assertSessionHasErrors('content');
    }
    public function test_invalid_url_is_rejected(): void
    {
        $this->post('/detect-scam', ['mode' => 'url', 'content' => 'bukan url'])->assertSessionHasErrors('content');
    }
    public function test_no_indicators_does_not_mean_safe(): void
    {
        $this->post('/detect-scam', ['mode' => 'text', 'content' => 'Halo teman'])->assertOk()->assertSee('Tidak ada indikator pada aturan demo');
    }
}
