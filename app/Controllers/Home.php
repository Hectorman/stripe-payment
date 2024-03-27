<?php

namespace App\Controllers;

require_once(APPPATH.'ThirdParty/stripe/init.php');

class Home extends BaseController
{

  public function index(): string
  {
      return view('payment_form');
  }

  public function process_payment() {
    $stripe_secret_key = 'sk_test_vwo37epdDzvmtC8XXY5MCNss';
    \Stripe\Stripe::setApiKey($stripe_secret_key);

    // Get token from the payment form
    $request = \Config\Services::request();
    $token = $request->getPost('stripeToken');

    try {
        // Charge the customer
        $charge = \Stripe\Charge::create(array(
            'amount' => 100, // $1 in cents
            'currency' => 'usd',
            'description' => 'Payment for product ABC',
            'source' => $token,
        ));

        // Payment successful
        $data['message'] = "Payment successful!";
        return view('payment_success', $data);
    } catch (\Stripe\Exception\CardException $e) {
        // Payment failed
        $data['error'] = $e->getError()->message;
        return view('payment_error', $data);
    }
  }
}
