<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SimulationScenario;

class SimulatorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_active_scenarios_by_topic_id()
    {
        SimulationScenario::factory()->create(['topic_id' => 1, 'is_active' => true]);
        SimulationScenario::factory()->create(['topic_id' => 1, 'is_active' => false]);

        $response = $this->getJson('/api/simulator/scenarios?topic_id=1');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_validates_invalid_topic_id()
    {
        $response = $this->getJson('/api/simulator/scenarios?topic_id=99');
        $response->assertStatus(422);
    }

    public function test_can_get_scenario_detail_by_slug()
    {
        $scenario = SimulationScenario::factory()->create(['slug' => 'test-slug', 'is_active' => true]);

        $response = $this->getJson('/api/simulator/scenarios/test-slug');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'test-slug');
    }

    public function test_returns_404_for_inactive_scenario_detail()
    {
        SimulationScenario::factory()->create(['slug' => 'test-slug', 'is_active' => false]);
        $response = $this->getJson('/api/simulator/scenarios/test-slug');
        $response->assertStatus(404);
    }
}
