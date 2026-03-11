<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Str;
use function Laravel\Prompts\text;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\RolesEnum;
use function Illuminate\Support\enum_value;
use function Laravel\Prompts\table;

class CreateNewSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                                {email? : Super admin email}
                                {name? : Super admin name}
                                {password? : Super admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->argument('name');
        $password = $this->argument('password');

        $name = text(
            label: 'Name',
            placeholder: 'Enter the name of the new super admin',
            default: 'Super Admin',
        );
        $email = text(
            label: 'Email',
            placeholder: 'Enter the email of the new super admin',
            default: $email ?? '',
        );

        if (blank($password)) {
            $password = text(
                label: 'Password',
                placeholder: 'Enter the password of the new super admin',
            );
        }

        $password = ! blank($password) ? $password : Str::random(10);

        $user = $this->updateAdminRecord($email, $name, $password);

        $user->assignRole(enum_value(RolesEnum::SUPER_ADMIN));

        table(
            ['Name', 'Email', 'Role', 'Password'],
            [[$name, $email, enum_value(RolesEnum::SUPER_ADMIN), $password]]
        );
    }

    private function updateAdminRecord($email, $name, $password): User
    {
        return User::updateOrCreate(['email' => $email], [
            'name' => $name,
            'password' => Hash::make($password)
        ]);
    }
}
