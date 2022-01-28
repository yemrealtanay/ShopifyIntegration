<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Http\Client\ClientExceptionInterface;
use Shopify\Clients\Rest as ShopifyAPI;
use Shopify\Exception\UninitializedContextException;

class CreateProductOnShopifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $productData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productData)
    {
        $this->productData = $productData;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle(ShopifyAPI $shopify)
    {
        try {
            $shopify->post(
                "products",
                [
                    "product" => $this->productData
                ]
            );
        } catch (ClientExceptionInterface $e)  {
            return $e->getMessage();
        }
    }
}
