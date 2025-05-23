<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)[^\s]{8,16}$/'
            ],
        ], [
            'password.required'  => 'パスワードは必須です。',
            'password.confirmed' => 'パスワード（確認）が一致しません。',
            'password.regex'     => 'パスワードは英字と数字を含めた8〜16文字で、空白を含めないでください。',
        ]);

        // ユーザーをメールアドレスで取得
        $user = User::where('email', $request->email)->first();

        // 既存のパスワードと同じ場合はエラーを返す
        if ($user && Hash::check($request->password, $user->password)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['password' => '前回のパスワードは再利用できません。']);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
}
