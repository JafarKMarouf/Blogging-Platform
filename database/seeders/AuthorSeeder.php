<?php

namespace Database\Seeders;

use Database\Factories\AuthorFactory;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        AuthorFactory::new()->count(5)->create();
    }
}
