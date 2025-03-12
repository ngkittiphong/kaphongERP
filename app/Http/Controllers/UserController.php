<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\UserRequest;    
use Illuminate\Http\Request;
class UserController
{  
    public function LogIn() {
        
        return view('user.login');
    }

    // public function signInProcess(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('/user/login')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $user = DB::table('users')
    //         ->select('id')
    //         ->where('email', $request->email)
    //         ->where('password', $request->password)
    //         ->first();

    //     if (isset($user)) {
    //         session(['user_id' => $user->id]);
            
    //         return redirect('/');
    //     } else {
    //         return redirect('/user/login')
    //             ->withErrors(['search' => 'Invalid email or password'])
    //             ->withInput();
    //     }
    // }

    /**
     * Handle user sign-in process.
     */
    public function signinProcess(Request $request)
    {
        // Validate the input data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log in the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            $request->session()->regenerate();

            return redirect('/');
            
            // return response()->json([
            //     'message' => 'Login successful',
            //     'user' => Auth::user(),
            // ], 200);
        }

        // Authentication failed
        return redirect('/user/login')
            ->withErrors(['search' => 'Invalid email or password'])
            ->withInput();
        // return response()->json([
        //     'message' => 'Invalid email or password',
        // ], 401);
    }

    public function signOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user/login');
    }

    // public function store(UserRequest $request)
    // {
    //     User::create([
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return redirect()->back()->with('success', 'User added successfully!');
    // }
    public function store(Request $request)
    {
        // 1) Validation automatically runs here
        //    If validation fails, it redirects back with errors
        //$validated = $request->validated();
        //$validated = $request->validate();
        // $this->validate([
        //     (new UserRequest())->rules()
        //     // or just rely fully on the controller
        // ]);
        //$validator = Validator::make($request->all(), (new UserRequest())->rules());

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            \Log::error("Validation failed: " . json_encode($validator->errors()));
            return response()->json([
                'message' => 'Validation failed. Please check your input.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2) Create the user
            $user = User::create([
                'username'       => $request->username,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'user_type_id'   => $request->user_type_id,
                'user_status_id' => $request->user_status_id,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error creating user: " . $e->getMessage());
            return response()->json([
                'message' => 'Error creating user',
                'error' => $e->getMessage()
            ], 500);
        }

        try {
            // 3) Create the profile
            $user->profile()->create([
                'nickname'       => $request->nickname       ?? '',
                'card_id_no'     => $request->card_id_no     ?? '',
                'fullname_th'    => $request->fullname_th    ?? '',
                'fullname_en'    => $request->fullname_en    ?? '',
                'prefix_en'      => $request->prefix_en      ?? '',
                'prefix_th'      => $request->prefix_th      ?? '',
                'birth_date'     => $request->birth_date     ?? null,
                'description'    => $request->description    ?? '',
                'avatar'         => $request->avatar         ?? '',
            ]);
        } catch (\Exception $e) {
            \Log::error("Error creating user profile: " . $e->getMessage());
            return response()->json([
                'message' => 'Error creating user profile',
                'error' => $e->getMessage()
            ], 500);
        }

        // 4) Return JSON or redirect
        return response()->json([
            'message' => 'User and profile created successfully!',
            'user_id' => $user->id,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // 1) Validation automatically runs here
        //    If validation fails, it redirects back with errors
        //$validated = $request->validated();
        //$validated = $request->validate();
        // $this->validate([
        //     (new UserRequest())->rules()
        //     // or just rely fully on the controller
        // ]);
        //$validator = Validator::make($request->all(), (new UserRequest())->rules());

        try {
            // 2) Find the user
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error finding user',
                'error' => $e->getMessage()
            ], 404);
        }

        try {
            // 3) Update the profile
            $user->profile()->update([
                'nickname'       => $request->nickname       ?? '',
                'card_id_no'     => $request->card_id_no     ?? '',
                'fullname_th'    => $request->fullname_th    ?? '',
                'fullname_en'    => $request->fullname_en    ?? '',
                'prefix_en'      => $request->prefix_en      ?? '',
                'prefix_th'      => $request->prefix_th      ?? '',
                'birth_date'     => $request->birth_date     ?? null,
                'description'    => $request->description    ?? '',
                'avatar'         => $request->avatar         ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating user profile',
                'error' => $e->getMessage()
            ], 500);
        }

        // 4) Return JSON or redirect
        return response()->json([
            'message' => 'User and profile updated successfully!',
            'user_id' => $user->id,
        ], 200);
    }

    /**
     * Change user password
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            \Log::error("Password validation failed: " . json_encode($validator->errors()));
            return response()->json([
                'message' => 'Validation failed. Please check your input.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the user
            $user = User::findOrFail($id);
            
            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'message' => 'Password changed successfully!',
                'user_id' => $user->id,
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error("Error changing password: " . $e->getMessage());
            return response()->json([
                'message' => 'Error changing password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
