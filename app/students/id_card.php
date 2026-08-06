<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
require_once "../vendor/autoload.php";

include "../includes/header.php";
include "../includes/navbar.php";

if (!isset($_GET["id"])) {
    die("Student not found.");
}
if (!canManageStudents()) {

    die("Access Denied");

}
$id = $_GET["id"];

$sql = "
SELECT *
FROM students
WHERE id = :id
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ":id" => $id
]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

/*
|--------------------------------------------------------------------------
| Dates
|--------------------------------------------------------------------------
*/

$issueDate = date("d M Y");
$expiryDate = date("d M Y", strtotime("+4 years"));

/*
|--------------------------------------------------------------------------
| Student Photo
|--------------------------------------------------------------------------
*/

$photo = "../uploads/students/" . $student["photo"];

if (!empty($student["photo"]) && file_exists($photo)) {
    $studentPhoto = $photo;
} else {
    $studentPhoto = "https://via.placeholder.com/150x180?text=No+Photo";
}

?>

<style>

body{
    background:#eef3f8;
    font-family:Arial, Helvetica, sans-serif;
}

.id-card{

    width:760px;
    height:480px;

    margin:30px auto;

    background:#fff;

    border-radius:18px;

    overflow:hidden;

    border:3px solid #0056b3;

    box-shadow:0 12px 30px rgba(0,0,0,.25);

    position:relative;

}

.watermark{

    position:absolute;

    width:260px;

    opacity:.05;

    top:110px;

    left:250px;

    z-index:0;

}

.id-header{

    height:90px;

    background:#0056b3;

    color:#fff;

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:15px 25px;

    position:relative;

    z-index:2;

}

.school{

    display:flex;

    align-items:center;

}

.school img{

    width:60px;

    height:60px;

    margin-right:15px;

}

.school h2{

    margin:0;

    font-size:28px;

    line-height:1.1;

}

.card-title{

    text-align:right;

}

.card-title h4{

    margin:0;

    font-size:24px;

}

.card-title p{

    margin:0;

    font-size:13px;

    letter-spacing:2px;

}

.id-body{

    display:flex;

    justify-content:space-between;

    padding:20px;

    position:relative;

    z-index:2;

}

.left-panel{

    width:180px;

    text-align:center;

}

.id-photo{

    width:150px;

    height:180px;

    border:3px solid #0056b3;

    border-radius:10px;

    object-fit:cover;

}

.student-name{

    margin-top:10px;

    font-size:20px;

    font-weight:bold;

    color:#003366;

    text-transform:uppercase;

}

.right-panel{

    flex:1;

    padding-left:30px;

}

.info-table{

    width:100%;

    border-collapse:collapse;

}

.info-table td{

    padding:9px 5px;

    border-bottom:1px dashed #ccc;

    font-size:16px;

}

.info-table td:first-child{

    font-weight:bold;

    color:#003366;

    width:180px;

}

.barcode{

    position:absolute;

    left:30px;

    right:30px;

    bottom:45px;

    text-align:center;

    z-index:2;

}

.barcode img{

    width:420px;

    height:70px;

}

.barcode-code{

    margin-top:8px;

    font-size:22px;

    font-weight:bold;

    color:#222;

}

.footer{

    position:absolute;

    bottom:0;

    width:100%;

    background:#0056b3;

    color:#fff;

    text-align:center;

    padding:8px;

    font-size:13px;

    z-index:2;

}

.print-btn{

    margin-top:30px;

}

@media(max-width:768px){

.id-card{

    width:100%;

    height:auto;

}

.id-body{

    flex-direction:column;

    align-items:center;

}

.right-panel{

    padding-left:0;

    margin-top:20px;

}

.info-table td{

    font-size:14px;

}

.barcode{

    position:static;

    margin-top:20px;

}

.barcode img{

    width:100%;

    height:60px;

}

.school h2{

    font-size:18px;

}

.card-title h4{

    font-size:18px;

}

}

@media print{

body{

    background:#fff;

}

.print-btn,

.navbar,

footer{

    display:none;

}

.id-card{

    margin:0;

    box-shadow:none;

    border:2px solid #000;

}

}
</style>

<div class="id-card">

    <!-- Watermark -->
    <img src="../assets/images/logo.png"
         class="watermark"
         alt="Watermark">

    <!-- Header -->
    <div class="id-header">

        <div class="school">

            <img src="../assets/images/logo.png" alt="School Logo">

            <div>
                <h2>FEDERAL POLYTECHNIC<br>OROGUN</h2>
            </div>

        </div>

        <div class="card-title">

            <h4>STUDENT</h4>
            <p>IDENTITY CARD</p>

        </div>

    </div>

    <!-- Body -->
    <div class="id-body">

        <!-- Left Side -->
        <div class="left-panel">

            <img
                src="<?= $studentPhoto ?>"
                class="id-photo"
                alt="Student Photo">

            <div class="student-name">

                <?= strtoupper(htmlspecialchars($student["fullname"])) ?>

            </div>

        </div>

        <!-- Right Side -->
        <div class="right-panel">

            <table class="info-table">

                <tr>

                    <td>Matric No.</td>

                    <td><?= htmlspecialchars($student["student_id"]) ?></td>

                </tr>

                <tr>

                    <td>Department</td>

                    <td><?= htmlspecialchars($student["department"]) ?></td>

                </tr>

                <tr>

                    <td>Level</td>

                    <td><?= htmlspecialchars($student["level"]) ?></td>

                </tr>

                <tr>

                    <td>Issue Date</td>

                    <td><?= $issueDate ?></td>

                </tr>

                <tr>

                    <td>Expiry Date</td>

                    <td><?= $expiryDate ?></td>

                </tr>

            </table>

        </div>

    </div>

    <!-- Barcode -->

    <div class="barcode">

        <img
            src="/barcodes/generate.php?code=<?= urlencode($student["barcode"]) ?>"
            alt="Barcode">

        <div class="barcode-code">

            <?= htmlspecialchars($student["barcode"]) ?>

        </div>

    </div>

    <!-- Footer -->

    <div class="footer">

        Federal Polytechnic Orogun &nbsp; | &nbsp;
        Barcode Attendance Management System

    </div>

</div>

<div class="text-center print-btn">

<div class="d-grid gap-2">

<button
class="btn btn-success btn-lg"
onclick="window.print()">

🖨 Print ID Card

</button>

<a
href="download_id.php?id=<?= $student['id'] ?>"
class="btn btn-danger btn-lg">

📥 Download PDF

</a>

<a
href="list.php"
class="btn btn-secondary">

⬅ Back to Students

</a>

</div>

</div>

<?php include "../includes/footer.php"; ?>