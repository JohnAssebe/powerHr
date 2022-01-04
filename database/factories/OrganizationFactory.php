<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Organization::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $owner = User::where('role', '=', 1)->first();

        return [
            'organization_id' => 1,
            'owner_id' => $owner->id,
            'name' => $this->faker->name,
            'phone' => '0934094395',
            'sun' => '{"open":"00:30","close":"10:30"}',
            'mon' => '{"open":"00:30","close":"10:30"}',
            'tue' => '{"open":"00:30","close":"10:30"}',
            'wed' => '{"open":"00:30","close":"10:30"}',
            'thu' => '{"open":"00:30","close":"10:30"}',
            'fri' => '{"open":"00:30","close":"10:30"}',
            'sat' => '{"open":"00:30","close":"10:30"}',
            'status' => 1
        ];
    }
}
