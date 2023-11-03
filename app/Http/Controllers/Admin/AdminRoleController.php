<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    public function getAllRole()
    {
        $Role = Role::with('user')->get();
        return response()->json(['data' => $Role], 200);
    }

    public function getByIDRole($id)
    {
        $Role = Role::with('user')->where('id', $id)->get();
        return response()->json(['data' => $Role], 200);
    }

    public function createRole(Request $request)
    {
        try {
            request()->validate([
                'nama_role' => 'required',
            ]);

            $Role = Role::create([
                'nama_role' => $request->nama_role,
            ]);
            return response()->json(['message' => 'Berhasil di Create', 'data' => $Role], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateRole(Request $request, $id)
    {
        try {
            request()->validate([
                'nama_role' => 'required',
            ]);

            $Role = Role::where('id', $id)->first();
            $Role->update([
                'nama_role' => $request->nama_role,
            ]);

            return response()->json(['message' => 'Berhasil di Update', 'data' => $Role], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteRole($id)
    {
        $Role = Role::where('id', $id)->first()->delete();
        return response()->json(['message' => 'Berhasil di Delete'], 200);
    }
}
