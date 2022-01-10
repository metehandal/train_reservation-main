<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VagonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $kapasite    = $this->faker->numberBetween(100, 150);
        $dolu_koltuk = $this->faker->numberBetween(0, 100);
        return [
            'train_id'        => $this->faker->numberBetween(1, 3),
            'kapasite'        => $kapasite,
            'dolu_koltuk'     => $dolu_koltuk,
            'doluluk_yuzdesi' => ($dolu_koltuk * 100) / $kapasite
        ];
    }
}
