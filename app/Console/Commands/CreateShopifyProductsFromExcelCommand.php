<?php

namespace App\Console\Commands;

use App\Jobs\CreateProductOnShopifyJob;
use Illuminate\Console\Command;
use Shopify\Clients\Rest as ShopifyAPI;
use Google\Cloud\Translate\V2\TranslateClient;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

class CreateShopifyProductsFromExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:fromexcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Shopify Products from Excel File';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $spreadsheet = IOFactory::load(Storage::path('demo.xls'));
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        $this->line("Excel has " . $highestRow . " lines. It means it has " . ((int)$highestRow - 3) . " products.");

        $imageUrlColumns = ["AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BJ"];

        for ($i = 4; $i <= $highestRow; $i++) {
            $productToCreate = [
                "title" =>
                    $worksheet->getCell('F' . $i)->getValue() . " " .
                    $worksheet->getCell('I' . $i)->getValue() . " " .
                    $worksheet->getCell('J' . $i)->getValue() . " - " .
                    $worksheet->getCell('E' . $i)->getValue(),

                "body_html" => "<strong>Good " . $worksheet->getCell('I' . $i)->getValue() . " " . $worksheet->getCell('J' . $i)->getValue() . "!</strong>",

                "vendor" => $worksheet->getCell('F' . $i)->getValue(),

                "product_type" => $worksheet->getCell('I' . $i)->getValue(),

                "variants" => [
                    [
                        "sku" => $worksheet->getCell('E' . $i)->getValue(),
                        "price" => $worksheet->getCell('Q' . $i)->getValue(),
                    ]
                ],

                "images" => []
            ];

            foreach ($imageUrlColumns as $column) {
                if ($worksheet->getCell($column . $i)->getValue() != "") {
                    $productToCreate["images"][] = ["src" => $worksheet->getCell($column . $i)->getValue()];
                }
            }

            $this->line($productToCreate["title"] . " creating with " . count($productToCreate["images"]) . " images...");

            $dispatchedJob = CreateProductOnShopifyJob::dispatch($productToCreate);
        }

        $this->info("Create product job has been dipatched.");
        $this->line(" ");
    }
}
