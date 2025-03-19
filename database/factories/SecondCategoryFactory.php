<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\FirstCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecondCategory>
 */
class SecondCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'first_category_id' => FirstCategory::factory(),
            'name' => fake()->word(),
        ];
    }
}
