<?php

namespace Tests\Feature;

use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Modules\Shared\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthRoleRegisterLoginRedirectTest extends TestCase
{
    public function test_normal_user_registration_redirects_to_home(): void
    {
        $this->withoutMiddleware();

        $email = 'user_' . Str::random(8) . '@example.com';

        $response = $this->postJson(route('register.post'), [
            'role_type' => 'user',
            'name' => 'Fərdi İstifadəçi',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect' => '/',
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_agent_registration_creates_agent_and_redirects_to_agency_panel(): void
    {
        $this->withoutMiddleware();

        $email = 'agent_' . Str::random(8) . '@example.com';

        $response = $this->postJson(route('register.post'), [
            'role_type' => 'agent',
            'name' => 'Rieltor Test',
            'email' => $email,
            'phone' => '0501112233',
            'whatsapp' => '0501112233',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect' => '/agency',
            'role' => 'agent',
        ]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->agent);
        $this->assertEquals('0501112233', $user->agent->phone);
    }

    public function test_agency_registration_creates_agency_and_redirects_to_agency_panel(): void
    {
        $this->withoutMiddleware();

        $email = 'agency_' . Str::random(8) . '@example.com';

        $response = $this->postJson(route('register.post'), [
            'role_type' => 'agency',
            'agency_name' => 'Yeni Əmlak Agentliyi',
            'name' => 'Agentlik Rəhbəri',
            'email' => $email,
            'phone' => '0124445566',
            'whatsapp' => '0504445566',
            'address' => 'Bakı ş., Nizami k. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect' => '/agency',
            'role' => 'agency',
        ]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->agencies()->exists());
        $this->assertEquals('Yeni Əmlak Agentliyi', $user->agencies()->first()->name);
    }

    public function test_login_as_agent_or_agency_redirects_to_agency_panel(): void
    {
        $this->withoutMiddleware();

        // 1. Create agency user
        $email = 'login_agency_' . Str::random(8) . '@example.com';
        $user = User::create([
            'name' => 'Agency Owner',
            'email' => $email,
            'password' => Hash::make('secret123'),
        ]);
        Agency::create([
            'owner_id' => $user->id,
            'name' => 'Test Login Agency',
            'email' => $email,
            'phone' => '0509998877',
        ]);

        $response = $this->postJson(route('login.post'), [
            'email' => $email,
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect' => '/agency',
            'role' => 'agency',
        ]);
    }

    public function test_login_as_normal_user_redirects_to_home(): void
    {
        $this->withoutMiddleware();

        $email = 'login_user_' . Str::random(8) . '@example.com';
        User::create([
            'name' => 'Normal User',
            'email' => $email,
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson(route('login.post'), [
            'email' => $email,
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect' => '/',
            'role' => 'user',
        ]);
    }

    public function test_login_and_register_pages_render_views(): void
    {
        $loginRes = $this->get(route('login'));
        $loginRes->assertStatus(200);
        $loginRes->assertSee('Hesaba Daxil Ol');

        $regRes = $this->get(route('register'));
        $regRes->assertStatus(200);
        $regRes->assertSee('Yeni Hesab Yarat');
        $regRes->assertSee('Fərdi İstifadəçi');
        $regRes->assertSee('Rieltor (Agent)');
        $regRes->assertSee('Agentlik');
    }
}
