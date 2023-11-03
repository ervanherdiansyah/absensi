<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminKehadiranController extends Controller
{
    public function getAllKehadiran()
    {
        $kehadiran = Kehadiran::with('user.role')->get();
        return response()->json(['data' => $kehadiran], 200);
    }

    public function getByUUIDKehadiran($uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $Kehadiran = Kehadiran::with('user.role')->where('user_id', $user->id)->first();
        return response()->json(['data' => $Kehadiran], 200);
    }

    public function getByUserIDKehadiran($user_id)
    {
        $Kehadiran = Kehadiran::with('user.role')->where('user_id', $user_id)->first();
        return response()->json(['data' => $Kehadiran], 200);
    }

    public function getByIDKehadiran($id)
    {
        $Kehadiran = Kehadiran::with('user.role')->where('id', $id)->first();
        return response()->json(['data' => $Kehadiran], 200);
    }

    public function createKehadiran(Request $request)
    {
        try {
            request()->validate([
                'in_time' => 'required',
                'out_time' => 'required',
                'image' => 'required',
                'location' => 'required',
                'status' => 'required',
            ]);

            $file_name = $request->image->getClientOriginalName();
            $namaGambar = str_replace(' ', '_', $file_name);
            $image = $request->image->storeAs('public/image', $namaGambar);
            $Kehadiran = Kehadiran::create([
                'user_id' => $request->user_id,
                'in_time' => $request->in_time,
                'out_time' => $request->out_time,
                'location' => $request->location,
                'status' => $request->status,
                'image' => $namaGambar,
            ]);
            return response()->json(['message' => 'Berhasil di Create', 'data' => $Kehadiran], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateKehadiran(Request $request, $id)
    {
        try {
            request()->validate([
                'in_time' => 'required',
                'out_time' => 'required',
                'location' => 'required',
                'status' => 'required',
            ]);

            $Kehadiran = Kehadiran::where('id', $id)->first();
            if (Request()->hasFile('image')) {
                if (Storage::exists($Kehadiran->image)) {
                    Storage::delete($Kehadiran->image);
                }
                $file_name = $request->image->getClientOriginalName();
                $namaGambar = str_replace(' ', '_', $file_name);
                $image = $request->image->storeAs('public/image', $namaGambar);
                $Kehadiran = Kehadiran::create([
                    'user_id' => $request->user_id,
                    'in_time' => $request->in_time,
                    'out_time' => $request->out_time,
                    'location' => $request->location,
                    'status' => $request->status,
                    'image' => $namaGambar,
                ]);
            } else {
                $Kehadiran->update([
                    'user_id' => $request->user_id,
                    'in_time' => $request->in_time,
                    'out_time' => $request->out_time,
                    'location' => $request->location,
                    'status' => $request->status,
                ]);
            }
            return response()->json(['message' => 'Berhasil di Update', 'data' => $Kehadiran], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteKehadiran($id)
    {
        $Kehadiran = Kehadiran::where('id', $id)->first()->delete();
        return response()->json(['message' => 'Berhasil di Delete'], 200);
    }
}
