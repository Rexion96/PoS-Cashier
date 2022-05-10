<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //

    public function index()
    {
        $products = Product::get();

        return view('welcome', compact('products'));
    }

    public function checkout(Request $request)
    {
        $success_message = false;

        $products = Product::get();

        $payload = $request->all();

        DB::Transaction(function () use ($payload) {
            //Creating Order
            $order = new Order;
            $order->number_of_order_items = (int)$payload['number_of_items'];
            $order->total_without_tax = $payload['sub_total'];
            $order->tax = 6;
            $order->service_charge = null;
            $order->total_with_tax = $payload['total'];
            $order->total_amount_paid = $payload['total_amount_paid'];
            $order->change = $payload['checkout-change'];
            $order->payment_method = $payload['payment_method'];
            $order->status = Order::STATUS_SUCCESS;
            $order->save();

            $order->order_id = 'ORDER-'.sprintf('%05d', $order->id);
            $order->save();

            //Loop for every order items
            for ($i = 1; $i <= Product::get()->count(); $i++){

                $check_if_form_exist = $payload['product_P'.$i.'_quantity'] ?? null;

                //Skip iteration if quantity not found
                if($check_if_form_exist == null){
                    continue;
                }

                //Skip iteration if order quantity is 0
                if ($payload['product_P'.$i.'_quantity'] == 0) {
                    continue;
                }
                $product = Product::where('name', $payload['product_P'.$i.'_name'])->first();

                //Creating Order Item
                $order_item = new OrderItem;
                $order_item->order_id = $order->id;
                $order_item->product_id = $product->id;
                $order_item->quantity = $payload['product_P'.$i.'_quantity'];
                $order_item->cost = $payload['product_P'.$i.'_cost'];
                $order_item->save();
            }

            $success_message = true;
        });


        return redirect()->route('home')->with('message', 'You have successfully purchased your order!');
    }
}
