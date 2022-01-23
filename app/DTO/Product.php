<?php
namespace App\DTO;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Product
{
    /**
     * Code (or SKU) of product
     * @var string
     */
    public $code;

    /**
     * Vendor or brand of product
     * @var string
     */
    public $brand;

    /**
     * An URL to a detail of brand
     */
    public $linkBrand;

    /**
     * Full name of product
     * @var string
     */
    public $fullName;

    /**
     * Type of product (eg: Chair)
     * @var string
     */
    public $type;

    /**
     * Category of product
     * @var string
     */
    public $category;

    /**
     * Collection of product
     * @var string
     */
    public $collection;

    /**
     * Barcode of product
     * @var string
     */
    public $barcode;

    /**
     * Manifacturing country of product
     * @var string
     */
    public $madeIn;

    /**
     * Purchase country of product
     * @var string
     */
    public $purchaseCountry;

    /**
     * Price of product
     *
     * @var float
     */
    public $price;

    /**
     * Reserved stock
     * @var int
     */
    public $reservedStock;

    /**
     * Width of product
     * @var float
     */
    public $width;

    /**
     * Height of product
     * @var float
     */
    public $height;

    /**
     * Length of product
     * @var float
     */
    public $length;

    /**
     * Size name of product (eg: 12x10x5 cm)
     * @var string
     */
    public $sizeName;

    /**
     * Color of product
     * @var string
     */
    public $color;

    /**
     * Image URLs of product
     * @var array<string>
     */
    public $images = [];

    /**
     * @param Spreadsheet $spreadsheet
     * @return array<Product>
     */
    public static function createCollectionFromExcel(Spreadsheet $spreadsheet): array
    {
        //  excelden okuma işlemi
        //  her bir satır için new self;
        //  ilgili objenin içini Excel satırından okuduklarınla doldur
        //  tümünü bir collection içine koy
        //  return
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        $imageUrlColumns = ["AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BJ"];

        $productsData = [];

        for ($i = 4; $i <= $highestRow; $i++) {

            $product = new static;
            $product->code = $worksheet->getCell("E" . $i)->getValue();
            $product->brand = $worksheet->getCell("F" . $i)->getValue();
            $product->type = $worksheet->getCell("I" . $i)->getValue();
            $product->width = $worksheet->getCell("Y" . $i)->getValue();
            $product->height = $worksheet->getCell("Z" . $i)->getValue();
            $product->length = $worksheet->getCell("X" . $i)->getValue();
            $product->color = explode("\n", $worksheet->getCell('AL' . $i)->getValue())[0];
            $product->collection = $worksheet->getCell("K" . $i)->getValue();
            $product->category = $worksheet->getCell("J" . $i)->getValue();
            $product->price = $worksheet->getCell("Q" . $i)->getValue();


            foreach ($imageUrlColumns as $column) {
                if ($worksheet->getCell($column . $i)->getValue() != "") {
                    $product->images[] = ["src" => $worksheet->getCell($column . $i)->getValue()];
                }
            }

            $sizeParts = [];
            foreach (["width", "height", "length"] as $column) {
                if ($product->$column != "") {
                    $sizeParts[] = $product->$column;
                }
            }
            $sizeBaseName = implode('x', $sizeParts);
            $productData["sizeName"] = "";
            if ($sizeBaseName != "") $product->sizeName = $sizeBaseName . " cm";

            $productsData[] = $product;
        }

        return $productsData;
    }
}
