<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\RolesEnum;

use function Illuminate\Support\enum_value;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => enum_value(RolesEnum::SUPER_ADMIN)]);
        Role::firstOrCreate(['name' => enum_value(RolesEnum::USER)]);
    }
}
