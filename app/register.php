<?php
require_once "config/db.php";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect data from the form
    $student_id = trim($_POST["student_id"]);
    $fullname = trim($_POST["fullname"]);
    $department = trim($_POST["department"]);
    $level = trim($_POST["level"]);

    // Generate a unique barcode
    $barcode = uniqid("STD");

    try {

        $sql = "INSERT INTO students
                (student_id, fullname, department, level, barcode)
                VALUES
                (:student_id, :fullname, :department, :level, :barcode)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":student_id" => $student_id,
            ":fullname" => $fullname,
            ":department" => $department,
            ":level" => $level,
            ":barcode" => $barcode
        ]);

        $success = "Student Registered Successfully!";
    } catch(PDOException $e) {

        $error = $e->getMessage();

    }

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Register Student</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Student Registration</h3>

</div>

<div class="card-body">

<?php if(isset($success)): ?>

<div class="alert alert-success">

<?= $success ?>

<br><br>

<strong>Generated Barcode:</strong>

<?= $barcode ?>

</div>

<?php endif; ?>

<?php if(isset($error)): ?>

<div class="alert alert-danger">

<?= $error ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="mb-3">

<label>Student ID</label>

<input
type="text"
name="student_id"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="fullname"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Department</label>

<input
type="text"
name="department"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Level</label>

<select
name="level"
class="form-control">

<option>ND I</option>

<option>ND II</option>

<option>HND I</option>

<option>HND II</option>

</select>

</div>

<button class="btn btn-success">

Register Student

</button>

</form>

</div>

</div>

</div>

</body>

</html>