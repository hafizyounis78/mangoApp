<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Use App\Project;


/*Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});*/
Route::post('login', 'Api\UserController@login');
Route::post('login_social', 'Api\UserController@login_social_medial');
Route::post('loginGoogle', 'Api\UserController@loginGoogle');
Route::post('refresh_token', 'Api\UserController@refresh_token');
Route::post('getDeliverySchedules', 'Api\OrderController@getDeliverySchedules');
//Route::middlewares(['langApi'])->group(function () {

Route::group(['middleware' => ['auth:api','langApi'], 'namespace' => 'Api'], function () {

    Route::post('/myProfile', 'UserController@show');
    Route::post('/logout', 'UserController@logout');

    //Route::post('forgetPasswordDriver', 'UserController@forgetPasswordDriver');
    Route::post('changeMobileDriver', 'UserController@changeMobileDriver');
    Route::post('/editProfile', 'UserController@updateInfo');

    Route::post('/changeImage', 'UserController@changeImage');

    Route::post('/addNewAddress', 'UserController@addNewAddress');
    Route::post('/getAddresses', 'UserController@getAddresses');
    Route::post('/deleteAddress', 'UserController@deleteAddress');
    Route::post('/setAsDefaultAddress', 'UserController@setAsDefaultAddress');
    Route::post('changePassword', 'UserController@changePassword');
    Route::post('refreshFcmToken', 'UserController@refreshFcmToken');

    Route::post('addToWishList', 'WishListController@addTOWishList');
    Route::post('deleteFromWishList', 'WishListController@deleteFromWishList');
    Route::post('wishList', 'WishListController@wishList');
    Route::post('getWishList', 'WishListController@getWishList');

    Route::post('addCartItem', 'ShoppingCartController@addCartItem');
    Route::post('getCartItems', 'ShoppingCartController@getCartItems');
    Route::post('editShoppCart', 'ShoppingCartController@editShoppCart');
    Route::post('getCartDetails', 'ShoppingCartController@getCartDetails');
    Route::post('deletecartItem', 'ShoppingCartController@deleteCartItem');
    Route::post('getCartCount', 'ShoppingCartController@getCartCount');
    Route::post('/editCard', 'UserController@updateCardInfo');
    Route::post('checkCounponValidity', 'CouponController@checkCounponValidity');
    /*      orders               */
    Route::post('addOrder', 'OrderController@addOrder');
    Route::post('addOrderRaw', 'OrderController@addOrderRaw');
    Route::post('updateOrderRaw', 'OrderController@updateOrderRaw');
    Route::post('updateOrder', 'OrderController@updateOrder');
    Route::post('orders', 'OrderController@orders');
    Route::post('startWithOrder', 'OrderController@startWithOrder');
    Route::post('getOrder', 'OrderController@getOrder');
    Route::post('getOrderDetails', 'OrderController@getOrderDetails');
    Route::post('orderEvaluation', 'OrderController@orderEvaluation');
    Route::post('getDriverOrder', 'OrderController@getDriverOrder');
    Route::post('getDriverOrderDetails', 'OrderController@getDriverOrderDetails');
    Route::post('getUserOrder', 'OrderController@getUserOrder');
    Route::post('cancelOrder', 'OrderController@cancelOrder');
    Route::post('endOrder', 'OrderController@endOrder');
    Route::post('getDrivers', 'OrderController@getDrivers');
    Route::post('sendDriverLocation', 'OrderController@sendDriverLocation');
    // Route::post('getDriversLocation' , 'OrderController@getDriversLocation');
    Route::post('getDriverLocation', 'OrderController@getDriverLocation');
    Route::post('updateOrderStatus', 'OrderController@updateOrderStatus');
    Route::post('getOrder2', 'OrderController@getOrder2');
    // cities
    Route::post('/cities-list', 'CityController@citiesList');
    //Notifications
    Route::post('getMyNotification', 'NotificationController@getMyNotification');
    Route::post('getbadge', 'NotificationController@getbadge');
    Route::post('seenNoti', 'NotificationController@seenNoti');
    //  Route::post('/verifiedCode', 'UserController@verifiedCode');

});
Route::middleware(['langApi'])->group(function () {

    /*          users           */
    // Route::post('/login', 'Api\UserController@login');
    // Route::post('loginSocial', 'Api\UserController@loginSocial');
    Route::post('/register', 'Api\UserController@register');
    Route::post('/verificationMobile', 'Api\UserController@verificationMobile');
    Route::post('/verifyMobile', 'Api\UserController@changeMobile');
    Route::post('/activateMobile', 'Api\UserController@activateMobile');
    Route::post('forgetPasswordDriver', 'Api\UserController@forgetPasswordDriver');
    Route::post('password/email', 'Api\UserController@getResetToken');
    Route::post('password/reset/{token}', 'Api\UserController@reset');
    Route::get('sellerProfile/{id}', 'Api\UserController@sellerProfile');
    /*               instructions      */
    Route::post('instructions', 'Api\InstructionController@instructions');
    /*               categories      */
    Route::post('categories', 'Api\CategoryController@categories');
    Route::post('categoriesWithSub', 'Api\CategoryController@categoriesWithSub');
    Route::post('subCategory', 'Api\CategoryController@subCategory');
    /*                 products          */
    Route::post('products-list', 'Api\ProductController@productsList');
    Route::post('offered-products-list', 'Api\ProductController@offeredProductsList');
    Route::post('products', 'Api\ProductController@products');
    Route::post('searchWithFilter', 'Api\ProductController@searchWithFilter');
    Route::post('productDetails', 'Api\ProductController@getProduct');
    Route::post('getProductByBarcode', 'Api\ProductController@getProductByBarcode');
    /*        Settings                 */
    Route::post('setting', 'Api\SettingController@setting'); //auth:
    Route::post('aboutUs', 'Api\SettingController@aboutUs');

});


