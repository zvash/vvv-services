<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referrer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $referrersPhones = Referrer::query()
            ->where('is_active', true)
            ->where('left_refers', '>', 0)
            ->pluck('phone')
            ->all();
        if (
            $request->has('referrer_phone') &&
            $request->has('phone') &&
            in_array($request->get('referrer_phone'), $referrersPhones)
        ) {
            $referrersPhones[] = $request->get('phone');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}/',
                'max:255',
                'unique:users',
                'in:' . implode(',', $referrersPhones),
            ],
            //'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referrer_phone' => [
                'nullable',
                'sometimes',
                'string',
                'regex:/^09[0-9]{9}/',
                'max:255',
                'in:' . implode(',', $referrersPhones),
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
//            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        if (isset($request->referrer_phone)) {
            $user->referrer_phone = $request->referrer_phone;
            $user->save();
            $referrer = Referrer::query()->where('phone', $request->referrer_phone)->first();
            $referrer->setAttribute('left_refers', $referrer->left_refers - 1)->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
