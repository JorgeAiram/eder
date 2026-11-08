<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test health check endpoint returns 200 and healthy status.
     */
    public function test_health_check_returns_healthy_status(): void
    {
        // Fallo intencional para demostrar el bloqueo del pipeline en Pull Request
        $this->fail('Fallo intencional: demostracion de bloqueo de CI ante prueba fallida.');

        $response = $this->getJson('/health');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'healthy',
                'services' => [
                    'api' => [
                        'status' => 'operational',
                    ],
                    'database' => [
                        'status' => 'connected',
                    ],
                ],
            ]);
    }
}
