<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
include "../includes/header.php";
include "../includes/navbar.php";
if (!canTakeAttendance()) {

    die("Access Denied");

}
$department = $_GET["department"] ?? "";
$course_id  = $_GET["course_id"] ?? "";

if($department=="" || $course_id==""){
    die("Please start attendance from the Take Attendance page.");
}

?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>Scan Student Barcode</h3>

<p class="mb-0">

Department:
<strong><?= htmlspecialchars($department) ?></strong>

</p>

</div>

<div class="card-body text-center">

<div id="reader" style="width:100%;max-width:700px;margin:auto;"></div>

<br>

<div id="result" class="alert alert-primary">

Waiting for barcode...

</div>

</div>

</div>

</div>

<!-- Load library FIRST -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>

function onScanSuccess(decodedText){

    document.getElementById("result").innerHTML =
    "<div class='alert alert-success'>Barcode detected... Saving attendance...</div>";

    html5QrcodeScanner.clear();

    window.location =
    "save.php?barcode="
    + encodeURIComponent(decodedText)
    + "&department=<?= urlencode($department) ?>"
    + "&course_id=<?= $course_id ?>";

}

function onScanFailure(error){

}

let html5QrcodeScanner =
new Html5QrcodeScanner(

"reader",

{

fps:10,

qrbox:250,

rememberLastUsedCamera:true

},

false

);

html5QrcodeScanner.render(

onScanSuccess,

onScanFailure

);

</script>

<?php include "../includes/footer.php"; ?>