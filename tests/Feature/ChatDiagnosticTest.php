<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChatDiagnosticTest extends TestCase
{
    public function test_it_reports_openai_configuration_status(): void
    {
        $response = $this->get('/chat/diagnostic');

        $response->assertOk();
        $response->assertJsonPath('checks.api_key_configured', true);
        $response->assertJsonPath('checks.route_available', true);
        $response->assertJsonPath('status', 'ok');
    }
}
