<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/19/2019
 * Time: 7:47 PM
 */


namespace App\Resolvers;

use Hivokas\LaravelPassportSocialGrant\Resolvers\SocialUserResolverInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Socialite;
class SocialUserResolver implements SocialUserResolverInterface
{
    /**
     * Resolve user by provider credentials.
     *
     * @param string $provider
     * @param string $accessToken
     *
     * @return Authenticatable|null
     */

    public function resolveUserByProviderCredentials(string $provider, string $accessToken): ?Authenticatable
    {
        // Return the user that corresponds to provided credentials.
        // If the credentials are invalid, then return NULL.
        $providerUser = null;
        if($provider!='google')
        {
            try {
                $providerUser = Socialite::driver($provider)->userFromToken($accessToken);
            } catch (\Exception $exception) {
                return   $exception;
            }
        }
        if ($providerUser) {
            return (new SocialAccountsService())->findOrCreate($providerUser, $provider);
        }
        return null;

    }
}