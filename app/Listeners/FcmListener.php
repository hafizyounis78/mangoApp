<?php

namespace App\Listeners;

use App\Events\FcmEvent;
use App\Http\Controllers\Api\NotificationController;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use GuzzleHttp;

class FcmListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function handle(FcmEvent $event)
    {
        $token = $event->token;
        $user = User::where('fcmToken' , $token)->first();
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('Raffle');
        $notificationBuilder->setBody($event->title)
            ->setSound('default')
            ->setBadge($user->unreadNotifications()->get()->count() + 1);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($event->data);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // $token = "fXaH6KvPAmo:APA91bEGiVwHbGzm-zrA-2W70BGSSz8OdL0v1VWvatCq9S8ad7eYWUObyZXNeTrlzL0o5wSF2yIfMyyamMZwMrPrmw43ipjsrPqTp1Vhq6OgXVLnUYFjMljOWuY9XcROmUpWm349eWRK";
        //  $token = "eT8am3SW5jI:APA91bHSdD_zPHZ8-7zIEK5yrc2EIlbFxkayNELWrUnj_S4CxXRbN-pQgxrdbmdS-ZZ7s-VK3TK1U2dZj5k7sfzFYToAajwKJOo-4kzQxj2gyRIS3egm4PNWz5-yR9cmcXHmduBSjp_r";
        //  $tokenArr = ['cjkzE3GzIxc:APA91bGnfSAjbkpV89TD85CKUOvo0IoM4_sIX6bUhqCTbovbpXoMuGcbS2_DXB7_ygYpu3DCIGYGDh_k7HQi8C9QNagtIxe4x4BdbVtw1bFTRhQ_ssVEhe0FeI5KORcDhnWHvKzn9-Cb', 'dG6eOy5m8_c:APA91bEXCMozBYDltvh7kRxWx61ifdKUEjh2oGEgBamEnme9BD2r8naeA2LhbxJJmN7ujaqGrHEGh2vz4vxX0SJVtUGbjQyXHfxfzd9gPgJWKPaGr6aEUomhNM2IKltveeSz_OIBr1UK'];
       // $token = "evNlu46D9JI:APA91bFDm31Ge4ebsE1gFv23sYAWE3zX-b29r4lRl0wHHoDMjZdaUMkh19TiBS1bTqUJ7hCqCzFTIQ-U-AsAM0G7qVWlD5IOxrsnwLqWVN9nRX2jvAv0gWqf6rkGyPIeKb1urKVgiWwL";


        if(!empty($token) || $token != null) {
            $user = User::where('fcmToken' , $token)->first();
            if($user->deviceType == 1) {
                FCM::sendTo($token, $option,null ,$data);
            }else {
                FCM::sendTo($token, $option, $notification, $data);
            }
        }


        /* $downstreamResponse = FCM::sendTo($token, $option ,$notification, $data);
         if($downstreamResponse->numberSuccess() >= 1) {
             return "done";
         }else {
             return "error";
         }*/

    }
}
