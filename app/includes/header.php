<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Online Barcode Attendance System</title>

<!-- Favicon -->
<?php
$assetPath = (basename(dirname($_SERVER['PHP_SELF'])) == "app")
    ? "assets/"
    : "../assets/";
?>

<link rel="icon" type="image/png" href="<?= $assetPath ?>images/favicon.png">

<link rel="stylesheet" href="<?= $assetPath ?>css/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>