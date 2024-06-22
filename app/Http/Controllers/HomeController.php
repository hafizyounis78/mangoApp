<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderDetail;
use App\Winner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Product;
use App\Ticket;
use App\Category;
use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $data = array();

    public function __construct()
    {
        $this->middleware('role:admin');
        $this->middleware('auth');
        $this->data['menu'] = 'home';
        $this->data['location'] = '/';
        $this->data['location_title'] = '';
        $this->data['selected'] = '';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $this->data['sub_menu'] = '';
      $this->data['Lifetime_Sales'] = Order::whereDate('ord_createdAt', Carbon::today())
            ->whereNotIn('ord_status', [6])
        ->sum('ord_totalAfterTax');
        $this->data['total_cust'] = User::where('type', '=', 3)
            ->where('isActive', '=', 1)
            ->count('id');
         $this->data['Total_Orders'] = Order::whereDate('ord_createdAt', Carbon::today())
            ->whereNotIn('ord_status', [6])
            ->count('ord_id');
//        $this->data['Top_selling'] = OrderDetail::s('odt_quantity')->sum('odt_price')->groupBy('prd_id');
        $this->data['Top_selling'] = DB::table('order_details')
            ->select(DB::raw('order_details.*,(select ptr_name from product_translations where product_translations.prd_id=order_details.prd_id 
            and lng_id=' . lang() . ' ) as product_name,sum(odt_quantity)  AS total_quantity, sum(odt_price)  AS total_amount'))
            ->groupBy('prd_id')
            ->latest()
            ->take(10)->get();

        $this->data['lastTenCustomer'] = DB::table('users')
            ->select(DB::raw('users.*, (SELECT COUNT(ord_id) FROM orders o WHERE o.ord_customer=users.id ) AS total_order, 
            (SELECT sum(ord_totalAfterTax) FROM orders a WHERE a.ord_customer=users.id ) AS total_amount'))
            ->where('type', '=', 3)
            ->latest()
            ->take(10)->get();
        $this->data['last_orders'] = DB::table('orders')
            ->select(DB::raw('orders.*,(select name from users where users.id=orders.ord_customer) as name,
            (select trn_text from translations where trn_foreignKey=ord_status+200 and trn_type="lookup" and lng_id=' . lang() . ') as status_desc'))
            ->orderBy('ord_createdAt')
            ->latest()
            ->take(10)->get();
        $this->data['pending_orders'] = DB::table('orders')
            ->select(DB::raw('orders.*,(select name from users where users.id=orders.ord_customer) as name,
            (select trn_text from translations where trn_foreignKey=ord_status+200 and trn_type="lookup" and lng_id=' . lang() . ') as status_desc'))
            ->whereIn('ord_status', [1, 2])
            ->orderBy('ord_createdAt')
            ->latest()
            ->take(10)->get();
        $this->data['complete_orders'] = DB::table('orders')
            ->select(DB::raw('orders.*,(select name from users where users.id=orders.ord_customer) as name,
            (select trn_text from translations where trn_foreignKey=ord_status+200 and trn_type="lookup" and lng_id=' . lang() . ') as status_desc'))
            ->whereIn('ord_status', [4, 5])
             ->whereDate('ord_createdAt', Carbon::today())
            ->orderBy('ord_createdAt')
            ->latest()
            ->take(10)->get();
        $this->data['canceled_orders'] = DB::table('orders')
            ->select(DB::raw('orders.*,(select name from users where users.id=orders.ord_customer) as name,
            (select trn_text from translations where trn_foreignKey=ord_status+200 and trn_type="lookup" and lng_id=' . lang() . ') as status_desc'))
            ->whereIn('ord_status', [6])
             ->whereDate('ord_createdAt', Carbon::today())
            ->orderBy('ord_createdAt')
            ->latest()
            ->take(10)->get();
        // dd( $this->data['pending_orders'] );
        $this->data['list_orders_by_Loc'] = DB::table('orders')
            ->join('addresses', 'addresses.user_id', '=', 'orders.ord_customer')
            ->select(DB::raw('count(ord_id) as total_orders,(select trn_text from translations where trn_foreignKey=addresses.adr_city 
            and trn_type="city" and lng_id=' . lang() . ') as city_name'))
             ->where('adr_isDefault','=',1)
              ->whereDate('ord_createdAt', Carbon::today())
            ->groupBy('city_name')
            ->get();
        $this->data['last_products'] = DB::table('products')
            ->select(DB::raw('products.*,(select ptr_name from product_translations where product_translations.prd_id=products.prd_id 
            and lng_id=' . lang() . ' ) as product_name'))
            ->latest()
            ->take(10)->get();
        $this->data['last_category'] = DB::table('categories')
            ->select(DB::raw('categories.*,(select trn_text from translations where trn_foreignKey=cat_id and trn_type="categories" and lng_id=' . lang() . ') as cat_name'))
            ->where('cat_isDeleted','=',-1)
            ->latest()
            ->take(10)->get();

//        $this->data['list_of_drivers'] = User::where('type', '=', 2)
//            ->where('isActive', '=', 1)->get();
        $this->data['list_of_drivers'] = DB::table('users')
            ->select(DB::raw('users.*, (SELECT COUNT(odv_id) FROM orders_deliveries o WHERE o.odv_driver=users.id ) AS total_order'))
            ->where('type', '=', 2)
            ->latest()
            ->take(10)->get();
       // dd($this->data['list_of_drivers']);
        // dd($this->data['list_orders_by_Loc']);
//        $totalRevenue = DB::table('partner_revenues')
//            ->select('lp_users.email', DB::raw('SUM(revenue) as total'))
//            ->join('lp_users', 'partner_revenues.user_id', '=', 'lp_users.id')
//            ->join('partners', 'partner_revenues.partner_id', '=', 'partners.id')
//            ->join('pocs', 'partner_revenues.poc_id', '=', 'pocs.id')
//            ->groupBy('partner_revenues.user_id')
//            ->paginate(15);

        return view('home', $this->data);
    }

 public function topSelling()
    {
        $top_selling = DB::table('order_details')
            ->select(DB::raw('order_details.*,(select ptr_name from product_translations where product_translations.prd_id=order_details.prd_id 
            and lng_id=' . lang() . ' ) as product_name,sum(odt_quantity)  AS total_quantity, sum(odt_price)  AS total_amount'))
            ->groupBy('prd_id')
            ->latest()
            ->take(10)->get();
        //dd($top_selling);
        return $top_selling;

}
    public function revenueold($yearRevenue)
    {
        /*
        $collection = Winner::leftJoin('products', 'winners.product_id', '=', 'products.id')
            ->leftJoin('tickets', 'tickets.product_id', '=', 'products.id')
            ->select('products.ticket_price as ticket_price', \Illuminate\Support\Facades\DB::raw('YEAR(winners.created_at) as year'), \Illuminate\Support\Facades\DB::raw('MONTH(winners.created_at) as month'), \Illuminate\Support\Facades\DB::raw('COUNT(tickets.id) as sold_tickets'))
            ->groupBy('winners.id')
            ->get();
*/

        $collection = Winner::leftJoin('products', 'winners.product_id', '=', 'products.id')
            ->leftJoin('tickets', 'tickets.product_id', '=', 'products.id')
            ->select('products.ticket_price as ticket_price', \Illuminate\Support\Facades\DB::raw('YEAR(winners.created_at) as year'), \Illuminate\Support\Facades\DB::raw('MONTH(winners.created_at) as month'), \Illuminate\Support\Facades\DB::raw('sum(tickets.count) as sold_tickets'))
            ->groupBy('winners.id')
            ->get();

        $arr = [];

        $a = $collection->map(function ($v, $k) {
            $data['total_price'] = $v->ticket_price * $v->sold_tickets;
            $data['month'] = $v->month;
            $data['year'] = $v->year;

            return $data;
        });
        $a = collect($a)->sortBy('month');

        $r = $a->where('year', '=', $yearRevenue)->groupBy('month', function ($row) {
            return $row;
        });

        $data = [];
        foreach ($r as $p) {
            $data['price'] = 0;
            foreach ($p as $t) {
                $data['price'] += $t['total_price'];
                $data['month'] = date("F", mktime(0, 0, 0, $t['month'], 10));
            }
            array_push($arr, $data);
        }

        return $arr;
    }
      public function revenue($year)
    {
        $revenue = DB::table('orders')
            ->join('order_details','order_details.ord_id','=','orders.ord_id')
            ->select(DB::raw('sum(order_details.odt_price)  AS total_amount,CONCAT(MONTH(orders.ord_createdAt),\'/\',YEAR(orders.ord_createdAt)) as month'))
            ->where('ord_status', '!=',6)
             ->whereYear('ord_createdAt', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('ord_createdAt')
            ->get();
       // dd($revenue);
        return $revenue;


    }
 public function orders($year)
    {
        $orders = DB::table('orders')

            ->select(DB::raw('count(orders.ord_id)  AS total_orders,CONCAT(MONTH(orders.ord_createdAt),\'/\',YEAR(orders.ord_createdAt)) as month'))
               ->where('ord_status', '!=', 6)
            ->whereYear('ord_createdAt', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('ord_createdAt')
            ->get();
       //  dd($orders );
        return $orders;


    }
public function ordersMap()
    {
       // dd('mapppppp');
        $orders_by_Loc= DB::table('orders')
            ->join('addresses', 'addresses.user_id', '=', 'orders.ord_customer')
            ->select('ord_id',DB::raw('addresses.lat,addresses.lng'))
            ->where('adr_isDefault','=',1)
             ->where('ord_status', '!=', 6)
            ->whereDate('ord_createdAt','<=' ,Carbon::today())
            ->get();
       //dd($orders_by_Loc);
        return $orders_by_Loc;

    }
    public function totalRevenue($yearRevenue)
    {
        $arr = $this->revenue($yearRevenue);
        $total = 0;
        foreach ($arr as $p) {
            $total += $p['price'];
        }
        return $total;
    }

    public function revenueYears()
    {
        $collection = Winner::leftJoin('products', 'winners.product_id', '=', 'products.id')
            ->leftJoin('tickets', 'tickets.product_id', '=', 'products.id')
            ->select('products.ticket_price as ticket_price', \Illuminate\Support\Facades\DB::raw('YEAR(winners.created_at) as year'), \Illuminate\Support\Facades\DB::raw('MONTH(winners.created_at) as month'), \Illuminate\Support\Facades\DB::raw('sum(tickets.count) as sold_tickets'))
            ->groupBy('winners.id')
            ->get();

        $arr = [];

        $a = $collection->map(function ($v, $k) {
            $data['total_price'] = $v->ticket_price * $v->sold_tickets;
            $data['month'] = $v->month;
            $data['year'] = $v->year;

            return $data;
        });
        $arr = $a->unique('year')->toArray();
        return $arr;
    }

    public function siteUserMonthly($year)
    {
        $users = User::select(DB::raw('count(id) as num_users'), DB::raw('Year(created_at) as year'), DB::raw('Month(created_at) as month'))
            ->groupBy('year')
            ->groupBy('month')
            ->get();

        $users = $users->filter(function ($value) use ($year) {
            return $value->year == $year;
        });

        $arr = $users->toArray();
        return array_values($arr);

    }

    public function siteUserYears()
    {
        $users = User::select(DB::raw(DB::raw('Year(created_at) as year')))
            ->groupBy('year')
            ->get()
            ->toArray();

        return $users;
    }
}
