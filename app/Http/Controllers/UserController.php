<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('pages.master_data.users', compact('users'));
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if($request->hasFile('photo')){
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        User::create($data);

        return response()->json(['success' => true]);
    }
    public function show($id)
    {
        $user = User::findOrFail($id);

        $user->photo_url = $user->photo 
            ? asset('storage/' . $user->photo) 
            : asset('default-user.png');

        return response()->json($user);
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->all();

        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if($request->hasFile('photo')){
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        return response()->json(['success' => true]);
    }
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    } 
}