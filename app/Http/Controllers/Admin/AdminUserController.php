<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AdminUserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::with('kehadiran', 'role')->get();
        return response()->json(['data' => $users], 200);
    }

    public function getByIDUser($role)
    {
        $users = User::with('kehadiran', 'role')->where('uuid', $role)->get();
        return response()->json(['data' => $users], 200);
    }

    public function getByUUIDUsers($uuid)
    {
        $user = User::with('kehadiran', 'role')->where('uuid', $uuid)->first();
        return response()->json(['data' => $user], 200);
    }

    public function createUser(Request $request)
    {
        try {
            request()->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|min:8',
                'image_profile' => 'required',
            ]);

            $file_name = $request->image_profile->getClientOriginalName();
            $namaGambar = str_replace(' ', '_', $file_name);
            $image = $request->image_profile->storeAs('public/image_profile', $namaGambar);
            $user = User::create([
                'uuid' => Str::uuid(),
                'role_id' => $request->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image_profile' => $namaGambar,
            ]);
            return response()->json(['message' => 'Berhasil di Create', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateUser(Request $request, $uuid)
    {
        try {
            request()->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|min:8',
            ]);

            $user = User::where('uuid', $uuid)->first();
            if (Request()->hasFile('image_profile')) {
                if ($user->image_profile && Storage::exists($user->image_profile)) {
                    Storage::delete($user->image_profile);
                }
                $file_name = $request->image_profile->getClientOriginalName();
                $namaGambar = str_replace(' ', '_', $file_name);
                $image = $request->image_profile->storeAs('public/image_profile', $namaGambar);
                $user->update([
                    'role_id' => $request->role_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'image_profile' => $namaGambar,
                ]);
            } else {
                $user->update([
                    'role_id' => $request->role_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
            }
            return response()->json(['message' => 'Berhasil di Update', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteUser($uuid)
    {
        $user = User::where('uuid', $uuid)->first()->delete();
        return response()->json(['message' => 'Berhasil di Delete'], 200);
    }
}
