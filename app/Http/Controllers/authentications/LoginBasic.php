<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    // Check if user exists
    $user = User::where('email', $credentials['email'])->first();

    if (!$user) {
      Log::info('Login failed: User not found with email ' . $credentials['email']);
      return back()->withErrors([
        'email' => 'No account found with this email address.',
      ])->withInput($request->except('password'));
    }

    Log::info('Login attempt details', [
      'email' => $credentials['email'],
      'user_id' => $user->id,
      'manual_hash_check' => Hash::check($credentials['password'], $user->password)
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
      $request->session()->regenerate();

      $user = Auth::user();
      Log::info('User logged in successfully: ' . $user->email);

      // Set session flag for password change modal if password not changed
      // if (!$user->password_changed && !$user->is_admin && $user->email !== 'admin@example.com') {
      //   session(['show_password_modal' => true]);
      // }

      // Redirect based on user role
      if ($user->role === 'admin' || $user->is_admin) {
        return redirect()->intended('/dashboard/crm');
      } elseif ($user->role === 'agent') {
        return redirect()->intended('/events');
      } else {
        // For regular users (bulk registered users)
        return redirect()->intended('/supplie/dashboard');
      }
    }

    Log::warning('Login failed: Invalid password for user ' . $credentials['email'], [
      'auth_attempt_result' => Auth::attempt($credentials),
      'manual_hash_check' => Hash::check($credentials['password'], $user->password),
      'user_password_hash' => substr($user->password, 0, 20) . '...'
    ]);
    return back()->withErrors([
      'password' => 'The provided password is incorrect.',
    ])->withInput($request->except('password'));
  }

}
