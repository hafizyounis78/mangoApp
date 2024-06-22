<?php


Route::group(['middleware' => 'auth'], function () {


    Route::get('logout', 'UserController@logout');
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::patch('users/updateUserInfo/{id}', 'UserController@updateInfo')->name('updateUserInfo');
    Route::post('topSelling', 'HomeController@topSelling');
    Route::post('revenue/{Y}', 'HomeController@revenue');
    Route::post('orders/{Y}', 'HomeController@orders');
    Route::post('ordersMap', 'HomeController@ordersMap');
    Route::get('roles/newRole', 'RoleController@newRole')->name('roles.newRole');
    Route::get('roles/view', 'RoleController@view')->name('roles.view');
    Route::get('roles/editRole/{id}', 'RoleController@editRole')->name('roles.editRole');
    Route::patch('roles/updateRole/{id}', 'RoleController@updateRole')->name('roles.updateRole');

    Route::delete('roles/destroyRole/{id}', 'RoleController@destroyRole')->name('roles.destroyRole');

    Route::get('users/profile', 'UserController@profile')->name('users.profile');
    Route::post('users/changePassword', 'UserController@changePassword')->name('users.changePassword');

    Route::post('roles/storeRole', 'RoleController@storeRole')->name('roles.store_role');
    //Route::get('project/generate_project_number/{num}', 'ProjectController@generate_project_number');

    /*  Route::get('logout', function () {
          \Illuminate\Support\Facades\Auth::logout();
          return back();
      });*/


    /*   Mango   */

    /*  users */
    Route::resource('users', 'UserController');
    Route::post('user/contentListData', 'UserController@contentListData');
    Route::post('user/contentListData/{status}', 'UserController@contentListData');
    Route::get('activeUser', 'UserController@activeUser');
    Route::post('getUserInfo/{id}', 'UserController@getUserInfo');
    /*  Admin users */
    Route::resource('admin', 'AdminController');
    Route::post('admin/contentListData', 'AdminController@contentListData');
    Route::post('admin/contentListData/{status}', 'AdminController@contentListData');
    Route::get('activeAdmin', 'AdminController@activeUser');
    /*  Drivers users */
    Route::resource('drivers', 'DriverController');
    Route::post('driver/contentListData', 'DriverController@contentListData');
    Route::post('driver/contentListData/{status}', 'DriverController@contentListData');
    Route::get('activeDriver', 'DriverController@activeUser');

    /*      category   */
    Route::resource('category', 'CategoryController');
    Route::post('category/contentListData', 'CategoryController@contentListData');
    Route::post('category/contentListData/{state}', 'CategoryController@contentListData');
    Route::get('statusCategory', 'CategoryController@statusCategory');
    /*      subcategory   */
    Route::resource('subcategory', 'SubCategoryController');
    /* Route::post('category/contentListData', 'CategoryController@contentListData');
     Route::post('category/contentListData/{state}', 'CategoryController@contentListData');
     Route::get('statusCategory', 'CategoryController@statusCategory');*/

    /*    attribute      */
    Route::get('attribute', 'AttributeController@index');
    Route::post('addAttribute', 'AttributeController@addAttribute');
    Route::post('editAttribute', 'AttributeController@editAttribute');
    Route::get('getAttributeData', 'AttributeController@getAttributeData');
    Route::post('attribute/contentListData', 'AttributeController@contentListData');
    Route::post('delAttr', 'AttributeController@delAttr');
    /*           */

    /*     instructions     */
    Route::resource('instructions', 'InstructionController');
    Route::post('instructions/contentListData', 'InstructionController@contentListData');
    Route::get('statusInstruction', 'InstructionController@statusInstruction');


    /* Product     */

    Route::get('products', ['uses' => 'ProductController@index', 'as' => 'products']);
    Route::get('addProduct', ['uses' => 'ProductController@addProduct', 'as' => 'addProduct']);
    Route::get('editProduct/{prd_id}', ['uses' => 'ProductController@editProduct', 'as' => 'editProduct']);
    Route::get('updateProduct', ['uses' => 'ProductController@updateProduct']);
    Route::post('addOffer', ['uses' => 'ProductController@addOffer']);
    Route::post('product/saveGallery', ['uses' => 'ProductController@saveGallery', 'as' => 'saveGallery']);
    Route::post('product/deleteImg', ['uses' => 'ProductController@deleteImg', 'as' => 'deleteImg']);
    Route::post('delProduct', ['uses' => 'ProductController@delProduct']);
    //Route::post('saveProduct' , ['uses' => 'ProductController@saveProduct', 'as' => 'saveProduct']);
    //  Route::post('saveAttributes' , ['uses' => 'ProductController@saveAttributes', 'as' => 'saveAttributes']);
    Route::post('getProductsData', 'ProductController@productsList');
    Route::post('saveProduct2', ['uses' => 'ProductController@saveProduct2', 'as' => 'saveProduct2']);
    Route::post('saveAttributes', ['uses' => 'ProductController@saveAttributes', 'as' => 'saveAttributes']);
    Route::post('saveVariation', ['uses' => 'ProductController@saveVariation', 'as' => 'saveVariation']);
    Route::post('getAttValue', 'ProductController@getAttValue');
    Route::post('delAttribute', 'ProductController@delAttribute');
    Route::post('delVariation', 'ProductController@delVariation');
    Route::post('getProdInfo/{id}', 'ProductController@getProdInfo');


    /*    Offers      */

    Route::get('offers', ['uses' => 'OfferController@index', 'as' => 'offers']);
    Route::post('getOffersList', 'OfferController@OffersList');
    Route::post('destroy/{id}', 'OfferController@destroy');
    Route::post('updateOffer', ['uses' => 'OfferController@updateOffer']);
    /*    Coupon      */

    Route::get('coupons', ['uses' => 'CouponController@index', 'as' => 'coupons']);
    // Route::post('getCouponList' , 'CouponController@CouponList');
    Route::post('getCouponList/{no}', 'CouponController@CouponList');
    Route::post('saveCoupon', 'CouponController@saveCoupon');
    Route::post('delCoupon/{id}', 'CouponController@destroy');
    Route::post('addCoupons', 'CouponController@addCoupons');

    /*     unit               */
    Route::get('unit', 'UnitController@index');
    Route::post('unit/contentListData', 'UnitController@contentListData');
    Route::post('addUnit', 'UnitController@store');
    Route::post('delUnit/{id}', 'UnitController@destroy');
    /*     delivery setting               */
    Route::get('delivery', 'DevliveryScheduleController@index');
    Route::post('delivery/contentListData', 'DevliveryScheduleController@contentListData');
    Route::post('addDelivery', 'DevliveryScheduleController@store');
    Route::post('activeSchedule', 'DevliveryScheduleController@activeSchedule');

    /*     city               */
    Route::get('city', 'CityController@index');
    Route::post('addCity', 'CityController@store');
    Route::post('editCity', 'CityController@editCity');
    Route::get('getCityData', 'CityController@getCity');
    Route::post('city/contentListData', 'CityController@contentListData');
    Route::post('city/contentListData/{status}', 'CityController@contentListData');
    Route::get('statusCity', 'CityController@statusCity');

    /* Route::get('city2' , function() {
         return getAllCity();
     });*/
    /*                         */


    /*          order              */
    Route::get('orders', 'OrderController@index');
    Route::post('order/contentListData', 'OrderController@contentListData');
    Route::post('order/contentListData/{status}', 'OrderController@contentListData');
    Route::get('getDriver', 'OrderController@getDriver');
    Route::get('assignDriverToOrder', 'OrderController@assignDriverToOrder');
    Route::get('details', 'OrderController@details');
    Route::get('orderDetails', 'OrderController@orderDetails');
    /*                        */

    /*          notifications              */
    Route::get('notifications', 'SystemNotificationsController@index');
    Route::post('notifications/contentListData', 'SystemNotificationsController@contentListData');
    Route::post('notifications/contentListData/{type}', 'SystemNotificationsController@contentListData');
    Route::post('seenNoti', 'SystemNotificationsController@seenNoti');
    Route::post('get_noti', 'SystemNotificationsController@get_noti');
    Route::post('notifications/send_fcm', 'SystemNotificationsController@sendMultipleFcm');

    /*       setting                 */
    Route::get('setting', 'AppSettingController@index');
    Route::post('setting/update/{id}', 'AppSettingController@update');
});

Route::get('showResetForm/{token}', 'UserController@showResetForm');
Route::post('passwordReset', 'UserController@reset');

/*
Route::get('test' , 'Controller@getAllCategories');
Route::get('test2' , function() {
   return getAllInstructions("all");
});*/


Auth::routes();
