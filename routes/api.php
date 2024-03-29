<?php

use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->name('api.')->group(function () {
    Route::middleware('throttle:' . config('api.rate_limits.sign'))
        ->group(function () {
            // 短信验证码
            Route::post('smsCodes', 'SmsCodesController@store')->name('smsCodes.store');
            // 用户登录/注册
            Route::post('users', 'UsersController@store')->name('users.store');
            // 刷新token
            Route::post('users/token/update', 'UsersController@updateToken')->name('users.updateToken');
            // 删除token
            Route::post('users/token/destroy', 'UsersController@destroyToken')->name('users.destroyToken');
        });

    Route::middleware('throttle:' . config('api.rate_limits.access'))
        ->group(function () {
            // 游客可以访问的接口
            // 某个用户的详情
            Route::get('users/{user}', 'UsersController@show')->name('users.show');
            // 支付宝支付服务端回调
            Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
            // 微信支付服务端回调
            Route::post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');
            // 微信支付退款回调
            Route::post('payment/wechat/refund_notify', 'PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');

            // 登录后可以访问的接口
            Route::middleware('auth:api')->group(function () {
                // 上传图片
                Route::post('images', 'ImagesController@store')->name('images.store');
                // 当前登录用户信息
                Route::get('user', 'UsersController@me')->name('user.show');
                // 编辑登录用户信息
                Route::post('user/update', 'UsersController@update')->name('user.update');
                // 当前用户的收获地址列表
                Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
                // 收藏商品
                Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
                // 取消收藏商品
                Route::post('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
                // 收藏商品列表
                Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
                // 购物车列表，添加商品到购物车
                Route::resource('cart', 'CartController')->only(['index', 'store']);
                // 从购物车中移除商品
                Route::post('cart/{sku}/destory', 'CartController@destroy')->name('cart.destroy');
                // 创建订单
                Route::resource('orders', 'OrdersController')->only(['index', 'store', 'show']);
                // 唤起微信支付
                Route::get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');
                // 唤起支付宝支付
                Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
                // 支付宝支付前端回调
                Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
                // 确认收货
                Route::post('orders/{order}/received', 'OrdersController@received')->name('orders.received');
                // 评价详情
                Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');
                // 进行评价
                Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');
                // 申请退款
                Route::post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');
            });

            // 商品列表，详情
            Route::resource('products', 'ProductsController')->only(['index', 'show']);
        });
});
