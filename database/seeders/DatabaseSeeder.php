<?php

namespace Database\Seeders;

use App\Models\RequestStatement;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(Q_A_Seeder::class);
       
        $this->call(DisorderSeeder::class);
        $this->call(DisorderTherapistSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(RequestStatementSeeder::class);
        $this->call(BookingSeeder::class);
         
    }
}
