<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        // 只展示已支付的订单，并且默认按支付时间倒序排序
        $grid->model()->whereNotNull('paid_at')->orderBy('paid_at', 'desc');

        $grid->column('id', __('ID'));
        $grid->column('no', __('Order No'));
        $grid->column('user.name', __('Buyer'));
        $grid->column('total_amount', __('Total amount'))->sortable();
        $grid->column('paid_at', __('Paid at'))->sortable();
        $grid->column('ship_status', __('Ship status'))->display(function ($value) {
            return Order::$shipStatusMap[$value];
        });
        $grid->column('refund_status', __('Refund status'))->display(function ($value) {
            return Order::$refundStatusMap[$value];
        });
        // 禁用创建按钮，后台不需要创建订单
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 禁用删除和编辑按钮
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    public function show($id, Content $content)
    {
        return $content
        ->header('查看订单')
        // body 方法可以接受 Laravel 的视图作为参数
        ->body(view('admin.orders.show', ['order' => Order::find($id)]));
    }
}
