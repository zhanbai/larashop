<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddCartRequest;
use App\Models\ProductSku;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    // 利用 Laravel 的自动解析功能注入 CartService 类
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cartItems = $this->cartService->index();
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();

        return ['cartItems' => $cartItems, 'addresses' => $addresses];
    }

    public function store(AddCartRequest $request)
    {
        $this->cartService->store($request->input('sku_id'), $request->input('amount'));

        return null;
    }

    public function destroy(ProductSku $sku, Request $request)
    {
        $this->cartService->destroy($sku->id);

        return null;
    }
}
