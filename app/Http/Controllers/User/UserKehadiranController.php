<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\User;
use Illuminate\Http\Request;

class UserKehadiranController extends Controller
{
    public function getByUUIDKehadiran($uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $Kehadiran = Kehadiran::with('user.role')->where('user_id', $user->id)->first();
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

            $user = User::where('uuid', $request->uuid)->first();
            $file_name = $request->image->getClientOriginalName();
            $namaGambar = str_replace(' ', '_', $file_name);
            $image = $request->image->storeAs('public/image', $namaGambar);
            $Kehadiran = Kehadiran::create([
                'user_id' => $user->id,
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
}
