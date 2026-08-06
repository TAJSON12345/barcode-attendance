<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (!isset($_GET['code'])) {
    die("No barcode supplied");
}

$code = trim($_GET['code']);

$generator = new BarcodeGeneratorPNG();

$image = $generator->getBarcode(
    $code,
    $generator::TYPE_CODE_128
);

header("Content-Type: image/png");
header("Content-Length: " . strlen($image));

echo $image;
exit;