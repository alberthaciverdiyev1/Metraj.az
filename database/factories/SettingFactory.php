<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Setting\Http\Entities\Setting;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'logo' => $this->faker->imageUrl(100, 100, 'business', true, 'logo'),
            'favicon' => $this->faker->imageUrl(50, 50, 'business', true, 'favicon'),
            'description' => $this->faker->paragraph,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'phone_1' => $this->faker->phoneNumber,
            'phone_2' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'google_map_url' => $this->faker->url,
            'whatsapp_number' => $this->faker->phoneNumber,
            'instagram_url' => $this->faker->url,
            'facebook_url' => $this->faker->url,
            'linkedin_url' => $this->faker->url,
            'tiktok_url' => $this->faker->url,
            'payment_username' => $this->faker->userName,
            'payment_password' => $this->faker->password,
            'otp_login' => $this->faker->randomElement(['enabled', 'disabled']),
            'otp_password' => $this->faker->password,
            'otp_sender_name' => $this->faker->company,
            'is_offline' => $this->faker->boolean(50),
            'about' => $this->faker->text,
            'terms_and_conditions' => $this->faker->text,
            'privacy_and_policy' => $this->faker->text,
        ];
    }
}
