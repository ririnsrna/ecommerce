<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carts= Cart::where('user_id', Auth::user()->id)->get();
        return view('cart.index',compact('carts'));
    }
    public function store(Request $request)
    {  
         //validasi barang
        $duplicate = Cart::where('product_id', $request->product_id)->first();
        if($duplicate){
            return redirect('/cart')->with('gagal','Produk yang anda tambahkan suadh ada di keranjang');
        }
        //ketika barang berhasil ditambahkan
        Cart::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'qty' => 1
        ]);
        return redirect('/cart')->with('berhasil','Produk berhasil ditambahkan ke keranjang');
    }
    public function update(Request $request, $id)
    {
        Cart::where('id', $id)->update([
            'qty' => $request->quantity
        ]);

        // update menggunakan fitur rest API
        return response()->json([
            'success' => true
        ]);
    }
}
