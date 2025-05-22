<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use ErrorException;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\StripeSession;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\InvalidRequestException;

class OrderController extends Controller
{
    private array $cart;

    public function __construct()
    {
        $this->cart = session()->get('cart',[]);
        //provide the stripe key
        Stripe::setApiKey("YOUR STRIPE SECRET KEY");
    }

    /**
     * Pay orders by stripe
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
                            'name' => 'Laravel 12 Shopping Cart'
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
    private function calculateTotalToPay(array $items)
    {
        $total = 0; 
        foreach($items as $item)
        {
            $total += $item['qty'] * $item['price'];
        }
        return $total * 100;
    }

    /**
     * Success page
     */
    public function successPaid(Request $request)
    {
        $sessionId = $request->get('session_id');
        //if no session id provided
        if(!$sessionId) {
            return to_route('home');
        }

        //check if stripe session id already stored to prevent reuse
        if(StripeSession::where('stripe_id', $sessionId)->exists()) {   
            return to_route('home')->with('error', 'This session id has already been used.');
        }

        try {
            Session::retrieve($sessionId);
            //store the session id to prevent reuse
            StripeSession::create([
                'stripe_id' => $sessionId,
            ]);
            //clear the cart
            session()->forget('cart');
            session()->forget('cartItemsTotal');
            return view('success-paid');
        } catch (InvalidRequestException $e) {
            return to_route('home');
        }
    }
}
