<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CreativeWorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'doi' => '10.11111/testedoi',
            'record_source' => 'Base Lattes',
            'type' => 'Artigo',
            'type_schema_org' => 'ScholarlyArticle',

        ];
    }
}
