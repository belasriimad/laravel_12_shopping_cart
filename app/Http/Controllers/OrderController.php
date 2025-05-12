<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use ErrorException;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\InvalidRequestException;

class OrderController extends Controller
{
    private array $cart;

    public function __construct()
    {
        $this->cart = session()->get('cart', []);
        //provide the stripe key
        Stripe::setApiKey("YOUR STRIPE SECRET KEY HERE");
    }

    /**
     * Pay order by stripe
     */
    public function payOrderByStripe() 
    {
        //proceed to payment
        try {
            $checkout_session = Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Fashion Store'
                        ],
                        'unit_amount' => $this->calculateTotalToPay($this->cart),
                    ],
                    'quantity' => 1
                ]],
                'mode' => 'payment',
                'success_url' => route('order.success').'?session_id={CHECKOUT_SESSION_ID}'
            ]);
            return redirect($checkout_session->url);
        } catch (ErrorException $e) {
            Log::error('Stripe error: '.$e->getMessage());
            return back()->with('error', 'Something went wrong with the payment. Please try again.');
        }
    }
    
    /**
     * Calculate the total to pay
     */
    private function calculateTotalToPay(array $items) : float
    {
        $total = 0;
        foreach($items as $item)
        {
            $total += $item['qty'] * $item['price'];
        }
        return $total * 100;
    }

    /**
     * Redirect user to success page after payment
     */
    public function successPaid(Request $request)
    {
        $sessionId = $request->get('session_id');
        if($sessionId) {
            try {
                Session::retrieve($sessionId);
                session()->forget('cart');
                session()->forget('cartItemsTotal');
                return view('success-paid');
            } catch (InvalidRequestException $e) {
                return to_route('home');
            } 
        }else {
            return to_route('home');
        }
    }
}
