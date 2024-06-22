<?php

namespace App\Console\Commands;

use App\Events\FcmEvent;
use App\Http\Controllers\Controller;
use App\Notifications\CloseNotify;
use App\Notifications\LostNotify;
use App\Notifications\OptionNotify;
use App\Notifications\WonNotify;
use App\Product;
use App\Ticket;
use App\User;
use App\Winner;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use URL;
use Session;
use Redirect;
use Input;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class RaffleJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RaffleJob:rafflejob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $controller;

    public function __construct()
    {
        parent::__construct();
        $this->controller = new Controller();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $products = $this->controller->getActiveProduct();
        foreach ($products as $prod) {
            $sell_rate = $this->controller->sellingRate($prod->id);

            if ($sell_rate == 100) {
                $this->controller->executeRaffle($prod->id);

            } else if ($this->controller->isExpireProduct($prod->id)) {

                if ($sell_rate <= 50) {
                    $this->controller->closeRaffle($prod->id);
                } else {

                    $product = Product::find($prod->id);
                    $product->option_state = 1;
                    $product->update();

                    $user = User::find($prod->user_id);
                    $msg = 'The product ' . $prod->name . " is expired and ticket rate is greater than 50%";
                    Notification::send($user, new OptionNotify(-1, "", $prod->id, $prod->name , $msg));
                    $token = $user->fcmToken;

                    $data = [
                        'type' => 'Option',
                        'message' => $msg,
                        'product_id' => $prod->id,
                        'product_name' => $prod->name,
                        'user_id' => -1,
                        'user_name' => ""
                    ];
                    event(new FcmEvent($token, $msg, $data));

                }

            } else if (!$this->controller->isExpireProduct($prod->id)) {

                if ($sell_rate >= 75) {
                    $product = Product::find($prod->id);
                    $product->option_state = 1;
                    $product->update();

                    $msg = "More than 75% of the product was purchased,you can execute or close product";
                    $user = User::find($prod->user_id);
                    Notification::send($user, new OptionNotify(-1, "", $prod->id, $prod->name , $msg));


                    $token = $user->fcmToken;
                    $data = [
                        'type' => 'Option',
                        'message' => $msg,
                        'product_id' => $prod->id,
                        'product_name' => $prod->name,
                        'user_id' => -1,
                        'user_name' => ""
                    ];
                    event(new FcmEvent($token, $msg, $data));
                }

            }

        }

        //   Notification::send(User::find(1) , new WonNotify(1 , "aaa"));


    }


}
