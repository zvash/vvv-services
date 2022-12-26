<?php

namespace App\Http\Requests;

use App\Models\Referrer;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $pattern = '/^[\pL\pM\s-]+$/u';
        $referrersPhones = Referrer::query()
            ->where('left_refers', '>', 0)
            ->where('is_active', true)
            ->pluck('phone')
            ->all();
        if (
            request()->has('phone') &&
            request()->get('phone') &&
            request()->has('referrer_phone') &&
            in_array(request()->get('referrer_phone'), $referrersPhones)
        ) {
            $referrersPhones[] = request()->get('phone');
        }
        $referrersPhones = implode(',', $referrersPhones);
        return [
            'name' => 'required|filled|min:2|max:50|regex:' . $pattern,
            'phone' => [
                'required',
                'unique:users,phone',
                'regex:/^[0-9]{5,11}$/',
                'in:' . $referrersPhones,
            ],
            'password' => 'required|confirmed|min:6',
            'referrer_phone' => 'nullable|sometimes|in:' . $referrersPhones,
        ];
    }
}
