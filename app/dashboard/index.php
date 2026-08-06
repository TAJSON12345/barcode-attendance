<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$totalStudents = $conn->query("
SELECT COUNT(*) FROM students
")->fetchColumn();

$totalCourses = $conn->query("
SELECT COUNT(*) FROM courses
")->fetchColumn();

$totalAttendance = $conn->query("
SELECT COUNT(*) FROM attendance
")->fetchColumn();

$todayAttendance = $conn->query("
SELECT COUNT(*)
FROM attendance
WHERE attendance_date = CURRENT_DATE
")->fetchColumn();

$recent = $conn->query("
SELECT
students.student_id,
students.fullname,
courses.course_title,
attendance.attendance_date,
attendance.attendance_time

FROM attendance

JOIN students
ON attendance.student_id = students.id

JOIN courses
ON attendance.course_id = courses.id

ORDER BY attendance.id DESC

LIMIT 10

")->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
include "../includes/navbar.php";
?>
<div class="container mt-4">

<h2 class="mb-4">
Dashboard
</h2>
<div class="mb-4">

<a href="../students/index.php" class="btn btn-primary">
👨‍🎓 Manage Students
</a>

<a href="../courses/index.php" class="btn btn-success">
📚 Manage Courses
</a>

<a href="../attendance/index.php" class="btn btn-warning">
✅ Take Attendance
</a>

<a href="../attendance/report.php" class="btn btn-danger">
📄 Attendance Report
</a>

</div>
<div class="row">

<div class="col-md-3">

<div class="card bg-primary text-white">

<div class="card-body">

<h5>Total Students</h5>

<h2><?= $totalStudents ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-success text-white">

<div class="card-body">

<h5>Total Courses</h5>

<h2><?= $totalCourses ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-warning">

<div class="card-body">

<h5>Today's Attendance</h5>

<h2><?= $todayAttendance ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-dark text-white">

<div class="card-body">

<h5>Total Attendance</h5>

<h2><?= $totalAttendance ?></h2>

</div>

</div>

</div>

</div>
<div class="card shadow mt-4">

<div class="card-header bg-dark text-white">

Recent Attendance

</div>

<div class="card-body">

<table class="table table-striped">

<thead>

<tr>

<th>#</th>

<th>Student ID</th>

<th>Name</th>

<th>Course</th>

<th>Date</th>

<th>Time</th>

</tr>

</thead>

<tbody>

<?php

$i = 1;

foreach($recent as $row){

?>

<tr>

<td><?= $i++ ?></td>

<td><?= htmlspecialchars($row['student_id']) ?></td>

<td><?= htmlspecialchars($row['fullname']) ?></td>

<td><?= htmlspecialchars($row['course_title']) ?></td>

<td><?= $row['attendance_date'] ?></td>

<td><?= $row['attendance_time'] ?></td>

</tr>

<?php } ?>

<?php if(count($recent)==0){ ?>

<tr>

<td colspan="6" class="text-center">

No attendance records found.

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>
</div>

<?php include "../includes/footer.php"; ?>