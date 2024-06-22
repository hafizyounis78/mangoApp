<?php

namespace App\Http\Controllers;

use App\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
        $this->data['menu'] = 'offers';
        $this->data['selected'] = 'offer';
        $this->data['location'] = "offer";
        $this->data['location_title'] ='العروض';
        $this->data['languages'] = getLanguages();
    }
    public function index()
    {
        $this->data['sub_menu'] = 'offer-display';
        $this->data['location_title'] = 'عروض المنتجات';
        //session()->pull('errors');
        return view('offers.index', $this->data);
    }
    public function OffersList(Request $request)
    {

        $offers=Offer::join('products','products.prd_id','=','offers.prd_id')
            ->join('product_translations','product_translations.prd_id','=','offers.prd_id')
            ->where('lng_id','=',2)
            ->where('offers.ofr_isDeleted','!=',1)
            
            ->orderBy('ofr_id', 'desc')
            ->get();
       // dd($offers);
        //return datatables()->of(Product::query())->toJson();
      /*  $cat_id = getRawLookupSelect('products', 'cat_id', category_trans_type());
        $builder = DB::table('products');

        $builder2 = clone $builder;

        $builder->select('products.prd_id', 'ptr_name', 'prd_image', 'prd_price', $cat_id)
            ->join('product_translations', 'product_translations.prd_id', '=', 'products.prd_id')
            ->where('product_translations.lng_id', '=', lang());*/
        return datatables($offers)
            ->addColumn('image', function ($prod) {// as foreach ($users as $user)
                return url('storage/product/img/' . $prod->prd_image);
            })
             ->editColumn('ofr_start', function ($model) {// as foreach ($users as $user)

                $dt = new \DateTime($model->ofr_start);

                return $dt->format('Y-m-d');
                 //->format('Y-m-d');

            })
            ->editColumn('ofr_end', function ($model) {// as foreach ($users as $user)

                $dt = new \DateTime($model->ofr_end);

                return $dt->format('Y-m-d');
                //->format('Y-m-d');

            })
            ->addColumn('action', function ($model) {
                $id = $model->ofr_id;
                $active = $model->ofr_isDeleted;

                $icon1= "
                <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='prd_id_hidden' value='$id'>
                          <a class='btn btn-success btn-sm' data-toggle='modal' data-target='#offersModal' data-id='$id'
                           onclick='setProdValue($model)' title='تعديل' > 
                           <i class='fa fa-edit '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>
                        
                         
                 </div>";
                 $icon2="<div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='prd_id_hidden' value='$id'>
                          <a class='btn red btn-sm'  onclick='delOffer($id)'  title='حذف'> 
                           <i class='fa fa-remove '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>
                        
                         
                 </div>";
                 if($active==0)
                 return $icon1.$icon2;
                 else
                     return $icon1;
            })
            ->rawColumns(['action'])
            ->toJson();

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer=Offer::find($id);
        $offer->ofr_isDeleted=1;
        $offer->save();
        return response()->json(['success' => true]);
    }

    public function updateOffer(Request $request)
    {

        $ofr_id= $request->ofr_id;
        $ofr_discount = $request->ofr_discount;
        $ofr_start = $request->from;
        $ofr_end = $request->to;
        $offers = Offer::find($ofr_id);
        $offers->ofr_discount =$ofr_discount;
        $offers->ofr_start =$ofr_start;
        $offers->ofr_end =$ofr_end;
        $offers->save();
         return response()->json(['success' => true]);

    }

}
