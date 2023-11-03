<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getByUUIDUsers($uuid)
    {
        $user = User::with('kehadiran', 'role')->where('uuid', $uuid)->first();
        return response()->json(['data' => $user], 200);
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
                if (Storage::exists($user->image_profile)) {
                    Storage::delete($user->image_profile);
                }
                $file_name = $request->image_profile->getClientOriginalName();
                $namaGambar = str_replace(' ', '_', $file_name);
                $image = $request->image_profile->storeAs('public/image_profile', $namaGambar);
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'image_profile' => $namaGambar,
                ]);
            } else {
                $user->update([
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
}
