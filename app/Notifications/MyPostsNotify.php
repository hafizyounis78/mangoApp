<?php

namespace App\Notifications;

use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use App\Winner;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MyPostsNotify extends Notification
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
    public $controller;
    public $state;
    public function __construct($user_id , $user_name , $product_id , $product_name, $state)
    {

        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->controller = new Controller();
        $this->state = $state;
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

        if($this->state == "sold") {
            //$user = User::find(Winner::where('product_id' ,$this->product_id )->first()->user_id);
          /*  $user = User::find(Winner::where('product_id' ,$this->product_id )->first()->user_id);
            $image = $this->controller->fullPath($user->image);
          */
            $product = Product::find($this->product_id);
            $image = $this->controller->fullPath($product->image);
        }else if($this->state == "closed") {
         //   $user = User::find(Product::find($this->product_id)->user_id);
            $product = Product::find($this->product_id);
            $image = $this->controller->fullPath($product->image);
        }else {
            $image = $this->controller->fullPath("/notification/product.png");
        }

        return [
            'type' => 'MyPosts',
            'message' => "Your product ".$this->product_name ." is ".$this->state ,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name ,
            'image' => $image
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
