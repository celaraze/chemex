<?php

namespace Database\Factories;

use App\Models\DeviceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeviceCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'order' => 0,
        ];
    }
}
