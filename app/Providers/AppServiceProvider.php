<?php

namespace App\Providers;

use App\Resolvers\SocialUserResolver;
use Hivokas\LaravelPassportSocialGrant\Resolvers\SocialUserResolverInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public $bindings = [
        SocialUserResolverInterface::class => SocialUserResolver::class,
    ];

    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Validator::extend('is_base64_image', function ($attribute, $value, $params, $validator) {
            if(empty($value)) return true;
            return $this->check_base64_image($value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }

    public function check_base64_image($base64) {

       return true;
       /* $file_name = public_path()."/testImage/tmp".time().".png";
        $director = public_path()."/testImage";
        if(!File::exists($director)) {
            File::makeDirectory($director , 777, true);
        }
        try {
            $img = base64_decode($base64);

            File::put($file_name,$img);
            $info = getimagesize($file_name);
            unlink($file_name);
            if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
                return true;
            }

            return false;
        }catch (\ErrorException $e) {
            unlink($file_name);
            return false;
        }

*/
    }
}
