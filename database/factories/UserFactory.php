<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;


class UserFactory extends Factory
{
    
    protected static ?string $password;

    public function definition(): array
    {
        $name = fake('en_GB')->unique()->name();
        return [
            'name' => $name,
            'email' => Str::slug($name) . '@bloggs.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('mysecret'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
