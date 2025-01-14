<?php

namespace App\Actions\Fortify;

use App\Models\User; // Assuming this is your User model  
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Illuminate\Foundation\Auth\User as AuthenticatableUser; //Import the correct User type  


class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**  
     * Validate and update the given user's profile information.  
     *  
     * @param  array<string, string>  $input  
     */
    public function update(AuthenticatableUser $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            if ($user instanceof AuthenticatableUser) { //Explicit check for correct type  
                $this->updateVerifiedUser($user, $input);
            } else {
                // Handle the case where $user is not the expected type.  Log an error, throw an exception, or take other appropriate action.  
                throw new \InvalidArgumentException("User object is not of the expected type.");
            }
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**  
     * Update the given verified user's profile information.  
     *  
     * @param  array<string, string>  $input  
     */
    protected function updateVerifiedUser(AuthenticatableUser $user, array $input): void //Corrected type hint here  
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
