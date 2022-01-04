<?php

namespace Database\Seeders;

use App\Models\RequestStatement;
use Illuminate\Database\Seeder;

class RequestStatementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RequestStatement::factory(5)->create();
    }
}
