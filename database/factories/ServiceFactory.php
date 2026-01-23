<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::where('role', 'provider')->inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->numberBetween(5000, 100000),
            'price_type' => $this->faker->randomElement(['fixed', 'hourly', 'daily']),
            'duration' => $this->faker->numberBetween(30, 480),
            'tags' => $this->faker->words(3),
            'latitude' => $this->faker->latitude(0.3, 0.5),
            'longitude' => $this->faker->longitude(9.3, 9.6),
            'address' => $this->faker->address,
            'status' => 'approved',
            'is_active' => true,
            'rating' => $this->faker->randomFloat(1, 3.0, 5.0),
            'reviews_count' => $this->faker->numberBetween(0, 100),
        ];
    }
}
