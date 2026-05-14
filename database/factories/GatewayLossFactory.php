<?php

namespace Database\Factories;

use App\Models\GatewayLoss;
use Illuminate\Database\Eloquent\Factories\Factory;

class GatewayLossFactory extends Factory
{
    protected $model = GatewayLoss::class;

    public function definition(): array
    {
        return [
            'gateway_name' => $this->faker->company() . ' Gateway',
            'loss_percentage' => $this->faker->randomFloat(2, 0, 100),
            'incident_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'resolved_date' => null,
            'status' => $this->faker->randomElement(['open', 'investigating', 'resolved']),
            'impact_duration_minutes' => 0,
            'reported_by' => $this->faker->name()
        ];
    }
}