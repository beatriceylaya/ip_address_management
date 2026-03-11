<?php

namespace Tests\Feature\IpAddressManagement;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class ListIpAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_ip_addresses(): void
    {
        $user = User::factory()->create();

        $first = $user->ipAddresses()->create([
            'ip_address' => '192.168.1.10',
            'label' => 'Office Router',
            'comment' => 'Static IP for office router',
        ]);

        $second = $user->ipAddresses()->create([
            'ip_address' => '10.0.0.25',
            'label' => 'NAS',
            'comment' => null,
        ]);

        $response = $this->actingAs($user, 'api')->getJson('/api/ip-addresses');

        $response->assertStatus(200);

        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'id' => $first->id,
            'user_id' => $user->id,
            'ip_address' => '192.168.1.10',
            'label' => 'Office Router',
            'comment' => 'Static IP for office router',
        ]);
        $response->assertJsonFragment([
            'id' => $second->id,
            'user_id' => $user->id,
            'ip_address' => '10.0.0.25',
            'label' => 'NAS',
            'comment' => null,
        ]);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'user_id',
                'ip_address',
                'label',
                'comment',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
