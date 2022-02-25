<?php

namespace Database\Factories;

use App\Models\Postit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Postit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'description' => Str::random(100),
            'image_url' => $this->faker->imageUrl(200, 100, 'dogs')
        ];
    }
}
