<?php

namespace App\Notifications;

use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketNotify extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user_id;
    public $user_ticket_id;
    public $user_name;
    public $product_id;
    public $product_name;
    public $controller;
    public function __construct($user_id , $user_name , $user_ticket_id , $product_id , $product_name)
    {
        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->user_ticket_id = $user_ticket_id;
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
            'type' => 'MyPosts',
            'message' => "The user ".$this->user_name . " purchase tickets from product ".$this->product_name ,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name ,
            //'image' =>  $this->controller->fullPath("/notification/ticket.png")
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
