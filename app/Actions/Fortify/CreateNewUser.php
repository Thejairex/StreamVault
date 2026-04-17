<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password'    => $this->passwordRules(),
            'card_name'   => ['required', 'string', 'min:3'],
            'card_number' => ['required', 'string', 'size:19'],
            'card_expiry' => ['required', 'string', 'regex:/^\d{2}\/\d{2}$/'],
            'card_cvc'    => ['required', 'string', 'size:3'],
        ], [
            'card_number.size'  => __('Enter a valid 16-digit card number.'),
            'card_expiry.regex' => __('Use MM/YY format.'),
            'card_cvc.size'     => __('CVC must be 3 digits.'),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name'     => $input['name'],
                'email'    => $input['email'],
                'password' => $input['password'],
            ]);

            Subscription::create([
                'user_id'                  => $user->id,
                'provider'                 => config('streamvault.payment_provider', 'stripe'),
                'provider_subscription_id' => 'mock_' . uniqid(),
                'status'                   => 'active',
                'plan'                     => 'premium',
                'starts_at'                => now(),
                'ends_at'                  => now()->addMonth(),
            ]);

            return $user;
        });
    }
}
