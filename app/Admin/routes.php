<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UsersController::class);
    $router->resource('products', ProductsController::class);
    $router->resource('orders', OrdersController::class);
    // 订单发货
    $router->post('orders/{order}/ship', 'OrdersController@ship')->name('orders.ship');
    // 拒绝退款
    $router->post('orders/{order}/refund', 'OrdersController@handleRefund')->name('orders.handle_refund');
});
