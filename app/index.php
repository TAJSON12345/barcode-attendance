<?php
require_once "includes/auth.php";
require_once "config/db.php";

// Total Students
$totalStudents = $conn->query("
SELECT COUNT(*) FROM students
")->fetchColumn();

// Total Courses
$totalCourses = $conn->query("
SELECT COUNT(*) FROM courses
")->fetchColumn();

// Today's Attendance
$todayAttendance = $conn->query("
SELECT COUNT(*)
FROM attendance
WHERE attendance_date = CURRENT_DATE
")->fetchColumn();

// Count Students
$students = $conn->query("SELECT COUNT(*) FROM students")->fetchColumn();

// Count Courses
$courses = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();

// Count Attendance
$attendance = $conn->query("SELECT COUNT(*) FROM attendance")->fetchColumn();

// Count Users
//$users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

include "includes/header.php";
include "includes/navbar.php";
?>

<div class="container mt-4">

<h2 class="mb-4">

Dashboard

</h2>

<div class="row">

<div class="col-md-4">

<div class="card bg-primary text-white">

<div class="card-body">

<h5>Total Students</h5>

<h1><?= $totalStudents ?></h1>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-success text-white">

<div class="card-body">

<h5>Total Courses</h5>

<h1><?= $totalCourses ?></h1>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-danger text-white">

<div class="card-body">

<h5>Today's Attendance</h5>

<h1><?= $todayAttendance ?></h1>

</div>

</div>

</div>

</div>
<?php

$sql = "

SELECT

students.student_id,

students.fullname,

courses.course_title,

attendance.attendance_time

FROM attendance

JOIN students
ON attendance.student_id = students.id

JOIN courses
ON attendance.course_id = courses.id

WHERE attendance.attendance_date = CURRENT_DATE

ORDER BY attendance.attendance_time DESC

LIMIT 10

";

$stmt = $conn->query($sql);

$recent = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="card mt-4">

<div class="card-header bg-dark text-white">

Recent Attendance

</div>

<div class="card-body">

<table class="table table-striped">

<thead>

<tr>

<th>Student ID</th>

<th>Name</th>

<th>Course</th>

<th>Time</th>

</tr>

</thead>

<tbody>

<?php foreach($recent as $row){ ?>

<tr>

<td><?= htmlspecialchars($row["student_id"]) ?></td>

<td><?= htmlspecialchars($row["fullname"]) ?></td>

<td><?= htmlspecialchars($row["course_title"]) ?></td>

<td><?= $row["attendance_time"] ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<?php
include "includes/footer.php";
?>