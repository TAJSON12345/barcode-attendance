<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

include "../includes/header.php";
include "../includes/navbar.php";
if (!canTakeAttendance()) {

    die("Access Denied");

}
/*
|--------------------------------------------------------------------------
| Get data from scanner
|--------------------------------------------------------------------------
*/

$barcode = $_GET["barcode"] ?? "";
$department = $_GET["department"] ?? "";
$course_id = $_GET["course_id"] ?? "";

if ($barcode == "" || $department == "" || $course_id == "") {
    die("Invalid attendance request.");
}

/*
|--------------------------------------------------------------------------
| Find Student
|--------------------------------------------------------------------------
*/

$sql = "
SELECT *
FROM students
WHERE barcode = :barcode
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ":barcode" => $barcode
]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

/*
|--------------------------------------------------------------------------
| Verify Department
|--------------------------------------------------------------------------
*/

if ($student["department"] != $department) {

    die("
    <div class='container mt-5'>
        <div class='alert alert-danger'>
            <h3>Wrong Department!</h3>

            This student belongs to

            <strong>{$student['department']}</strong>

            not

            <strong>{$department}</strong>.
        </div>
    </div>
    ");

}

/*
|--------------------------------------------------------------------------
| Prevent Duplicate Attendance
|--------------------------------------------------------------------------
*/

$check = $conn->prepare("
SELECT id
FROM attendance
WHERE
student_id = :student_id
AND
course_id = :course_id
AND
attendance_date = CURRENT_DATE
");

$check->execute([

    ":student_id" => $student["id"],

    ":course_id" => $course_id

]);

if($check->rowCount() > 0){

    die("
    <div class='container mt-5'>
        <div class='alert alert-warning'>
            Attendance has already been recorded today.
        </div>
    </div>
    ");

}

/*
|--------------------------------------------------------------------------
| Save Attendance
|--------------------------------------------------------------------------
*/

$save = $conn->prepare("
INSERT INTO attendance
(
student_id,
course_id
)
VALUES
(
:student_id,
:course_id
)
");

$save->execute([

":student_id"=>$student["id"],

":course_id"=>$course_id

]);

?>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>Attendance Recorded Successfully</h3>

</div>

<div class="card-body text-center">

<?php

$photo = "../uploads/students/".$student["photo"];

if(file_exists($photo)){
?>

<img
src="<?= $photo ?>"
width="170"
class="rounded shadow">

<?php } ?>

<h3 class="mt-3">

<?= htmlspecialchars($student["fullname"]) ?>

</h3>

<p>

<strong>Matric No:</strong>

<?= htmlspecialchars($student["student_id"]) ?>

</p>

<p>

<strong>Department:</strong>

<?= htmlspecialchars($student["department"]) ?>

</p>

<div class="alert alert-success">

Attendance Successfully Recorded

</div>

<a
href="scan.php?department=<?= urlencode($department) ?>&course_id=<?= $course_id ?>"
class="btn btn-primary">

Scan Next Student

</a>

<script>
setTimeout(function(){

    window.location =
    "scan.php?department=<?= urlencode($department) ?>&course_id=<?= $course_id ?>";

},2000);
</script>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>