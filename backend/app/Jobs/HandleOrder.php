<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

// ShouldBeUnique
class HandleLinkedInExtract implements ShouldQueue
{
// , ShouldBeUniqueUntilProcessing
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
          
            $client = new \GuzzleHttp\Client([
                'base_uri' => env('BOT_HOST_IP'),
            ]);
            // request to bot
            $payload = ([
                'keep-alive' => in_array($order->status, ['PENDING', 'STARTED', 'RUNNING']),
                'card' => $card->toArray(),
                'order' => $order->toArray(),
                'sellPrice' => $sellPrice,
                'buyer' => $order->account->toArray(),
                'seller' => $sellerAccount->toArray(),
                'buyerToMarketSellPrice' => $card->price->ps_LCPrice - $cardPriceService->getIncrementalStep($card->price->ps_LCPrice, $sellPriceUnit),
            ]);
            $response = $client->post('/v1/order/take-order',
                ['json' => $payload]
            );
          
        } catch (\GuzzleHttp\Exception\RequestException$e) {
            Log::Info('fail');
            $errorRes = json_decode($e->getResponse()->getBody()->getContents());
            $data = $errorRes;
            $order->update(
                [
                    'status_message' => '-',
                    'status_label' => $data['buyerMissed'] ? 'Buyer Missed' :'Waiting for card to get bought',
                ]
            );
           
        } finally {
        //  
        }
        return $this;

    }

// /**
//  * Get the cache driver for the unique job lock.
//  *
//  * @return \Illuminate\Contracts\Cache\Repository
//  */
// public function uniqueVia()
// {
//     return Cache::driver('redis');
// }

    public function failed($e = null)
    {
        $this->release();
      
    }
}
