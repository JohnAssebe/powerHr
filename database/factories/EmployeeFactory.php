<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $emp = User::where('role', 2)->pluck('id')->shuffle()->first();

        return [
            'organization_id' => 1,
            'profession' => 'psychiatrist',
            // 'full_name' => $this->faker->name(),
            'user_id' => $emp,
            'sun' => '{"open":"00:30","close":"10:00"}',
            'mon' => '{"open":"00:30","close":"10:00"}',
            'tue' => '{"open":"00:30","close":"10:00"}',
            'wed' => '{"open":"00:30","close":"10:00"}',
            'thu' => '{"open":"00:30","close":"10:00"}',
            'fri' => '{"open":"00:30","close":"10:00"}',
            'sat' => '{"open":"00:30","close":"10:00"}',
           
        ];
    }
}
