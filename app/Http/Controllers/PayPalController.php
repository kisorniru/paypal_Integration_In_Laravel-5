<?php

namespace App\Http\Controllers;

use URL;
use Session;
use Redirect;
use Validator;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

// fOR PayPal
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

class PayPalController extends Controller
{
    private function paypal()
    {
        $paypal = new ApiContext( 
            new OAuthTokenCredential( 
                'ATpmXJPrY3MDTdVoBQDJm15jG6UP9WzJWHtxbSBE2LApROhHLN4UhGRahUCMUTOg8wu4jEtl6K7IEd_v', //client ID
                'EMIj5UG1pEbe3iycn7-UORNNVCV1HAAfbGeuLYN6WZ9TtaBko5I0muCBdtEidMUYk_lTLiXGHmtm3GQJ' //secrit ID
                ));
        return $paypal;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
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
        
        $paypal = $this->paypal();
        $product = $request->product;
        $quantiy = $request->quantiy;
        $singleProductPrice = $request->singleProductPrice;
        $price = $singleProductPrice * $quantiy;
        $shipping = 2.00;
        $total = $price + $shipping;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
          ->setCurrency('USD')
          ->setQuantity($quantiy)
          ->setPrice($singleProductPrice);

        $itemList = new ItemList();
        $itemList->setItems([$item]);
        // $itemList->setItems(array($item1, $item2));

        $details = new Details();
        $details->setShipping($shipping)
        ->setSubTotal($price);

        $amount = new Amount();
        $amount->setCurrency('USD')
        ->setTotal($total)
        ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription('PayForSomething Payment')
        ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(url('/', ['success' => 'true']))
        ->setCancelUrl(url('/', ['success' => 'false']));

        $payment = new Payment();
        $payment->setIntent('sale')
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions([$transaction]);

        try {
            $payment->create($paypal);
        } catch (Exception $e) {
            die($e);
        }

        $approvalUrl = $payment->getApprovalLink();
        return redirect()->to($approvalUrl);

    }

    public function paymentStatus($success)
    {
        $paypal = $this->paypal();
        $paymentId  = Input::get('paymentId');
        $payerID    = Input::get('PayerID');

        if (!isset($success, $paymentId, $payerID))
        {
            die();
        }
        
        if ((bool)$success === false) {
            die();
        }        

        // $payment = new Payment();
        // $payment->get($paymentId, $paypal);
        $payment = Payment::get($paymentId, $paypal);

        $execute = new PaymentExecution();
        $execute->setPayerId($payerID);

        try {
            $result = $payment->execute($execute, $paypal);
        } catch (Exception $e) {
            die($e);
        }
        if(!empty($result))
        {
            Session::flash('success', "Payment made. Thanks!");
            return redirect('/');
        }
        else
        {
            Session::flash('error', "Sorry, Could you please try again later.");
            return redirect('/');
        }

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
