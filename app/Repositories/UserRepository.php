<?php


namespace App\Repositories;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Referrer;
use App\Models\User;
use App\Traits\Passport\InteractsWithPassport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    use InteractsWithPassport;

    /**
     * @param array $inputs
     * @return array
     */
    public function register(array $inputs)
    {
        $record = collect($inputs)->only([
            'name',
            'email',
            'password',
            'phone',
            'referrer_phone',
        ])->toArray();
        $user = User::query()->create($record);
        if ($user->referrer_phone) {
            $referredBy = Referrer::query()->where('phone', $user->referrer_phone)->first();
            if ($referredBy) {
                $referredBy->setAttribute('left_refers', $referredBy->left_refers - 1)->save();
            }
        }
        $loginResponse = $this->logUserInWithoutPassword($user);
        return $loginResponse;
    }

}
