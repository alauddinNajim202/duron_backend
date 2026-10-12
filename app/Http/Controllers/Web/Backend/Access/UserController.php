<?php

namespace App\Http\Controllers\Web\Backend\Access;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $users = User::where('id', '!=', $user->id)->with(['roles', 'profile'])->orderBy('id', 'desc')->paginate(25);
        return view('backend.layouts.access.users.index', compact('users'));
    }

    public function create()
    {
        return view('backend.layouts.access.users.create', ['roles' => Role::all()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = 'active';
        $user->email_verified_at = now();
        $user->save();

        $slug = \Illuminate\Support\Str::slug($request->name ?: 'user') . '-' . $user->id;
        $user->profile()->create([
            'first_name' => $request->name,
            'username'   => \Illuminate\Support\Str::slug($request->name ?: 'user') . $user->id,
            'slug'       => $slug,
        ]);
        
        $roleModels = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roleModels);

        return redirect()->route('admin.users.index')->with('t-success', 'User created t-successfully');
    }

    public function show($id)
    {
        $user = User::with(['profile'])->find($id);
        return view('backend.layouts.access.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('backend.layouts.access.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::findOrFail($id);
            $user->update([
                'email' => $request->email,
            ]);

            if ($user->profile) {
                $user->profile->update([
                    'first_name' => $request->name,
                ]);
            } else {
                $slug = \Illuminate\Support\Str::slug($request->name ?: 'user') . '-' . $user->id;
                $user->profile()->create([
                    'first_name' => $request->name,
                    'username'   => \Illuminate\Support\Str::slug($request->name ?: 'user') . $user->id,
                    'slug'       => $slug,
                ]);
            }

            $roleModels = Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roleModels);           

            return redirect()->route('admin.users.index')->with('t-success', 'User updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->delete();
        return redirect()->back()->with('t-success', 'User deleted t-successfully');
    }

    public function status(int $id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            redirect()->back()->with('t-error', 'User not found');
        }
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();
        session()->put('t-success', 'Status updated successfully');
        return view('backend.layouts.access.users.show', compact('user'));
    }

    public function card($slug)
    {
        $profile = \App\Models\Profile::where('slug', $slug)->first();
        $user = $profile ? $profile->user : User::find($slug);

        if (!$user) {
            abort(404, 'User not found');
        }

        $logoBase64 = file_exists(public_path('default/logo.png')) ? base64_encode(file_get_contents(public_path('default/logo.png'))) : '';
        $whitelogoBase64 = $logoBase64;
        $backLogoBase64 = $logoBase64;

        $avatarPath = public_path(
            $user->avatar && file_exists(public_path($user->avatar)) ? $user->avatar : 'default/profile.jpg'
        );

        $avatarBase64 = file_exists($avatarPath) ? base64_encode(file_get_contents($avatarPath)) : '';

        //for web
        $cardSlug = $user->slug ?: $user->id;
        $qrCode = QrCode::size(90)->generate(route('admin.users.card', $cardSlug));
        return view('card.web', compact('user', 'logoBase64', 'whitelogoBase64', 'avatarBase64', 'qrCode', 'backLogoBase64'));
    }
}
