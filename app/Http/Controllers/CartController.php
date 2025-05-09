<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private array $cart;

    public function __construct()
    {
        $this->cart = session()->get('cart', []);
    }

    /**
     * Get the cart items from the session
     */
    public function index() 
    {
        $cart = $this->cart;
        //get cart items from the session
        return view('cart',compact('cart'));
    }

    /**
     * Add items to the cart
     */
    public function addToCart(Request $request)
    {
        //find and get the product by id
        $product = Product::findOrFail($request->product_id);
        //check if the product is already in the cart
        if(isset($this->cart[$product->id])) {
            return back()->with('info', 'Product already added to your cart');
        }else {
            $this->cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'image' => $product->image
            ];

            session()->put('cart', $this->cart);
            $this->calculateCartItemsTotal();

            return back()->with('success', 'Product added to your cart');
        }
    }

    /**
     * Update item inside the cart
     */
    public function updateCartItem(Request $request)
    {
        //find and get the product by id
        $product = Product::findOrFail($request->product_id);
        //get the new qty
        $qty = $request->qty;
        //check if the product is already in the cart
        if(isset($this->cart[$product->id])) {
            $this->cart[$product->id]['qty'] = $qty;
            //set the new cart session
            session()->put('cart', $this->cart);
            $this->calculateCartItemsTotal();
        }
        return back()->with('success', 'Cart updated successfully');
    }

    /**
     * Remove item from the cart
     */
    public function removeCartItem(Request $request)
    {
        //find and get the product by id
        $product = Product::findOrFail($request->product_id);
        //check if the product is already in the cart
        if(isset($this->cart[$product->id])) {
            unset($this->cart[$product->id]);
            //set the new cart session
            session()->put('cart', $this->cart);
            $this->calculateCartItemsTotal();
        }

        return back()->with('success', 'Product removed successfully');
    }

    /**
     * Clear the cart
     */
    public function clearCart()
    {
        //remove cart from the session
        session()->forget('cart');
        session()->forget('cartItemsTotal');
        return back()->with('success', 'Cart cleared');
    }

    private function calculateCartItemsTotal()
    {
        $total = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
        session()->put('cartItemsTotal', $total);
    }
}