<?php

namespace Eventsaaspro\Services;

use Eventsaaspro\Models\Tax;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Transfer;

class StripeService
{
    /**
     * sourceKey
     *
     * @var mixed
     */
    protected $public, $secret;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->public = setting('apps.stripe_public_key');
        $this->secret = setting('apps.stripe_secret_key');
        $this->account_id = setting('apps.stripe_comic_book_fee_account_id');
    }

    /**
     * stripeCharge
     *
     * @param  mixed $order
     * @param  mixed $currency
     * @return void
     */
    public function stripeCharge($order = [], $currency = 'USD', $booking = [])
    {
        try {
            $tax = Tax::where('comic_book_fee', 1)->get();
            $totalPrice = $order['price'];
            $comicFee = 0;
            foreach ($booking as $bookingValue) {
                foreach ($tax as $key => $value) {
                    if ($value->rate_type == 'fixed') {
                        $comicFee += $value->rate;
                    } else {
                        $comicFee += $bookingValue['ticket_price'] * ($value->rate / 100);
                    }
                }
            }
            $payment_method = session('payment_method');
            Stripe::setApiKey($this->secret);

            $charge = Charge::create([
                'amount' => $totalPrice * 100,
                'currency' => $currency,
                'source' => $payment_method['source'],
                'description' => $order['product_title'] . ' - Order Number:' . $order['order_number'] . ' - Item SKU:' . $order['item_sku'],

            ]);

            // Transfer comic book fees
            if ($comicFee != 0) {
                $this->transferComicBookFee($comicFee, $currency, $charge);
            }

            if ($charge->status === 'succeeded') {
                $flag['transaction_id'] = $charge['id'];
                $flag['payer_reference'] = $charge['receipt_url'];
                $flag['message'] = "Payment charged Successfully";
                $flag['status'] = true;
            } else {
                $flag['message'] = $charge['message'];
                $flag['status'] = true;
            }
            return $flag;
        } catch (\Exception $e) {
            \Log::error('---------------------------------------------------------------------------');
            \Log::error('Payment Charge Failed. Error ' . $e->getMessage());
            \Log::error('---------------------------------------------------------------------------');
            $flag['message'] = $e->getMessage();
            $flag['status'] = false;
            return $flag;
        }

        return $flag;
    }

    /**
     * checkKeys
     *
     * @return void
     */
    public function transferComicBookFee($comicFee, $currency, $charge)
    {
        try {
            $transfer = Transfer::create([
                'amount' => $comicFee * 100,
                'currency' => $currency,
                'destination' => $this->account_id,
                'transfer_group' => $charge->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('---------------------------------------------------------------------------');
            \Log::error('Comic Book Fee Transfer Failed. Error ' . $e->getMessage());
            \Log::error('Stripe Charge Id ' . $charge->id);
            \Log::error('---------------------------------------------------------------------------');
            return true;
        }
    }
    public function checkKeys()
    {
        if (!empty(setting('apps.stripe_public_key')) && !empty(setting('apps.stripe_secret_key'))) {
            return true;
        }

        return false;
    }
}
