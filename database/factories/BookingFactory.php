<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::where('role', 3)->pluck('id')->shuffle()->first();
        $emp = User::where('role', 2)->pluck('id')->shuffle()->first();
        return [
            'booking_id' => '#23232',
            'organization_id' => 1,
            'user_id' => $user,
            'emp_id' => $emp,
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'booking_status' => 'pending',
            'session_type' => 'mental_therapy',
            // 'type' => 'individual',
        ];
    }
}
