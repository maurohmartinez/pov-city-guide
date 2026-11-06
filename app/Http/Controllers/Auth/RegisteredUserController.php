<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\AddContactToMailingList;
use App\Models\User;
use Brevo\Exceptions\BrevoApiException;
use Brevo\Exceptions\BrevoException;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Log;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws BrevoApiException|BrevoException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        if ($request->input('agreed_to_receive_promotion_emails') === 'on') {
            AddContactToMailingList::dispatch($user->id);
        }

        Auth::guard('web')->login($user);

        return redirect()->route('profile');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
        ]);

//        $didEmailChange = $request->input('email') !== $request->user()->email;

        try {
            $request->user()->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return redirect()->route('verification.notice')->with('error', 'Failed to update profile');
        }

        return redirect()->route('profile')->with('success', __('common.profile_update'));
    }
}
