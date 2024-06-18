<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_can_login(): void
    {
        $data = [
            'email' => 'rico.hammes@example.org',
            'password' => 'password',
        ];

        $response = $this->post('/api/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'token'
            ]);
    }

    public function test_can_get_me(): void
    {
        $token = '1|2jmJrYuIv3wxCfX12PsDmpwPvmFGOZWHjTfa7Hkd72ed1fbc';

        $response = $this->get('/api/me', [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }
}
