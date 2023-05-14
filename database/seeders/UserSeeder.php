<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisablingForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    use TruncateTable, DisablingForeignKey;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKey();
        $this->truncate('users');
        $user = \App\Models\User::factory(10)->create();
        $this->enableForeignKey();
    }
}
