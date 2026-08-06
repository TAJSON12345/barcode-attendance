<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

include "../includes/header.php";
include "../includes/navbar.php";

$where = [];
$params = [];

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

";

/*
|--------------------------------------------------------------------------
| Filter by Date
|--------------------------------------------------------------------------
*/

if (!empty($_GET["attendance_date"])) {

    $where[] = "attendance.attendance_date = :attendance_date";

    $params[":attendance_date"] = $_GET["attendance_date"];

}

/*
|--------------------------------------------------------------------------
| Filter by Course
|--------------------------------------------------------------------------
*/

if (!empty($_GET["course_id"])) {

    $where[] = "attendance.course_id = :course_id";

    $params[":course_id"] = $_GET["course_id"];

}

/*
|--------------------------------------------------------------------------
| Build WHERE clause
|--------------------------------------------------------------------------
*/

if (count($where) > 0) {

    $sql .= " WHERE " . implode(" AND ", $where);

}

$sql .= " ORDER BY attendance.id DESC";

$stmt = $conn->prepare($sql);

$stmt->execute($params);

$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Attendance History</h3>

</div>

<div class="card-body">
    <form method="GET" class="row mb-4">

    <div class="col-md-4">
        <label>Date</label>
        <input
            type="date"
            name="attendance_date"
            class="form-control"
            value="<?= $_GET['attendance_date'] ?? '' ?>">
    </div>

    <div class="col-md-4">
        <label>Course</label>

        <select name="course_id" class="form-select">

            <option value="">All Courses</option>

            <?php

            $courses = $conn->query("
                SELECT id, course_title
                FROM courses
                ORDER BY course_title
            ");

            while($course = $courses->fetch(PDO::FETCH_ASSOC)){

                $selected = "";

                if(
                    isset($_GET["course_id"]) &&
                    $_GET["course_id"] == $course["id"]
                ){
                    $selected = "selected";
                }

                echo "<option value='{$course['id']}' $selected>
                        {$course['course_title']}
                      </option>";

            }

            ?>

        </select>

    </div>

    <div class="col-md-4 d-flex align-items-end">

        <button class="btn btn-primary me-2">

            Search

        </button>

        <a href="history.php" class="btn btn-secondary">

            Reset

        </a>

    </div>

</form>

<table class="table table-bordered table-striped">

<thead>

<tr>

<th>#</th>
<th>Student ID</th>
<th>Full Name</th>
<th>Course</th>
<th>Date</th>
<th>Time</th>

</tr>

</thead>

<tbody>

<?php foreach($records as $row){ ?>

<tr>

<td><?= $row["id"] ?></td>

<td><?= htmlspecialchars($row["student_id"]) ?></td>

<td><?= htmlspecialchars($row["fullname"]) ?></td>

<td><?= htmlspecialchars($row["course_title"]) ?></td>

<td><?= $row["attendance_date"] ?></td>

<td><?= $row["attendance_time"] ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>