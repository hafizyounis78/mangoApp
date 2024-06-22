<?php

namespace App\Notifications;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FollowProductNotify extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */


    public $user_id;
    public $user_name;
    public $product_id;
    public $product_name;
    public $msg;
    public $controller;
    public function __construct($user_id,$user_name ,$product_id,$product_name ,$msg)
    {

        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->msg = $msg;
        $this->controller = new Controller();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
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
        return [
            'type' => 'follow_product',
            'message' => $this->msg,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name ,
            'image' =>  $this->controller->fullPath(Product::find($this->product_id)->image)
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
