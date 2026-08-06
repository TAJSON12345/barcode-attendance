<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

include "../includes/header.php";
include "../includes/navbar.php";
if (!canTakeAttendance()) {

    die("Access Denied");

}
$departments = [

    "Computer Science",

    "Science Laboratory Technology",

    "Statistics",

    "Electrical/Electronic Engineering",

    "Computer Engineering",

    "Business Administration",

    "Accountancy",

    "Public Administration",

    "Office Technology and Management",

    "Mass Communication"

];

$courses = $conn->query("
SELECT *
FROM courses
ORDER BY course_title
")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>Take Attendance</h3>

</div>

<div class="card-body">

<form action="scan.php" method="GET">

<div class="mb-3">

<label class="form-label">

Department

</label>

<select
name="department"
class="form-select"
required>

<option value="">Select Department</option>

<?php foreach($departments as $department){ ?>

<option value="<?= $department ?>">

<?= $department ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Course

</label>

<select
name="course_id"
class="form-select"
required>

<option value="">Select Course</option>

<?php foreach($courses as $course){ ?>

<option value="<?= $course['id'] ?>">

<?= htmlspecialchars($course['course_code']) ?>

-

<?= htmlspecialchars($course['course_title']) ?>

</option>

<?php } ?>

</select>

</div>

<button
class="btn btn-success btn-lg w-100">

Start Attendance

</button>

</form>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>