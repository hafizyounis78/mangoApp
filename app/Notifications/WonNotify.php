<?php

namespace App\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WonNotify extends Notification
{
    use Queueable;

    public $user_id;
    public $user_name;
    public $product_id;
    public $product_name;
    public $controller;
    public function __construct($user_id , $user_name , $product_id , $product_name)
    {
        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->controller = new Controller();
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {

       /* return [
            'type' => 'Won',
            'message' => "Your Won ".$this->product_name,
            'product_id' => $this->product_id
        ];
*/
        return [
            'type' => 'Won',
            'message' => "Your Win! ",
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name ,
            'image' => $this->controller->fullPath("/notification/winner.png")
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
