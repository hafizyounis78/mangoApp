<?php

namespace App\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CloseNotify extends Notification
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


    public function toDatabase($notifiable)
    {
        /*return [
            'type' => 'Close',
            'message' => "The product ".$this->product_name." is closed" ,
            'product_id' => $this->product_id
        ];*/

        return [
            'type' => 'Close',
            'message' => "Product canceled!" ,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name ,
            'image' =>  $this->controller->fullPath("/notification/close.png")
        ];
    }


    public function toArray($notifiable)
    {
        return [

        ];
    }
}
