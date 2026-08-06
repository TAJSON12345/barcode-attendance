<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$course = $_GET['course'] ?? "";
$date = $_GET['date'] ?? "";
if (!canViewReports()) {

    die("Access Denied");

}
$sql = "
SELECT
attendance.id,
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
WHERE 1=1
";

$params = [];

if($course != ""){

    $sql .= " AND attendance.course_id = :course";
    $params[':course'] = $course;

}

if($date != ""){

    $sql .= " AND attendance.attendance_date = :date";
    $params[':date'] = $date;

}

$sql .= " ORDER BY attendance.attendance_date DESC,
attendance.attendance_time DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$courseList = $conn->query("
SELECT id, course_title
FROM courses
ORDER BY course_title
")->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h3>Attendance Report</h3>

</div>

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-5">

<label>Course</label>

<select
name="course"
class="form-select">

<option value="">All Courses</option>

<?php foreach($courseList as $c){ ?>

<option
value="<?= $c['id'] ?>"
<?= ($course==$c['id'])?"selected":"" ?>>

<?= htmlspecialchars($c['course_title']) ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-4">

<label>Date</label>

<input
type="date"
name="date"
value="<?= $date ?>"
class="form-control">

</div>

<div class="col-md-3 d-flex align-items-end">

<button class="btn btn-primary w-100">

Generate Report

</button>
<a
href="export_pdf.php?course=<?= urlencode($course) ?>&date=<?= urlencode($date) ?>"
class="btn btn-danger w-100">

Export PDF

</a>

</div>

</div>

</form>

<hr>

<div class="mb-3">

<a
href="export_pdf.php?course=<?= urlencode($course) ?>&date=<?= urlencode($date) ?>"
class="btn btn-danger">

Export PDF

</a>

<a
href="export_excel.php?course=<?= urlencode($course) ?>&date=<?= urlencode($date) ?>"
class="btn btn-success">

Export Excel

</a>

</div>

<table class="table table-bordered table-striped">

<thead class="table-dark">

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

$i=1;

foreach($records as $row){

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

<?php if(count($records)==0){ ?>

<tr>

<td colspan="6" class="text-center">

No attendance records found.

</td>

</tr>

<?php } ?>

</tbody>

</table>

<div class="alert alert-info">

Total Records:

<strong><?= count($records) ?></strong>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>