<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\ManagementAccess\DetailUser;
use App\Models\ManagementAccess\RoleUser;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

use Illuminate\Support\Facades\DB;

use Auth;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            // 'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // after recoding
        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) {

                // add to detail users
                $detail_user = new DetailUser;
                $detail_user->user_id = $user->id;
                $detail_user->type_user_id = 3;
                $detail_user->contact = NULL;
                $detail_user->address = NULL;
                $detail_user->photo = NULL;
                $detail_user->gender = NULL;
                $detail_user->save();

                // add to role users - set role to patient
                $role_user = new RoleUser;
                $role_user->user_id = $user->id;
                $role_user->role_id = 5;
                $role_user->save();

            });
        });
    }
}
