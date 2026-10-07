<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    /**
     * Show customer login form
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('pages.auth.login');
    }

    /**
     * Handle customer login submission
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'E-posta adresinizi giriniz.',
            'email.email' => 'Geçerli bir e-posta adresi yazınız.',
            'password.required' => 'Şifrenizi giriniz.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            AuditLog::record(
                'customer_login',
                User::class,
                Auth::id(),
                "Müşteri giriş yaptı: {$request->user()->email}"
            );

            return redirect()->intended(route('customer.dashboard'))->with('success', 'Hoş geldiniz, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz e-posta veya şifre hatalı.',
        ])->onlyInput('email');
    }

    /**
     * Show customer registration form
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('pages.auth.register');
    }

    /**
     * Handle customer registration
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users,email',
            'phone' => 'nullable|string|max:25',
            'password' => 'required|string|min:6|confirmed',
            'kvkk_approval' => 'accepted',
        ], [
            'name.required' => 'Ad ve soyadınızı giriniz.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.unique' => 'Bu e-posta adresi ile zaten kayıtlı bir hesap bulunmaktadır.',
            'password.required' => 'Şifre belirleyiniz.',
            'password.min' => 'Şifreniz en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifreleriniz birbiriyle uyuşmuyor.',
            'kvkk_approval.accepted' => 'Devam etmek için KVKK Aydınlatma Metnini onaylamanız gerekmektedir.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);

        AuditLog::record(
            'customer_registered',
            User::class,
            $user->id,
            "Yeni müşteri hesabı oluşturuldu: {$user->email}"
        );

        return redirect()->route('customer.dashboard')->with('success', 'Hesabınız başarıyla oluşturuldu. Keyifli alışverişler!');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm(): View
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'E-posta adresinizi giriniz.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('pages.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Handle reset password submission
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Yeni şifrenizi giriniz.',
            'password.min' => 'Şifreniz en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler uyuşmuyor.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('success', 'Şifreniz başarıyla sıfırlandı. Yeni şifrenizle giriş yapabilirsiniz.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Logout customer
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Güvenli bir şekilde çıkış yaptınız.');
    }
}
