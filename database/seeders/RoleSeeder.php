<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles for both Web & API guards
        $roles = [
            // Web guard — Admin Panel
            ['name' => 'admin',   'guard_name' => 'web'],
            ['name' => 'manager', 'guard_name' => 'web'],
            ['name' => 'expert',  'guard_name' => 'web'],
            ['name' => 'client',  'guard_name' => 'web'],

            // API guard — Mobile / REST API
            ['name' => 'expert',  'guard_name' => 'api'],
            ['name' => 'client',  'guard_name' => 'api'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']]
            );
        }

        $this->command->info('✅ Roles created for web & api guards.');

        // 2. All Admin Dashboard Permissions (web guard)
        $permissions = [
            // Dashboard
            'view dashboard',

            // Manage Tags, Categories & Languages
            'view tags',
            'create tags',
            'edit tags',
            'delete tags',
            'manage tags',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'manage categories',
            'view languages',
            'create languages',
            'edit languages',
            'delete languages',
            'manage languages',

            // Manage Users (Experts & Clients)
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage users',

            // Manage Gigs
            'view gigs',
            'create gigs',
            'edit gigs',
            'delete gigs',
            'manage gigs',

            // Manage Orders, QA & Extensions
            'view orders',
            'manage orders',
            'view qa requests',
            'manage qa requests',
            'view extension requests',
            'manage extension requests',

            // Payments & Payouts
            'view payments',
            'manage payments',

            // Support Corner & Contacts
            'view supports',
            'manage supports',

            // Terms & Privacy
            'view terms and privacy',
            'manage terms and privacy',

            // CMS (Hero, Tags, Features, Testimonial, About, etc.)
            'view cms',
            'manage cms',

            // Access Control (Roles, Permissions & Staff Admin Users)
            'view access control',
            'manage access control',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'manage permissions',
            'view admin users',
            'create admin users',
            'edit admin users',
            'delete admin users',
            'manage admin users',

            // Settings (General, Logo, Profile, Mail, Stripe, Social Links, etc.)
            'view settings',
            'manage settings',

            // Additional Modules
            'view faqs',
            'manage faqs',
            'view subscribers',
            'manage subscribers',
            'view social links',
            'manage social links',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('✅ All permissions created for web guard (' . count($permissions) . ' permissions).');

        // 3. Admin gets all web guard permissions
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        if ($adminRole) {
            $allWebPermissions = Permission::where('guard_name', 'web')->get();
            $adminRole->syncPermissions($allWebPermissions);
            $this->command->info('✅ All permissions assigned to admin role.');
        }

        // 4. Default permissions for Manager role
        $managerRole = Role::where('name', 'manager')->where('guard_name', 'web')->first();
        if ($managerRole) {
            $defaultManagerPermissions = [
                'view dashboard',
                'view users',
                'view gigs',
                'view orders',
            ];
            $managerPermissions = Permission::where('guard_name', 'web')
                ->whereIn('name', $defaultManagerPermissions)
                ->get();
            $managerRole->syncPermissions($managerPermissions);
            $this->command->info('✅ Default permissions assigned to manager role.');
        }
    }
}

