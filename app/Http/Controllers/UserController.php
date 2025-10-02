<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\UserRequest;    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserController
{  
    public function LogIn() {
        
        return view('user.login');
    }

    public function index()
    {
        return User::select(['id', 'email', 'username'])
            ->with(['profile', 'status'])
            ->orderBy('username')
            ->get();
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
        \Log::debug('Login attempt started:', [
            'username' => $request->username,
            'csrf_token' => $request->_token,
            'session_id' => session()->getId(),
            'all_input' => $request->all()
        ]);

        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|min:6',
        ]);

        $user = User::where('username', $request->username)->first();
        
        if (!$user) {
            \Log::warning('Login failed - User not found:', ['username' => $request->username]);
            return redirect('/user/login')
                ->withErrors(['username' => 'Invalid username'])
                ->withInput();
        }

        // Get fresh user data to ensure we have the latest password hash
        $user = $user->fresh();
        
        // Detailed debug logging
        \Log::debug('Login attempt details:', [
            'username' => $user->username,
            'provided_password' => $request->password,
            'stored_hash' => $user->password,
            'hash_check' => Hash::check($request->password, $user->password),
            'password_length' => strlen($request->password),
            'hash_length' => strlen($user->password)
        ]);

        // Try manual password verification
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);
            
            \Log::info('Login successful:', [
                'username' => $user->username,
                'user_id' => $user->id
            ]);

            return redirect('/')->with('success', 'Login successful!');
        }

        \Log::warning('Login failed - Invalid password:', [
            'username' => $request->username,
            'password_verification_failed' => true
        ]);

        return redirect('/user/login')
            ->withErrors(['password' => 'Invalid password'])
            ->withInput();
    }

    public function signOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user/login');
    }

    public function forceChangePassword(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->request_change_pass) {
            return redirect('/');
        }

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:6'],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __t('auth.current_password_incorrect', 'The current password is incorrect.'),
            ]);
        }

        $rawPassword = $validated['new_password'];
        $user->password = Hash::make($rawPassword);
        $user->request_change_pass = false;
        $user->save();

        Auth::loginUsingId($user->id);

        return redirect('/')
            ->with('status', __t('auth.password_changed_successfully', 'Password changed successfully.'));
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

        \Log::debug("Request details:", [
            'request' => $request->all()
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
            \Log::debug("Creating user with username: " . $request->username);
            \Log::debug("Hashed password: " . Hash::make($request->password));
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
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
            'request_change_pass' => 'boolean',
        ]);

        if ($validator->fails()) {
            \Log::error("Password validation failed: " . json_encode($validator->errors()));
            return response()->json([
                'message' => 'Validation failed. Please check your input.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($id);
            
            // Store the raw password for verification
            $rawPassword = $request->new_password;
            
            // Update the password using Hash directly
            $hashedPassword = Hash::make($rawPassword);
            $user->password = $hashedPassword;
            
            // Set the request_change_pass flag based on the checkbox value
            $user->request_change_pass = $request->has('request_change_pass') && $request->request_change_pass;
            
            $user->save();

            // Verify the password was stored correctly
            $verificationCheck = Hash::check($rawPassword, $user->fresh()->password);
            
            \Log::debug("Password change details:", [
                'user' => $user->username,
                'raw_password' => $rawPassword,
                'new_hash' => $hashedPassword,
                'stored_hash' => $user->fresh()->password,
                'verification_check' => $verificationCheck
            ]);

            if (!$verificationCheck) {
                throw new \Exception('Password verification failed after change');
            }

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

    public function uploadAvatar(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // Get the image data from Slim
            $imageData = $request->input('output');
            if (empty($imageData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image data provided'
                ], 400);
            }

            // Decode the JSON data from Slim
            $decoded = json_decode($imageData, true);
            if (!isset($decoded['output']['image'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image data format'
                ], 400);
            }

            // Get the base64 image data
            $base64Image = $decoded['output']['image'];

            // Update user's profile with base64 image data
            $user->profile()->update([
                'avatar' => $base64Image
            ]);

            \Log::debug('Avatar updated successfully for user: ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully',
                'path' => $base64Image
            ]);

        } catch (\Exception $e) {
            \Log::error('Avatar upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateNickname(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nickname' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid nickname',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            
            // Update the nickname in the profile
            $user->profile()->update([
                'nickname' => $request->nickname
            ]);

            \Log::debug('Nickname updated successfully for user: ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Nickname updated successfully',
                'nickname' => $request->nickname
            ]);

        } catch (\Exception $e) {
            \Log::error('Nickname update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating nickname: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change current user's password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePasswordProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|string|min:8',
                'confirm_password' => 'required|same:new_password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => [
                        'current_password' => ['The current password is incorrect.']
                    ]
                ], 422);
            }

            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();

            \Log::debug('Password changed successfully for user: ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Password change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error changing password: ' . $e->getMessage()
            ], 500);
        }
    }
}
