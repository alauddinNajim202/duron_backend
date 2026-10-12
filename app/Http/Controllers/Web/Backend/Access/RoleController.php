<?php

namespace App\Http\Controllers\Web\Backend\Access;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->paginate(25);
        return view('backend.layouts.access.roles.index', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        return view('backend.layouts.access.roles.create', [
            'permissionGroups' => $this->getGroupedPermissions('web'),
            'totalPermissions' => Permission::where('guard_name', 'web')->count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'required',
            'guard_name' => 'required|in:web,api'
        ]);
        try {
            $role = Role::create(['name' => $request->name, 'guard_name' => $request->guard_name]);
            $role->syncPermissions($request->permissions);
            return redirect()->route('admin.roles.index')->with('t-success', 'Role created successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('t-error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::find($id);
        return view('backend.layouts.access.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->getGroupedPermissions($role->guard_name),
            'totalPermissions' => Permission::where('guard_name', $role->guard_name)->count(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'required',
            'guard_name' => 'required|in:web,api'
        ]);
        try {
            $role = Role::find($id);
            $role->update(['name' => $request->name, 'guard_name' => $request->guard_name]);
            //must pass name not id
            $role->syncPermissions($request->permissions);
            return redirect()->route('admin.roles.index')->with('t-success', 'Role updated successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('t-error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        $role = Role::find($id);
        return view('backend.layouts.access.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->getGroupedPermissions($role->guard_name),
            'totalPermissions' => Permission::where('guard_name', $role->guard_name)->count(),
        ]);
    }

    public function destroy($id)
    {
        try {
            $role = Role::find($id);
            $role->delete();
            return redirect()->back()->with('t-success', 'Role deleted successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('t-error', $exception->getMessage());
        }
    }

    /**
     * Group permissions systematically by module
     */
    private function getGroupedPermissions($guardName = 'web')
    {
        $allPermissions = Permission::where('guard_name', $guardName)->get();

        $groupDefinitions = [
            'Dashboard' => [
                'icon' => 'fa-gauge-high',
                'permissions' => [
                    'view dashboard',
                ],
            ],
            'User Management (Experts & Clients)' => [
                'icon' => 'fa-users',
                'permissions' => [
                    'view users',
                    'create users',
                    'edit users',
                    'delete users',
                    'manage users',
                ],
            ],
            'Gig Management' => [
                'icon' => 'fa-gem',
                'permissions' => [
                    'view gigs',
                    'create gigs',
                    'edit gigs',
                    'delete gigs',
                    'manage gigs',
                ],
            ],
            'Order Management (Orders, QA & Extensions)' => [
                'icon' => 'fa-first-order',
                'permissions' => [
                    'view orders',
                    'manage orders',
                    'view qa requests',
                    'manage qa requests',
                    'view extension requests',
                    'manage extension requests',
                ],
            ],
            'Tags, Categories & Languages' => [
                'icon' => 'fa-tags',
                'permissions' => [
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
                ],
            ],
            'Payments & Transactions' => [
                'icon' => 'fa-dollar-sign',
                'permissions' => [
                    'view payments',
                    'manage payments',
                ],
            ],
            'Support Corner' => [
                'icon' => 'fa-circle-question',
                'permissions' => [
                    'view supports',
                    'manage supports',
                ],
            ],
            'Terms & Privacy' => [
                'icon' => 'fa-file-contract',
                'permissions' => [
                    'view terms and privacy',
                    'manage terms and privacy',
                ],
            ],
            'CMS (Content Management)' => [
                'icon' => 'fa-home',
                'permissions' => [
                    'view cms',
                    'manage cms',
                ],
            ],
            'Access Control (Roles, Permissions & Staff)' => [
                'icon' => 'fa-user-shield',
                'permissions' => [
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
                ],
            ],
            'Settings' => [
                'icon' => 'fa-gear',
                'permissions' => [
                    'view settings',
                    'manage settings',
                ],
            ],
            'Additional Features (FAQ, Subscribers, Social Links)' => [
                'icon' => 'fa-sliders',
                'permissions' => [
                    'view faqs',
                    'manage faqs',
                    'view subscribers',
                    'manage subscribers',
                    'view social links',
                    'manage social links',
                ],
            ],
        ];

        $grouped = [];
        $matchedIds = [];

        foreach ($groupDefinitions as $groupName => $config) {
            $items = $allPermissions->whereIn('name', $config['permissions'])->values();
            if ($items->isNotEmpty()) {
                $grouped[$groupName] = [
                    'icon' => $config['icon'],
                    'permissions' => $items,
                ];
                $matchedIds = array_merge($matchedIds, $items->pluck('id')->toArray());
            }
        }

        // Any custom permissions not defined in predefined groups
        $others = $allPermissions->whereNotIn('id', $matchedIds)->values();
        if ($others->isNotEmpty()) {
            $grouped['Other Permissions'] = [
                'icon' => 'fa-circle-check',
                'permissions' => $others,
            ];
        }

        return $grouped;
    }
}