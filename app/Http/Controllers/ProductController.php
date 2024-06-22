<?php

namespace App\Http\Controllers;

use App\Translation;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Datatables;
use Auth;
use DB;
use App\Product;
use App\ProductTranslation;
use App\Lookups;
use App\ProductAttribute;
use App\ProductVariation;
use App\SystemNotifications;
use App\Offer;
use App\User;
use Redirect;
use Carbon\Carbon;
use App\Attribute;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{

    protected $data;
    public $languages;

    public function __construct()
    {
        // $this->middleware('role:admin');
        $this->data['menu'] = 'products';
        $this->data['selected'] = 'products';
        $this->data['location'] = "products";
        $this->data['location_title'] = trans('products.products');
        //   $this->data['languages'] = getLanguages();
    }

    public function index()
    {

        $this->data['sub_menu'] = 'product-display';
        $this->data['location_title'] = trans('products.display_products');

        return view('products.index', $this->data);
    }

    public function productsList(Request $request)
    {

        $builder = Product::Join('product_translations', 'product_translations.prd_id', '=', 'products.prd_id')
            ->where('product_translations.lng_id', '=', lang())
            ->where('prd_isDeleted', '!=', 1)->get();

        return datatables($builder)
               ->addColumn('image', function ($prod) {
                   // as foreach ($users as $user)
                   return url('public/storage/product/img/' . $prod->prd_image);
               })
               ->addColumn('cat_name', function ($prd) {// as foreach ($users as $user)
                   return getTranslation($prd->cat_id , lang() , category_trans_type())->trn_text;
               })
               ->addColumn('action', function ($model) {
                   $id = $model->prd_id;

                   return "
                    <div class='col-xs-6' style='width: 20%!important;'>
                            <input type='hidden' class='prd_id_hidden' value='$id'>
                            <a href='" . url('updateProduct?prd_id=' . $id) . "' class='btn btn-primary btn-sm edit' title='تعديل' ><i class='fa fa-pencil'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>

                    </div>" ."<div class='col-xs-6' style='width: 20%!important;'>
                            <input type='hidden' class='prd_id_hidden' value='$id'>
                             <a class='btn btn-warning btn-sm' data-toggle='modal' data-target='#offersModal' data-id='$id'
                              onclick='setProdValue($id)' title='اضافة عرض على المنتج' >
                              <i class='fa fa-star '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>


                    </div></div> <div class='col-xs-6' style='padding-left: 3px!important;'>
                                   <a class='btn btn-danger btn-sm delete' onclick='delProduct($id)' title='حذف'><input type = 'hidden' class='id_hidden' value = '" . $id . "' > <i class='fa fa-remove' ></i ></a >
                               </div>";
               })
               ->rawColumns([ 'action'])
               ->toJson();
    }

    public function addProduct()
    {
        $this->data['sub_menu'] = 'product-display';
        $this->data['location_title'] = trans('products.adding_new');
        $lookups = $this->getLookup();
        $this->data['categories'] = getAllSubCategories("1");
        $this->data['attributes'] = $lookups['attributes'];
        $this->data['units'] = $this->getLookupUnits();
        return view('products.product_form', $this->data);
    }

    public function editProduct(Request $request, $prd_id)
    {
        $product = Product::find($prd_id);
        if (!($product instanceof Product)) {
            throw new \Exception(trans('products.product_not_found'));
        }

        $this->data['sub_menu'] = 'product-display';
        $this->data['location_title'] = trans('products.adding_new');
        $lookups = $this->getLookup();
        $this->data['categories'] = getAllSubCategories("1");
        $this->data['attributes'] = $lookups['attributes'];
        $this->data['product'] = $product;

        return view('products.product_form', $this->data);
    }

    public function updateProduct(Request $request)
    {
        $product = Product::find($request->prd_id);
        if (!($product instanceof Product)) {
            throw new \Exception(trans('products.product_not_found'));
        }
        //    $productAttribute = ProductAttribute::where('prd_id', $request->prd_id)->get();
        //  $productVariation = ProductVariation::where('prd_id', $request->prd_id)->get();

        $this->data['sub_menu'] = 'product-display';
        $this->data['location_title'] = trans('products.update');
        $lookups = $this->getLookup();
        $gallery = unserialize($product->prd_gallery);
        $this->data['gallery'] = $gallery;
        $this->data['categories'] = getAllSubCategories("all");
        $this->data['units'] = $this->getLookupUnits();
        $this->data['attributes'] = $lookups['attributes'];
        $this->data['product'] = $product;
        //  $this->data['productAttribute'] = $productAttribute;
        //    $this->data['productVariation'] = $productVariation;
        $this->data['attrTable'] = $this->getAttrTable($request->prd_id);
        $this->data['attrVar'] = $this->getAttrVar($request->prd_id);
        $this->data['varTextBox'] = $this->varTestBox($request->prd_id);
        // dd($this->data['attrVar']);
        return view('products.update', $this->data);

    }

    public function getAttrTable($prd_id)
    {
        $all[][] = '';
        $variations = ProductAttribute::where('prd_id', $prd_id)
            //  ->where('pat_isVariation', '=', 1)
            ->get();
        if (count($variations) == 0)
            return null;
        $i = 0;
        $j = 0;
        $value = '';
        foreach ($variations as $variation) {
            $value = '';
            //   dd($variation->pat_values);
            $value = unserialize($variation->pat_values);
            //$j++
            $all[$j]['id'] = $variation->pat_id;
            $all[$j]['value'] = '';
            $all[$j]['pat_isVariation'] = $variation->pat_isVariation;
            for ($k = 0; $k < count($value); $k++) {
                $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                //dd($all);
                if ($variation->pat_isVariation == 1) {
                    //$value=unserialize($variation->pat_values);
                    $obj[$i++] = getTranslation($value[$k], lang(), 'attribute_values');

                }
            }
            $j++;

        }
        return $all;
    }

    private function getLookupUnits()
    {
        /* $translations = Translation::where('trn_type','unit')->get();
         $lookups = eloquentToArray($translations, 'trn_foreignKey', 'trn_text', false);
         return $lookups;
 */
        $builder = DB::table('units as a')
            ->select(DB::raw('a.unit_id,(SELECT trn_text FROM translations WHERE trn_type="unit" and trn_foreignKey=a.unit_id and lng_id=2 ) as name_ar'))
            ->where('unit_isDeleted', '=', -1)
            ->get();
        $lookups = eloquentToArray($builder, 'unit_id', 'name_ar', false);
        return $lookups;
    }

    public function getAttrVar($prd_id)
    {
        $ProductVariation = ProductVariation::where('prd_id', $prd_id)
            //  ->where('pat_isVariation', '=', 1)
            ->get();
        //dd(count($ProductVariation));
        if (count($ProductVariation) == 0)
            return null;
        $i = 0;
        $j = 0;
        $all[][] = '';
        $value = '';
        foreach ($ProductVariation as $variation) {
            $value = unserialize($variation->pvr_attributesValues);
            $all[$j]['id'] = $variation->pvr_id;
            $all[$j]['pvr_price'] = $variation->pvr_price;
            $all[$j]['pvr_discount'] = $variation->pvr_discount;
            $all[$j]['pvr_isDefault'] = $variation->pvr_isDefault;
            $all[$j]['pvr_isDiscount'] = $variation->pvr_isDiscount;

            $all[$j]['value'] = '';
            for ($k = 0; $k < count($value); $k++) {
                $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
            }
            $j++;
        }
        return $all;
    }

    public function varTestBox($prd_id)
    {
        $obj[] = '';

        $variations = ProductAttribute::where('prd_id', $prd_id)
            //  ->where('pat_isVariation', '=', 1)
            ->get();
        //dd(count($ProductVariation));
        if (count($variations) == 0)
            return null;
        $i = 0;
        $j = 0;
        $value = '';
        foreach ($variations as $variation) {
            $value = '';
            $value = unserialize($variation->pat_values);

            for ($k = 0; $k < count($value); $k++) {
                if ($variation->pat_isVariation == 1) {
                    //$value=unserialize($variation->pat_values);
                    $obj[$i++] = getTranslation($value[$k], lang(), 'attribute_values');

                }
            }
            $j++;
        }
        return $obj;

    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), Product::validationRules());
        if ($validator->fails()) {
            Input::flash();
            return Redirect::back()->withErrors($validator, 'product')->withInput();
        }

        $success = false;
        if ($request->input('prd_id') > 0) {
            $product = Product::find($request->input('prd_id'));
            if ($product instanceof Product) {
                $success = $product->update($request->all());
                $prd_id = $request->input('prd_id');
            } else {
                throw new \Exception(trans('products.product_not_found'));
            }
        } else {
            $product = Product::create($request->all());
            if ($product instanceof Product) {
                $success = true;
                $prd_id = $product->prd_id;
            }
        }

        $translations = $request->input('translations', []);
        if ($success) {
            foreach ($translations as $lang => $trans) {
                $success = false;
                $data = [
                    'ptr_name' => $trans['ptr_name'],
                    'ptr_description' => $trans['ptr_description'],
                ];

                if ($trans['ptr_id'] > 0) {
                    $translation = ProductTranslation::find($trans['ptr_id']);
                    if ($translation instanceof ProductTranslation) {
                        $success = $translation->update($data);
                    }
                } else {
                    $data['prd_id'] = $prd_id;
                    $data['lng_id'] = $lang;
                    $translation = ProductTranslation::create($data);
                    $success = ($translation instanceof ProductTranslation);
                }

                if (!$success) {
                    break;
                }
            }
        }

        if ($success) {
            $message = array('success' => true, 'message' => trans('main.saved_successfully'));
        } else {
            $message = array('success' => false, 'message' => trans('main.server_error_occured'));
        }

        if ($success) {
            return redirect(route('editProduct', array($prd_id)));
        } else {
            if ($prd_id > 0) {
                return redirect(route('editProduct', array($prd_id)))->withInput();
            } else {
                return redirect(route('addProduct'))->withInput();
            }
        }
    }

    /***************************/

    public function saveGallery(Request $request)
    {
        if ($request->prd_id == '' || $request->prd_id == null)
            return response()->json(['error' => false]);

        // dd(Input::file('prd_gallery'));
        $product = Product::find($request->prd_id);
        //$galleryArr[]='';
        $galleryArr = array();
        $gallerys = unserialize($product->prd_gallery);
        // dd($gallerys);
        if (isset($gallerys) && $gallerys != null)
            for ($i = 0; $i < count($gallerys); $i++) {

                array_push($galleryArr, $gallerys[$i]);

            }


        if ($request->hasFile('prd_gallery')) {
            $image = Input::file('prd_gallery');

            $path = $this->storeImage3($image, '/product/img/', false);
        }
        //  dd(url('storage/product/img/' . $path)->getRealPath());
        array_push($galleryArr, $path);
        //$size = getimagesize(url('storage/product/img/' . $path));

        $imm = get_headers(url('public/storage/product/img/' . $path), 1);
        // $size=$imm[6]->Content-Length;
        $size = floatval($imm["Content-Length"]);
        //dd($size);

        $product->prd_gallery = serialize($galleryArr);
        $product->save();
        $files = [['url' => url('public/storage/product/img/' . $path),
            'thumbnailUrl' => url('public/storage/product/img/' . $path),
            'deleteUrl' => $path,
            'deleteType' => 'post',
            'name' => $image->getClientOriginalName(),
            // 'type'=> "image/jpeg",
            'size' => $size,
            'headers' => ['X-CSRF-TOKEN' => $request->_token],
            '_token' => $request->_token]
        ];

        return response()->json(['success' => true, 'files' => $files]);


    }

    public function deleteImg(Request $request)
    {
        // dd($request->all());
        $prd_id = $request->prd_id;
        $delimage = $request->image;
        $product = Product::find($prd_id);
        //dd($product);
        $newImage[] = '';
        $prd_galley = unserialize($product->prd_gallery);
        // dd($prd_galley);
        $i = 0;
        foreach ($prd_galley as $image) {
            if ($image == $delimage) {
                // echo $image;
                //return false;
            } else
                $newImage[$i++] = $image;

        }
        if ($newImage[0] != '')
            $product->prd_gallery = serialize($newImage);
        else
            $product->prd_gallery = null;
        $product->save();
        return response()->json(['success' => true, 'images' => $newImage]);

    }

    /*************************/
    public function saveProduct2(Request $request)
    {

        $path = null;
        if ($request->prd_id != null && $request->prd_id != '')
            $mode = 2;
        else
            $mode = 1;

        if ($mode == 1) {


            //  if ($request->has('translations')) {
            if ($request->hasFile('prd_image')) {
                $file = $request->file('image');//for thumb
                $extension = $file->getClientOriginalName();// image name

                $image = Input::file('prd_image');
                $path = $this->storeImage3($image, '/product/img/', false);
                $thumb = Image::make($file->getRealPath())->fit(250, 250)
                    ->save('public/storage/product/thumb/' . $extension);
            }
            $product = new Product();
            $product->prd_image = $path;
            $product->prd_thumbnail = $extension;
            $product->prd_price = $request->prd_price;
            $product->prd_isVariable = ($request->prd_isVariable == null ? -1 : $request->prd_isVariable);
            $product->cat_id = $request->cat_id;
            $product->prd_minQuantity = $request->prd_minQuantity;
            $product->prd_maxQuantity = $request->prd_maxQuantity;
            $product->prd_unit = ($request->prd_unit == null ? -1 : $request->prd_unit);
            $product->prd_unitValue = ($request->prd_unitValue == null ? 0 : $request->prd_unitValue);
            $product->prd_barcode = $request->prd_barcode;
            if ($product->save()) {
                for ($lang = 1; $lang <= 2; $lang++) {
                    if ($lang == 1) {
                        $ptr_name = $request->ptr_name_en;
                        $ptr_description = $request->ptr_description_en;
                    } else {
                        $ptr_name = $request->ptr_name_ar;
                        $ptr_description = $request->ptr_description_ar;
                    }
                    $prodctTrans = New ProductTranslation();
                    $prodctTrans->prd_id = $product->prd_id;
                    $prodctTrans->ptr_name = $ptr_name;
                    $prodctTrans->ptr_description = $ptr_description;
                    $prodctTrans->lng_id = $lang;
                    $prodctTrans->save();
                }

                return response()->json(array('success' => true, 'message' => trans('main.saved_successfully'), 'prd_id' => $product->prd_id));
            } else {

                return response()->json(array('success' => false, 'message' => trans('main.server_error_occured')));
            }
        } else {
            $prd_id = $request->prd_id;
            if ($request->hasFile('prd_image')) {
                $file = $request->file('image');//for thumb
                $extension = $file->getClientOriginalName();// image name

                $image = Input::file('prd_image');
                $path = $this->storeImage3($image, '/product/img/', false);
                $thumb = Image::make($file->getRealPath())->fit(250, 250)
                    ->save('public/storage/product/thumb/' . $extension);

            }
            $product = Product::find($prd_id);
            if ($path != '' && $path != null) {
                $product->prd_image = $path;
                $product->prd_thumbnail = $extension;

            }
            $product->prd_price = $request->prd_price;
            $product->prd_isVariable = ($request->prd_isVariable == null ? -1 : $request->prd_isVariable);
            $product->cat_id = $request->cat_id;
            $product->prd_minQuantity = $request->prd_minQuantity;
            $product->prd_maxQuantity = $request->prd_maxQuantity;
            $product->prd_unit = ($request->prd_unit == null ? -1 : $request->prd_unit);
            $product->prd_unitValue = ($request->prd_unitValue == null ? 0 : $request->prd_unitValue);
            $product->prd_barcode = $request->prd_barcode;
            if ($product->save()) {

                for ($lang = 1; $lang <= 2; $lang++) {
                    $ptr_name = '';
                    $ptr_description = '';

                    if ($lang == 1) {
                        $ptr_name = $request->ptr_name_en;
                        $ptr_description = $request->ptr_description_en;
                    } else {
                        $ptr_name = $request->ptr_name_ar;
                        $ptr_description = $request->ptr_description_ar;
                    }

                    $prodctTrans = ProductTranslation::where('prd_id', $prd_id)->where('lng_id', $lang)->first();

                    $prodctTrans->ptr_name = $ptr_name;
                    $prodctTrans->ptr_description = $ptr_description;
                    $prodctTrans->save();
                }

                return response()->json(array('success' => true, 'message' => trans('main.saved_successfully'), 'prd_id' => $product->prd_id));
            } else {
                //session()->flash('error');
                return response()->json(array('success' => false, 'message' => trans('main.server_error_occured')));
            }
        }

    }

    private function getLookupTable()
    {
        /* $lookups = Lookups::all();
         $lookups = eloquentToArray($lookups, 'lkp_id', 'lkp_unit', false);
         return $lookups;*/
        $translations = Translation::all();
        $lookups = eloquentToArray($translations, 'trn_foreignKey', 'trn_text', false);
        return $lookups;
    }

    public
    function getAttValue(Request $request)
    {
        $atr_id = $request->atr_id;
//         dd($atr_id);
        $attr_value = getAttributeValuesForAttr2($atr_id);

        // if($model->atr_id==117)
        //   dd( $attr_value);

        return response()->json(['success' => true, 'item' => $attr_value]);

    }

    public
    function saveAttributesold(Request $request)
    {

        $Attributes = ProductAttribute::where('prd_id', $request->prd_id)->get();
        foreach ($Attributes as $Attribute) {

            $Att = unserialize($Attribute->pat_values);

            $result = array_intersect($Att, $request->attributes_values);

            if (count($result) == count($request->attributes_values)) {

                $attributes_values = ProductAttribute::find($Attribute->pat_id);
                $attributes_values->pat_isVariation = ($request->attributes_variation == null ? -1 : $request->attributes_variation);
                $attributes_values->save();
                $obj[] = '';
                $all[][] = '';
                $variations = ProductAttribute::where('prd_id', $request->prd_id)
                    //  ->where('pat_isVariation', '=', 1)
                    ->get();

                $i = 0;
                $j = 0;
                $value = '';
                foreach ($variations as $variation) {
                    $value = '';
                    //   dd($variation->pat_values);
                    $value = unserialize($variation->pat_values);
                    //$j++
                    $all[$j]['id'] = $variation->pat_id;
                    $all[$j]['value'] = '';
                    $all[$j]['pat_isVariation'] = $variation->pat_isVariation;
                    for ($k = 0; $k < count($value); $k++) {
                        $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                        //dd($all);
                        if ($variation->pat_isVariation == 1) {
                            //$value=unserialize($variation->pat_values);
                            $obj[$i++] = getTranslation($value[$k], lang(), 'attribute_values');

                        }
                    }
                    $j++;


                    // dd($obj[0]);
                }


                return response()->json(['success' => true, 'attributes_values' => $obj, 'attributetable' => $all]);
            }

        }

        if ($request->has('atr_id') && $request->has('prd_id') && $request->has('attributes_values')) {
            $attributes_values = new ProductAttribute();
            $attributes_values->prd_id = $request->prd_id;
            $attributes_values->atr_id = $request->atr_id;
            $attributes_values->pat_isVariation = ($request->attributes_variation == null ? -1 : $request->attributes_variation);
            $attributes_values->pat_values = serialize($request->attributes_values);
            if ($attributes_values->save()) {
                $obj[] = '';
                $all[][] = '';
                $variations = ProductAttribute::where('prd_id', $request->prd_id)
                    //  ->where('pat_isVariation', '=', 1)
                    ->get();

                $i = 0;
                $j = 0;
                $value = '';
                foreach ($variations as $variation) {
                    $value = '';
                    //   dd($variation->pat_values);
                    $value = unserialize($variation->pat_values);
                    //$j++
                    $all[$j]['id'] = $variation->pat_id;
                    $all[$j]['value'] = '';
                    $all[$j]['pat_isVariation'] = $variation->pat_isVariation;
                    for ($k = 0; $k < count($value); $k++) {
                        $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                        //dd($all);
                        if ($variation->pat_isVariation == 1) {
                            //$value=unserialize($variation->pat_values);
                            $obj[$i++] = getTranslation($value[$k], lang(), 'attribute_values');

                        }
                    }
                    $j++;


                    // dd($obj[0]);
                }


                return response()->json(['success' => true, 'attributes_values' => $obj, 'attributetable' => $all]);
            }
        }
        return response()->json(['success' => false]);

    }

    function saveAttributes(Request $request)
    {

        $Attributes = ProductAttribute::where('prd_id', $request->prd_id)->get();
        foreach ($Attributes as $Attribute) {

            $Att = unserialize($Attribute->pat_values);
            if ($request->atr_id == $Attribute->atr_id) {

                $attributes_values = ProductAttribute::find($Attribute->pat_id);
                $attributes_values->pat_values = serialize($request->attributes_values);
                $attributes_values->pat_isVariation = ($request->attributes_variation == null ? -1 : $request->attributes_variation);
                $attributes = Attribute::find($request->atr_id);


                if ($attributes->atr_isSizeAttribute == 1)
                    $attributes_values->pat_isSizeAttribute = 1;
                else
                    $attributes_values->pat_isSizeAttribute = -1;
                // $attributes_values->pat_isSizeAttribute = ($request->pat_isSizeAttribute == null ? -1 : $request->pat_isSizeAttribute);
                $attributes_values->save();
                $obj[] = '';
                $all[][] = '';
                $variations = ProductAttribute::where('prd_id', $request->prd_id)
                    //  ->where('pat_isVariation', '=', 1)
                    ->get();

                $i = 0;
                $j = 0;
                $value = '';
                foreach ($variations as $variation) {
                    $value = '';
                    //   dd($variation->pat_values);
                    $value = unserialize($variation->pat_values);
                    //$j++
                    $all[$j]['id'] = $variation->pat_id;
                    $all[$j]['value'] = '';
                    $all[$j]['pat_isVariation'] = $variation->pat_isVariation;
                    for ($k = 0; $k < count($value); $k++) {
                        $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                        //dd($all);
                        if ($variation->pat_isVariation == 1) {
                            //$value=unserialize($variation->pat_values);
                            $obj[$i++] = getTranslation($value[$k], lang(), 'attribute_values');

                        }
                    }
                    $j++;

                }
                return response()->json(['success' => true, 'attributes_values' => $obj, 'attributetable' => $all]);

            } else {


                $result = array_intersect($Att, $request->attributes_values);

                if (count($result) == count($request->attributes_values)) {

                    $attributes_values = ProductAttribute::find($Attribute->pat_id);
                    $attributes_values->pat_isVariation = ($request->attributes_variation == null ? -1 : $request->attributes_variation);
                    $attributes = Attribute::find($request->atr_id);


                    if ($attributes->atr_isSizeAttribute == 1)
                        $attributes_values->pat_isSizeAttribute = 1;
                    else
                        $attributes_values->pat_isSizeAttribute = -1;
                    //$attributes_values->pat_isSizeAttribute = ($request->pat_isSizeAttribute == null ? -1 : $request->pat_isSizeAttribute);

                    $attributes_values->save();


                    $obj[] = '';
                    $all[][] = '';
                    $variations = ProductAttribute::where('prd_id', $request->prd_id)
                        //  ->where('pat_isVariation', '=', 1)
                        ->get();

                    $i = 0;
                    $j = 0;
                    $value = '';
                    foreach ($variations as $variation) {
                        $value = '';
                        //   dd($variation->pat_values);
                        $value = unserialize($variation->pat_values);
                        //$j++
                        $all[$j]['id'] = $variation->pat_id;
                        $all[$j]['value'] = '';
                        $all[$j]['pat_isVariation'] = $variation->pat_isVariation;
                        for ($k = 0; $k < count($value); $k++) {
                            $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                            //dd($all);
                            if ($variation->pat_isVariation == 1) {
                                //$value=unserialize($variation->pat_values);
                                $obj[$i++] = getTranslation($value[$k], lang(), 'attribute_values');

                            }
                        }
                        $j++;


                        // dd($obj[0]);
                    }


                    return response()->json(['success' => true, 'attributes_values' => $obj, 'attributetable' => $all]);
                }
            }
        }

        if ($request->has('atr_id') && $request->has('prd_id') && $request->has('attributes_values')) {

            $attributes_values = new ProductAttribute();
            $attributes_values->prd_id = $request->prd_id;
            $attributes_values->atr_id = $request->atr_id;
            $attributes_values->pat_isVariation = ($request->attributes_variation == null ? -1 : $request->attributes_variation);
            $attributes = Attribute::find($request->atr_id);


            if ($attributes->atr_isSizeAttribute == 1)
                $attributes_values->pat_isSizeAttribute = 1;
            else
                $attributes_values->pat_isSizeAttribute = -1;
            //$attributes_values->pat_isSizeAttribute = ($request->pat_isSizeAttribute == null ? -1 : $request->pat_isSizeAttribute);
            $attributes_values->pat_values = serialize($request->attributes_values);
            if ($attributes_values->save()) {
                $obj[] = '';
                $all[][] = '';
                $variations = ProductAttribute::where('prd_id', $request->prd_id)
                    //  ->where('pat_isVariation', '=', 1)
                    ->get();

                $i = 0;
                $j = 0;
                $value = '';
                foreach ($variations as $variation) {
                    $value = '';
                    //   dd($variation->pat_values);
                    $value = unserialize($variation->pat_values);
                    //$j++
                    $all[$j]['id'] = $variation->pat_id;
                    $all[$j]['value'] = '';
                    for ($k = 0; $k < count($value); $k++) {
                        $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                        //dd($all);
                        if ($variation->pat_isVariation == 1) {
                            //$value=unserialize($variation->pat_values);
                            $obj[$i++] = getTranslation($value[$k], lang(), 'attribute_values');

                        }
                    }
                    $j++;


                    // dd($obj[0]);
                }


                return response()->json(['success' => true, 'attributes_values' => $obj, 'attributetable' => $all]);
            }
        }
        return response()->json(['success' => false]);

    }

    public function saveVariation(Request $request)
    {

        $virations = ProductVariation::where('prd_id', $request->prd_id)->get();
        foreach ($virations as $viration) {
            //dd($virations->pvr_attributesValues);
            $cartAtt = unserialize($viration->pvr_attributesValues);
            // dd($cartAtt);
            $result = array_intersect($cartAtt, $request->attributes_variation);

            if (count($result) == count($cartAtt) && count($result) == count($request->attributes_variation)) {
                $prodVariation = ProductVariation::find($viration->pvr_id);
                $prodVariation->pvr_price = $request->pvr_price;
                $prodVariation->pvr_isDefault = ($request->pvr_isDefault == null ? -1 : $request->pvr_isDefault);
                //  $prodVariation->pvr_isDiscount = ($request->pvr_isDiscount == null ? -1 : $request->pvr_isDiscount);
                $prodVariation->pvr_discount = $request->pvr_discount;
                if ($request->pvr_discount > 0)
                    $prodVariation->pvr_isDiscount = 1;
                else
                    $prodVariation->pvr_isDiscount = 0;

                $prodVariation->save();
                $ProductVariation = ProductVariation::where('prd_id', $request->prd_id)
                    //  ->where('pat_isVariation', '=', 1)
                    ->get();

                $i = 0;
                $j = 0;
                $all[][] = '';
                $value = '';
                foreach ($ProductVariation as $variation) {
                    $value = unserialize($variation->pvr_attributesValues);
                    $all[$j]['id'] = $variation->pvr_id;
                    $all[$j]['pvr_price'] = $variation->pvr_price;
                    $all[$j]['pvr_discount'] = $variation->pvr_discount;
                    $all[$j]['pvr_isDefault'] = $variation->pvr_isDefault;
                    $all[$j]['pvr_isDiscount'] = $variation->pvr_isDiscount;

                    $all[$j]['value'] = '';
                    for ($k = 0; $k < count($value); $k++) {
                        $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                    }
                    $j++;
                }

                return response()->json(['success' => true, 'attributes_values' => $prodVariation, 'variationstable' => $all]);
            }

        }
        // dd($request->pvr_isDefault);
        if ($request->pvr_price == null && $request->pvr_price == '')
            $request->pvr_price = 0;
        if ($request->pvr_discount == null && $request->pvr_discount == '')
            $request->pvr_discount = 0;
        if ($request->has('prd_id') && $request->has('attributes_variation')) {
            $prodVariation = new ProductVariation();
            $prodVariation->prd_id = $request->prd_id;
            $prodVariation->pvr_price = $request->pvr_price;
            $prodVariation->pvr_isDefault = ($request->pvr_isDefault == null ? -1 : $request->pvr_isDefault);
            // $prodVariation->pvr_isDiscount = ($request->pvr_isDiscount == null ? -1 : $request->pvr_isDiscount);
            $prodVariation->pvr_discount = $request->pvr_discount;
            if ($request->pvr_discount > 0)
                $prodVariation->pvr_isDiscount = 1;
            else
                $prodVariation->pvr_isDiscount = 0;

            $prodVariation->pvr_attributesValues = serialize($request->attributes_variation);
            if ($prodVariation->save()) {
                //************//
                $ProductVariation = ProductVariation::where('prd_id', $request->prd_id)
                    //  ->where('pat_isVariation', '=', 1)
                    ->get();

                $i = 0;
                $j = 0;
                $all[][] = '';
                $value = '';
                foreach ($ProductVariation as $variation) {
                    $value = unserialize($variation->pvr_attributesValues);
                    $all[$j]['id'] = $variation->pvr_id;
                    $all[$j]['pvr_price'] = $variation->pvr_price;
                    $all[$j]['pvr_discount'] = $variation->pvr_discount;
                    $all[$j]['pvr_isDefault'] = $variation->pvr_isDefault;
                    $all[$j]['pvr_isDiscount'] = $variation->pvr_isDiscount;

                    $all[$j]['value'] = '';
                    for ($k = 0; $k < count($value); $k++) {
                        $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                    }
                    $j++;
                }

                //**********//
                return response()->json(['success' => true, 'attributes_values' => $prodVariation, 'variationstable' => $all]);
            }
        }
        return response()->json(['success' => false]);

    }

    public function delAttribute(Request $request)
    {
        $attibute = ProductAttribute::find($request->id);
        if ($attibute) {
            $attibute->delete();
            $ProductAttribute = ProductAttribute::where('prd_id', $request->prd_id)
                //  ->where('pat_isVariation', '=', 1)
                ->get();

            $i = 0;
            $j = 0;
            $all[][] = '';
            $value = '';
            foreach ($ProductAttribute as $variation) {
                $value = unserialize($variation->pat_values);
                $all[$j]['id'] = $variation->pat_id;
                $all[$j]['value'] = '';
                for ($k = 0; $k < count($value); $k++) {
                    $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                }
                $j++;
            }
            return response()->json(['success' => true, 'attributetable' => $all]);

        }


    }

    public function delVariation(Request $request)
    {
        $variation = ProductVariation::find($request->id);
        if ($variation) {
            $variation->delete();
            $ProductVariation = ProductVariation::where('prd_id', $request->prd_id)
                //  ->where('pat_isVariation', '=', 1)
                ->get();

            $i = 0;
            $j = 0;
            $all[][] = '';
            $value = '';
            foreach ($ProductVariation as $variation) {
                $value = unserialize($variation->pvr_attributesValues);
                $all[$j]['id'] = $variation->pvr_id;
                $all[$j]['pvr_price'] = $variation->pvr_price;
                $all[$j]['pvr_discount'] = $variation->pvr_discount;
                $all[$j]['pvr_isDefault'] = $variation->pvr_isDefault;
                $all[$j]['pvr_isDiscount'] = $variation->pvr_isDiscount;

                $all[$j]['value'] = '';
                for ($k = 0; $k < count($value); $k++) {
                    $all[$j]['value'] = $all[$j]['value'] . ' ' . getTranslation($value[$k], lang(), 'attribute_values')->trn_text;
                }
                $j++;
            }

            //**********//
            return response()->json(['success' => true, 'variationstable' => $all]);

        }


    }

    /**************************/
    private function getLookup()
    {
        $name = getRawLookupSelect('attributes', 'atr_id', attribute_trans_type(), 'name');
        $attributes = DB::table('attributes')->select('atr_id', $name)->where('atr_isDeleted', '<>', 1)->get();
        $attributes = eloquentToArray($attributes, 'atr_id', 'name', false);
        /*$attributes = [];
        foreach($attributesColl as $att){
            $attributes[] = ['id' => $att->atr_id, 'text' => $att->name];
        }*/

        $lookups = [
            'categories' => getLookupType('categories', 'cat_id', category_trans_type(), NULL, true, true),
            'attributes' => $attributes,
        ];

        return $lookups;
    }

    public function addOfferold(Request $request)
    {
        $ofr_discount = $request->ofr_discount;
        $prd_id = $request->prd_of_id;
        $ofr_start = $request->from;
        $ofr_end = $request->to;
        $offer = new Offer();
        $offer->ofr_discount = $ofr_discount;
        $offer->prd_id = $prd_id;
        $offer->ofr_start = $ofr_start;
        $offer->ofr_end = $ofr_end;
        $offer->save();

        $prd_trn = getProductTranslation($prd_id)->ptr_name;
        $not_title = 'عرض جديد';
        $not_ar = ' خصم بنسبة% ' . $ofr_discount . ' على منتج ' . $prd_trn;
        $notification = new SystemNotifications();
        $notification->not_title = $not_title;
        $notification->not_ar = $not_ar;
        $notification->ord_id = $prd_id;
        $notification->not_date = date('Y-m-d H:i:s');
        $notification->not_type = 3;
        //  $notification->save();

        $AndroidUsers = User::where('deviceType', '=', 1)->where('type', 3)->where('fcmToken', '!=', null)
            ->select('fcmToken')->get();

        foreach ($AndroidUsers as $AndroidUser) {
            $token = $AndroidUser->fcmToken;
            // dd($token);

            $this->sendGeneralFcm($not_title, $not_ar, $prd_id, $token, 3, 1);

        }

        $IoUsers = User::where('deviceType', '!=', 1)->where('type', 3)->where('fcmToken', '!=', null)
            ->select('fcmToken')->get();

        foreach ($IoUsers as $IoUser) {
            $token = $IoUser->fcmToken;

            $this->sendGeneralFcm($not_title, $not_ar, $prd_id, $token, 3, 2);

        }

        //   return Redirect::back()->with(['data', $notification]);

    }

    public function addOffer(Request $request)
    {
        $ofr_discount = $request->ofr_discount;
        $prd_id = $request->prd_of_id;
        $ofr_start = $request->from;
        $ofr_end = $request->to;
        $prd_offers = Offer::where('prd_id', '=', $prd_id)
            ->whereDate('ofr_start', '<=', Carbon::today())
            ->whereDate('ofr_end', '>=', Carbon::today())
            ->where('ofr_isDeleted', '!=', 1)->count();
        //dd($prd_offers);
        if ($prd_offers == 0) {
            $offersold = Offer::where('prd_id', '=', $prd_id)
                ->where('ofr_isDeleted', '!=', 1)->count();
            if ($offersold >= 1) {
                $offerToDeletes = Offer::where('ofr_isDeleted', '!=', 1)->get();
                foreach ($offerToDeletes as $offerToDelete) {
                    $offerToDelete->ofr_isDeleted = 1;
                    $offerToDelete->save();
                }
            }

            $offer = new Offer();
            $offer->ofr_discount = $ofr_discount;
            $offer->prd_id = $prd_id;
            $offer->ofr_start = $ofr_start;
            $offer->ofr_end = $ofr_end;
            $offer->save();

            $prd_trn = getProductTranslation($prd_id)->ptr_name;
            $not_title = 'عرض جديد';
            $not_ar = ' خصم بنسبة% ' . $ofr_discount . ' على منتج ' . $prd_trn;
            $notification = new SystemNotifications();
            $notification->not_title = $not_title;
            $notification->not_ar = $not_ar;
            $notification->ord_id = $prd_id;
            $notification->not_date = date('Y-m-d H:i:s');
            $notification->not_type = 3;
            $notification->expire_date = $ofr_end;
            $notification->save();

            $AndroidUsers = User::where('deviceType', '=', 1)->where('type', 3)->where('fcmToken', '!=', null)
                ->pluck('fcmToken')->toArray();

            /* foreach ($AndroidUsers as $AndroidUser)
             {*/
            //   $token= $AndroidUser->fcmToken;
            //  dd($AndroidUsers);
            if ($AndroidUsers != null)
                $this->sendGeneralFcm($not_title, $not_ar, $prd_id, $AndroidUsers, 3, 1);

            //}

            $IoUsers = User::where('deviceType', '!=', 1)->where('type', 3)->where('fcmToken', '!=', null)
                ->pluck('fcmToken')->toArray();

            // foreach ($IoUsers as $IoUser)
            // {
            // $token= $IoUser->fcmToken;
            // dd($IoUsers);
            if ($IoUsers != null)
                $this->sendGeneralFcm($not_title, $not_ar, $prd_id, $IoUsers, 3, 2);

            //  }
            return response()->json(['success' => true]);
            // return Redirect::back()->with(['data', $notification]);
        } else {
            $prd_trn = getProductTranslation($prd_id)->ptr_name;
            session()->flash('errors', 'يوجد عرض على المنتج  ' . $prd_trn . ' خلال هذه الفترة ');
            return Redirect::back()->with(['']);
        }


    }

    public function delProduct(Request $request)
    {
        // dd($request->id);
        $id = $request->id;
        $product = Product::find($id);
        if ($product) {
            $product->prd_isDeleted = 1;
            $product->save();
            $prdTranslations = ProductTranslation::where('prd_id', '=', $id)->get();
            if ($prdTranslations) {
                foreach ($prdTranslations as $prdTranslation) {
                    //dd($attribute_value->atv_id);
                    $prdTranslation->ptr_isDeleted = 1;
                    $prdTranslation->save();
                }
            }
            $prdAttributes = ProductAttribute::where('prd_id', '=', $id)->get();
            if ($prdAttributes) {
                foreach ($prdAttributes as $prdAttribute) {
                    //dd($attribute_value->atv_id);
                    $prdAttribute->pat_isDeleted = 1;
                    $prdAttribute->save();
                }
            }
            $prdVars = ProductVariation::where('prd_id', '=', $id)->get();
            if ($prdVars) {
                foreach ($prdVars as $prdVar) {
                    //dd($attribute_value->atv_id);
                    $prdVar->pvr_isDeleted = 1;
                    $prdVar->save();
                }
            }
            return response()->json(['status' => true]);
            //return Redirect::back()->withErrors(['status', 'success']);
        } else {
            return response()->json(['status' => false]);
        }


    }

    public function getProdInfo($prd_id)
    {
        $this->data2['product'] = DB::table('products')
            ->select(DB::raw('products.*, (SELECT ptr_name FROM product_translations  
            WHERE product_translations.prd_id=products.prd_id
             and lng_id=1) AS name_en,(SELECT ptr_name FROM product_translations  
            WHERE product_translations.prd_id=products.prd_id
             and lng_id=2) AS name_ar,(select trn_text from translations where trn_foreignKey=products.cat_id and trn_type="categories" 
             and lng_id=' . lang() . ') as cat_name'))
            ->where('prd_id', $prd_id)
            ->first();

        // $product = Product::find($prd_id);
        // $this->data['product'] = $product;

        //dd($this->data['product']);
        //  $this->data['attrTable'] = $this->getAttrTable($prd_id);
        // $this->data['attrVar'] = $this->getAttrVar($prd_id);
        return $this->data2;
    }
}