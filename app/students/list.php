<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

include "../includes/header.php";
include "../includes/navbar.php";
if (!canManageStudents()) {

    die("Access Denied");

}
/*
Fetch students together with their course title.
LEFT JOIN ensures students are still shown even if
course_id is empty.
*/

$sql = "
SELECT
    id,
    student_id,
    fullname,
    department,
    level,
    barcode,
    photo,
    created_at
FROM students
ORDER BY fullname ASC
";

$stmt = $conn->query($sql);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalStudents = count($students);
?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<div>
    <h2 class="mb-0">Student Management</h2>
    <small class="text-muted">
        Total Students:
        <strong><?= $totalStudents ?></strong>
    </small>
</div>

<a href="add.php" class="btn btn-primary">

<i class="bi bi-person-plus"></i>

Register Student

</a>

</div>

<div class="card shadow">

<div class="card-body">

<?php if(count($students)>0){ ?>
<div class="mb-3">

<input
type="text"
id="searchStudent"
class="form-control"
placeholder="Search by Student ID, Name or Department...">

</div>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Photo</th>

<th>Student ID</th>

<th>Full Name</th>

<th>Department</th>

<th>Level</th>

<th>Barcode</th>

<th>Registered</th>

<th width="180">Actions</th>

</tr>

</thead>

<tbody>

<?php

$count=1;

foreach($students as $student){

?>

<tr>
    <td>

<?php

$photo = !empty($student["photo"])
    ? "../uploads/students/" . $student["photo"]
    : "../assets/images/default-user.png";

?>

<img
src="<?= $photo ?>"
width="50"
height="50"
style="border-radius:50%; object-fit:cover;">

</td>

<td><?= $count++ ?></td>

<td><?= htmlspecialchars($student["student_id"]) ?></td>

<td><?= htmlspecialchars($student["fullname"]) ?></td>

<td>

<?= htmlspecialchars($student["department"]) ?>

</td>

<td><?= htmlspecialchars($student["level"]) ?></td>

<td>

<span class="badge bg-primary">

<?= htmlspecialchars($student["barcode"]) ?>

</span>

</td>

<td>

<?= date("d M Y", strtotime($student["created_at"])) ?>

</td>

<td>
    <a href="id_card.php?id=<?= $student['id'] ?>"
class="btn btn-primary btn-sm">
    

ID Card

</a>

<a
href="edit.php?id=<?= $student["id"] ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete.php?id=<?= $student["id"] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this student?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } else { ?>

<div class="alert alert-info">

No students have been registered yet.

</div>

<?php } ?>

</div>

</div>

</div>
<script>

document.getElementById("searchStudent").addEventListener("keyup", function(){

let value = this.value.toLowerCase();

let rows = document.querySelectorAll("tbody tr");

rows.forEach(function(row){

row.style.display = row.innerText.toLowerCase().includes(value)
? ""
: "none";

});

});

</script>
<?php include "../includes/footer.php"; ?>