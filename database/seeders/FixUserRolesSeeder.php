<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class FixUserRolesSeeder extends Seeder
{
    /**
     * Restore missing roles for users 3 through 16.
     */
    public function run(): void
    {
        // 1. Clear Spatie cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Ensure roles exist
        $expertWeb = Role::firstOrCreate(['name' => 'expert', 'guard_name' => 'web']);
        $expertApi = Role::firstOrCreate(['name' => 'expert', 'guard_name' => 'api']);
        $clientWeb = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $clientApi = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'api']);

        // 3. Experts list: [3, 11, 12, 14, 15, 16]
        $expertIds = [3, 11, 12, 14, 15, 16];
        foreach ($expertIds as $id) {
            $user = User::find($id);
            if ($user) {
                // Assign web role via Spatie
                $user->syncRoles([$expertWeb]);

                // Also insert API guard role into model_has_roles
                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id'    => $expertApi->id,
                    'model_type' => User::class,
                    'model_id'   => $user->id,
                ]);

                $this->command?->info("✅ Assigned [expert] (web + api) to User #{$id} ({$user->email})");
            }
        }

        // 4. Clients list: [4, 5, 6, 7, 8, 9, 10, 13]
        $clientIds = [4, 5, 6, 7, 8, 9, 10, 13];
        foreach ($clientIds as $id) {
            $user = User::find($id);
            if ($user) {
                // Assign web role via Spatie
                $user->syncRoles([$clientWeb]);

                // Also insert API guard role into model_has_roles
                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id'    => $clientApi->id,
                    'model_type' => User::class,
                    'model_id'   => $user->id,
                ]);

                $this->command?->info("✅ Assigned [client] (web + api) to User #{$id} ({$user->email})");
            }
        }

        // 5. Also ensure User 24, 25 (expert) and User 23, 26 (client) have api guard role
        foreach ([24, 25] as $eid) {
            DB::table('model_has_roles')->insertOrIgnore([
                'role_id'    => $expertApi->id,
                'model_type' => User::class,
                'model_id'   => $eid,
            ]);
        }
        foreach ([23, 26] as $cid) {
            DB::table('model_has_roles')->insertOrIgnore([
                'role_id'    => $clientApi->id,
                'model_type' => User::class,
                'model_id'   => $cid,
            ]);
        }

        // 6. Clear Spatie cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command?->info("🎉 All user roles restored successfully!");
    }
}
