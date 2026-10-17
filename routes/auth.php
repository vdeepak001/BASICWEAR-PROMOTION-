<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Passkeys\Http\Controllers\PasskeyConfirmationController;
use Laravel\Passkeys\Http\Controllers\PasskeyLoginController;
use Laravel\Passkeys\Http\Controllers\PasskeyRegistrationController;

Route::middleware('guest')->group(function () {
    // Admin Login & Register Routes
    Route::prefix('admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter(['throttle:login']))
            ->name('login.store');

        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store'])
            ->name('register.store');
    });

    // Convenient Redirects from base paths
    Route::redirect('login', '/admin/login');
    Route::redirect('register', '/admin/register');

    // Password Reset
    if (Features::enabled(Features::resetPasswords())) {
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.update');
    }

    // Two-Factor Challenge
    if (Features::enabled(Features::twoFactorAuthentication())) {
        Route::get('two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
            ->name('two-factor.login');

        Route::post('two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter(['throttle:two-factor']))
            ->name('two-factor.login.store');
    }

    // Passkeys Login
    if (Features::enabled(Features::passkeys())) {
        Route::get('passkeys/login/options', [PasskeyLoginController::class, 'index'])
            ->name('passkey.login-options');

        Route::post('passkeys/login', [PasskeyLoginController::class, 'store'])
            ->name('passkey.login');
    }
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::post('admin/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('admin.logout');

    // Password Confirmation
    Route::get('user/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::get('user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
        ->name('password.confirmation');

    Route::post('user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->name('password.confirm.store');

    // Two-Factor Authentication Management
    if (Features::enabled(Features::twoFactorAuthentication())) {
        Route::post('user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->middleware('password.confirm')
            ->name('two-factor.enable');

        Route::post('user/confirmed-two-factor-authentication', [ConfirmedTwoFactorAuthenticationController::class, 'store'])
            ->middleware('password.confirm')
            ->name('two-factor.confirm');

        Route::delete('user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware('password.confirm')
            ->name('two-factor.disable');

        Route::get('user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
            ->middleware('password.confirm')
            ->name('two-factor.qr-code');

        Route::get('user/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
            ->middleware('password.confirm')
            ->name('two-factor.secret-key');

        Route::get('user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
            ->middleware('password.confirm')
            ->name('two-factor.recovery-codes');

        Route::post('user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
            ->middleware('password.confirm')
            ->name('two-factor.regenerate-recovery-codes');
    }

    // Passkeys Management
    if (Features::enabled(Features::passkeys())) {
        Route::get('passkeys/confirm/options', [PasskeyConfirmationController::class, 'index'])
            ->middleware('throttle:passkeys')
            ->name('passkey.confirm-options');

        Route::post('passkeys/confirm', [PasskeyConfirmationController::class, 'store'])
            ->middleware('throttle:passkeys')
            ->name('passkey.confirm');

        Route::get('user/passkeys/options', [PasskeyRegistrationController::class, 'index'])
            ->middleware(['password.confirm', 'throttle:passkeys'])
            ->name('passkey.registration-options');

        Route::post('user/passkeys', [PasskeyRegistrationController::class, 'store'])
            ->middleware(['password.confirm', 'throttle:passkeys'])
            ->name('passkey.store');

        Route::delete('user/passkeys/{passkey}', [PasskeyRegistrationController::class, 'destroy'])
            ->middleware('password.confirm')
            ->name('passkey.destroy');
    }
});
