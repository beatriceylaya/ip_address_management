<?php

namespace Database\Seeders;

use App\Models\User;
use App\RolesEnum;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $userFactory = User::factory();

        $normalUsers = [
            [
                'name'  => 'Test User',
                'email' => 'test@example.com',
                'ip_addresses' => [
                    ['ip_address' => '192.168.10.10', 'label' => 'Office Router',     'comment' => 'Static IP for office router'],
                    ['ip_address' => '192.168.10.11', 'label' => 'Office NAS',        'comment' => 'File storage'],
                    ['ip_address' => '192.168.10.12', 'label' => 'WiFi Access Point', 'comment' => null],
                ],
            ],
            [
                'name'  => 'Alice User',
                'email' => 'alice.user@example.com',
                'ip_addresses' => [
                    ['ip_address' => '10.0.0.10', 'label' => 'Dev Server',      'comment' => 'CI host'],
                    ['ip_address' => '10.0.0.11', 'label' => 'Build Agent',     'comment' => null],
                    ['ip_address' => '10.0.0.12', 'label' => 'Monitoring Node', 'comment' => 'Prometheus target'],
                ],
            ],
            [
                'name'  => 'Bob User',
                'email' => 'bob.user@example.com',
                'ip_addresses' => [
                    ['ip_address' => '172.16.0.10', 'label' => 'Staging API', 'comment' => null],
                    ['ip_address' => '172.16.0.11', 'label' => 'VPN Gateway', 'comment' => 'Inbound VPN'],
                    ['ip_address' => '172.16.0.12', 'label' => 'DB Replica',  'comment' => 'Read-only replica'],
                ],
            ],
            [
                'name'  => 'Carol User',
                'email' => 'carol.user@example.com',
                'ip_addresses' => [
                    ['ip_address' => '192.168.20.10', 'label' => 'Warehouse Router',    'comment' => 'Site gateway'],
                    ['ip_address' => '192.168.20.11', 'label' => 'Barcode Scanner Hub', 'comment' => null],
                    ['ip_address' => '192.168.20.12', 'label' => 'Terminal Server',     'comment' => 'RDP access'],
                ],
            ],
            [
                'name'  => 'Dave User',
                'email' => 'dave.user@example.com',
                'ip_addresses' => [
                    ['ip_address' => '10.10.10.10', 'label' => 'K8s Control Plane', 'comment' => 'Master endpoint'],
                    ['ip_address' => '10.10.10.11', 'label' => 'K8s Worker 1',      'comment' => null],
                    ['ip_address' => '10.10.10.12', 'label' => 'K8s Worker 2',      'comment' => null],
                ],
            ],
        ];

        foreach ($normalUsers as $data) {
            $ipAddresses = $data['ip_addresses'];
            $attributes  = array_diff_key($data, array_flip(['ip_addresses']));
 
            $user = User::where('email', $attributes['email'])->first();
 
            if (! $user) {
                $user = User::factory()
                    ->configure()
                    ->create($attributes);
            } else {
                $user->update(['name' => $attributes['name']]);
                $userFactory->syncRolesToUser($user, RolesEnum::USER);
            }
 
            $this->seedIpAddresses($user, $ipAddresses);
        }

        $superAdmins = [
            [
                'name'  => 'Root Admin',
                'email' => 'root.admin@example.com',
                'ip_addresses' => [
                    ['ip_address' => '192.168.100.10', 'label' => 'Core Switch',  'comment' => 'Distribution layer'],
                    ['ip_address' => '192.168.100.11', 'label' => 'Auth Gateway', 'comment' => null],
                    ['ip_address' => '192.168.100.12', 'label' => 'Log Collector', 'comment' => 'Central syslog'],
                ],
            ],
            [
                'name'  => 'Primary Super Admin',
                'email' => 'primary.superadmin@example.com',
                'ip_addresses' => [
                    ['ip_address' => '10.100.0.10', 'label' => 'Edge Router',   'comment' => 'WAN uplink'],
                    ['ip_address' => '10.100.0.11', 'label' => 'Firewall',      'comment' => null],
                    ['ip_address' => '10.100.0.12', 'label' => 'Reverse Proxy', 'comment' => 'TLS termination'],
                ],
            ],
            [
                'name'  => 'Secondary Super Admin',
                'email' => 'secondary.superadmin@example.com',
                'ip_addresses' => [
                    ['ip_address' => '172.31.0.10', 'label' => 'Staging Proxy', 'comment' => null],
                    ['ip_address' => '172.31.0.11', 'label' => 'Cache Server',  'comment' => 'Redis'],
                    ['ip_address' => '172.31.0.12', 'label' => 'Email Relay',   'comment' => 'SMTP relay'],
                ],
            ],
        ];

        foreach ($superAdmins as $data) {
            $ipAddresses = $data['ip_addresses'];
            $attributes  = array_diff_key($data, array_flip(['ip_addresses']));
 
            $admin = User::where('email', $attributes['email'])->first();
 
            if (! $admin) {
                $admin = User::factory()
                    ->superAdmin()
                    ->create($attributes);
            } else {
                $admin->update(['name' => $attributes['name']]);
            }
 
            $userFactory->syncRolesToUser($admin, RolesEnum::SUPER_ADMIN, RolesEnum::USER);
 
            $this->seedIpAddresses($admin, $ipAddresses);
        }
    }

    private function seedIpAddresses(User $user, array $ipAddresses): void
    {
        foreach ($ipAddresses as $entry) {
            $user->ipAddresses()->updateOrCreate(
                ['ip_address' => $entry['ip_address']],
                [
                    'label'   => $entry['label'],
                    'comment' => $entry['comment'],
                ],
            );
        }
    }
}
