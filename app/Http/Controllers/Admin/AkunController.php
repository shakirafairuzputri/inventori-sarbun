<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AkunController extends Controller
{
    public function viewAkun(Request $request)
    {
        $status = $request->input('status'); 
    
        $users = User::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->get();
            return view('admin.kelola-user', compact('users', 'status'));
    }
    public function storeAkun(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.kelola-user')->with('success', 'User berhasil ditambahkan');
    }
    public function editAkun($id)
    {
        $user = User::findOrFail($id); // Fetch the user by ID
        return view('admin.edit-user', compact('user')); // Pass user data to the view
    }

    public function updateAkun(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->route('admin.kelola-user')->with('success', 'User updated successfully!');
    }
}
