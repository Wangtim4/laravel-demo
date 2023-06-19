<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use  App\Http\Requests\UpdateCartItem;
// 使用model
use App\Models\Cart;
use App\Models\CartItem;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DB::table('cart_items')->get();
        return response($data);
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
        // 回傳訊息改中文
        $messages = [
            'required' => ':attribute 是必要的',
            'between' => ':attribute 的輸入不在 :min 和 :max 之間'
        ];
        // 資料驗證
        $validator = Validator::make($request->all(),[
            'cart_id' => 'required | integer', 
            'product_id' => 'required | integer', 
            'quantity' => 'required | integer | between:1,10', 
        ],$messages);

        // 驗證失敗，回傳
        if($validator->fails()) {
            return response($validator->errors(),400);
        };
        // 驗證通過，儲存，所以$validatedData = $form
        $validatedData = $validator->validate();
        


        $cart = Cart::find($validatedData['cart_id']);
        $result = $cart->cartItems()->create(['product_id' => $validatedData['product_id'],
        'quantity' => $validatedData['quantity']]);

        return response()->json($result);
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
    public function update(UpdateCartItem $request, $id)
    {
        // $form = $request->validated();
        // DB::table('cart_items')
        // ->where('id',$id)
        // ->update([
        //     'quantity' => $form['quantity'],
        //     'updated_at' => now(),
        // ]);
        $validatedData = $request->validated();
        $item = CartItem::find($id);
        $item->fill(['quantity' => $validatedData['quantity']]);
        // do something
        $item->save();

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = CartItem::find($id)->delete();
        return response()->json(true);
    }
}
