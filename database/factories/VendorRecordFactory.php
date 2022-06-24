<?php

namespace Database\Factories;

use App\Models\VendorRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VendorRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
