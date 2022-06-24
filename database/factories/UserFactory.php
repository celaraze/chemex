<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'department_id' => 1,
            'password' => bcrypt('password'),
            'gender' => '男',
            'ad_tag' => 0,
        ];
    }
}
