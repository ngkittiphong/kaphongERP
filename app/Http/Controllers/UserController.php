<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController
{  
    public function LogIn() {
        
        return view('user.login');
    }

    public function signInProcess(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/user/login')
                ->withErrors($validator)
                ->withInput();
        }

        $user = DB::table('users')
            ->select('id')
            ->where('email', $request->email)
            ->where('password', $request->password)
            ->first();

        if (isset($user)) {
            session(['user_id' => $user->id]);
            
            return redirect('/');
        } else {
            return redirect('/user/login')
                ->withErrors(['search' => 'Invalid email or password'])
                ->withInput();
        }
    }


    public function Register() {
        return view('user.register');
    }

    public function createUserProcess(Request $request) {

        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return redirect('/user/register')
                ->withErrors($validator)
                ->withInput();
        }
        $existingUser = DB::table('users')
            ->where('email', $request->email)
            ->first();
            
        if ($existingUser) {
            return redirect('/user/register')
                ->withErrors(['email' => 'email already exists'])
                ->withInput();
        }

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect('/users/list');
    }



    public function form() {
        $id = null;
        $name = null;
        $email = null;
        $password = null;

        return view('users.form', compact('id', 'name', 'email', 'password'));  
    }

    public function list() {
        $users = DB::table('users')
            ->orderBy('id', 'desc')
            ->get();

        return view('users.list', compact('users'));
    }    




    public function edit($id) {
        $user = DB::table('users')->where('id', $id)->first();

        $id = null;
        $name = null;
        $email = null;
        $password = null;

        if (isset($user)) {
            $id = $user->id;
            $name = $user->name;
            $email = $user->email;
            $password = $user->password;
        }

        return view('users.form', compact('id', 'name', 'email', 'password'));
    }

    public function update(Request $request, $id) {
        DB::table('users')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect('/userss/list');
    }

    public function remove($id) {
        DB::table('users')->where('id', $id)->delete();
        return redirect('/users/list');
    }


    public function signOut() {
        session()->forget('user_id');
        return redirect('/user/login');
    }

    public function info() {
        $user_id = session('user_id');
        $user = DB::table('users')
            ->select('id', 'name', 'email')
            ->where('id', $user_id)
            ->first();

        return view('users.info', compact('user'));
    }
}
