<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/19/2019
 * Time: 8:04 PM
 */


namespace App\Resolvers;
use App\User;
use Laravel\Socialite\Two\User as ProviderUser;
class SocialAccountsService
{
    /**
     * Find or create user instance by provider user instance and provider name.
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     *
     * @return User
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider)
    {


        $facebookSocialAccount = \App\FacebookSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if (isset($facebookSocialAccount)) {
            return $facebookSocialAccount->user;
        } else {

            $user = null;
            $email = $providerUser->getEmail() ;
            $user = User::where('email', $email)->first();

            if (!isset($user)) {

                if($provider=='facebook')
                    $typeSocialMedia=1;
                else if($provider=='google')
                    $typeSocialMedia=2;
                else
                    $typeSocialMedia=0;

                $user = User::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'type'=>3,
                    'image'=>$providerUser->getAvatar(),
                    'typeSocialMedia'=>$typeSocialMedia,
                ]);
                $user->generateToken();

                $user->facebookSocialAccounts()->create([
                    'provider_id' => $providerUser->getId(),
                    'provider_name' => $provider,
                ]);
                //   return $user;

                $data = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'verficationCode' => $user->verficationCode,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                //  dd('$data :'.$data);

                return  $user;//$this->responseJson(true, 'success', $data);
            }
            else
                return $user;
        }
    }

}