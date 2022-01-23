<?php
namespace App\DTO;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Product
{
    public $code;
    public $brand;
    public $fullProductName;
    public $linkBrand;
    public $productType;
    public $category;
    public $collection;
    public $barcode;
    public $madeIn;
    public $purchaseCountry;
    public $purchasePrice;
    public $rrp;
    public $reservedStock;

    /**
     * @return array<array>
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
            $productData = [
                "code" => $worksheet->getCell("E" . $i)->getValue(),
                "brand" => $worksheet->getCell("F" . $i)->getValue(),
                "type" => $worksheet->getCell("I" . $i)->getValue(),
                "width" => $worksheet->getCell("Y" . $i)->getValue(),
                "height" => $worksheet->getCell("Z" . $i)->getValue(),
                "length" => $worksheet->getCell("X" . $i)->getValue(),
                "color" => explode("\n", $worksheet->getCell('AL' . $i)->getValue())[0],
                "collection" => $worksheet->getCell("K" . $i)->getValue(),
                "category" => $worksheet->getCell("J" . $i)->getValue(),
                "price" => $worksheet->getCell("Q" . $i)->getValue(),
                "images" => [],
            ];

            foreach ($imageUrlColumns as $column) {
                if ($worksheet->getCell($column . $i)->getValue() != "") {
                    $productData["images"][] = ["src" => $worksheet->getCell($column . $i)->getValue()];
                }
            }

            $sizeParts = [];
            foreach (["width", "height", "length"] as $column) {
                if ($productData[$column] != "") {
                    $sizeParts[] = $productData[$column];
                }
            }
            $sizeBaseName = implode('x', $sizeParts);
            $productData["sizeName"] = "";
            if ($sizeBaseName != "") $productData["sizeName"] = $sizeBaseName . " cm";

            $productsData[] = $productData;
        }

        return $productsData;
    }
}
