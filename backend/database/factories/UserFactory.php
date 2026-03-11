<?php

namespace Database\Factories;

use App\Models\User;
use App\RolesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

use function Illuminate\Support\enum_value;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    protected function assignRoleToUser(User $user, RolesEnum $roleEnum): void
    {
        $role = Role::firstOrCreate(['name' => enum_value($roleEnum)]);
        $user->syncRoles([$role]);
    }

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $this->assignRoleToUser($user, RolesEnum::USER);
        });
    }

    public function superAdmin(): static
    {
        return $this->afterCreating(function (User $user) {
            $this->assignRoleToUser($user, RolesEnum::SUPER_ADMIN);
        });
    }
}
