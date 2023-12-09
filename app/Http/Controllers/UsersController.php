<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::whereId($id)->first();
        return view('users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::whereId($id)->first();

        $user->update(request()->all());

        return redirect()->route('users')->with('success', 'User updated sucssfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::whereId($id)->first();
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted sucssfully.');
    }
}
