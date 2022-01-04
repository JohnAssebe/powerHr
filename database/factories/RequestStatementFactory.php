<?php

namespace Database\Factories;

use App\Models\RequestStatement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestStatementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RequestStatement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $emp = User::where('role', 3)->pluck('id')->shuffle()->first();

        return [
            'patient_id' => $emp,
            'request_statement' => $this->faker->text(30),
            'disorder' => $this->faker->word(),
        ];
    }
}
