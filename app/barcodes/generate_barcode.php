<?php

require_once "../vendor/autoload.php";

use Picqer\Barcode\BarcodeGeneratorPNG;

if (!isset($_GET["code"])) {
    die("Barcode not specified.");
}

$barcode = $_GET["code"];

$generator = new BarcodeGeneratorPNG();

header("Content-Type: image/png");

echo $generator->getBarcode(
    $barcode,
    $generator::TYPE_CODE_128
);