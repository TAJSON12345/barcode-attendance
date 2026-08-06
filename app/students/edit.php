<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
// Check if an ID was provided
if (!isset($_GET["id"])) {
    die("Student ID not specified.");
}
if (!canManageStudents()) {

    die("Access Denied");

}
$id = $_GET["id"];
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

// Load the student's current details
$stmt = $conn->prepare("
    SELECT *
    FROM students
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

$message = "";

// Update when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = strtoupper(trim($_POST["student_id"]));
$fullname   = trim($_POST["fullname"]);
$department = trim($_POST["department"]);
$level      = trim($_POST["level"]);

$barcode = "FEPO-" . $student_id;
$photoName = $student["photo"];

if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){

    $allowed = ["jpg","jpeg","png","gif","webp"];

    $extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));

    if(in_array($extension,$allowed)){

        if(!is_dir("../uploads/students")){

            mkdir("../uploads/students",0777,true);

        }

        $photoName = uniqid().".".$extension;

        move_uploaded_file(

            $_FILES["photo"]["tmp_name"],

            "../uploads/students/".$photoName

        );

    }

}
    $sql = "
    UPDATE students
    SET
        student_id = :student_id,
        fullname = :fullname,
        department = :department,
photo = :photo,
        level = :level,
        barcode = :barcode
    WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([

    ":student_id"=>$student_id,
    ":fullname"=>$fullname,
    ":department"=>$department,
    ":level"=>$level,
    ":barcode"=>$barcode,
    ":photo"=>$photoName,
    ":id"=>$id

]);

    header("Location: list.php?updated=1");
    exit;
}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-warning">

<h3>Edit Student</h3>

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label>Student ID</label>

<input
type="text"
name="student_id"
class="form-control"
value="<?= htmlspecialchars($student["student_id"]) ?>"
required>

</div>

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="fullname"
class="form-control"
value="<?= htmlspecialchars($student["fullname"]) ?>"
required>

</div>

<div class="mb-3">

<label>Department</label>

<select
name="department"
class="form-select"
required>

<?php foreach($departments as $department){ ?>

<option
value="<?= $department ?>"
<?= ($student["department"] == $department) ? "selected" : "" ?>>

<?= $department ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Current Passport</label>

<br>

<?php

$image = !empty($student["photo"])
    ? "../uploads/students/".$student["photo"]
    : "../assets/images/default-user.png";

?>

<img
src="<?= $image ?>"
width="120"
class="img-thumbnail">

</div>
<div class="mb-3">

<label>Change Passport</label>

<input
type="file"
name="photo"
class="form-control"
accept="image/*">

</div>

<div class="mb-3">

<label>Level</label>

<select
name="level"
class="form-select">

<option <?= ($student["level"]=="ND I")?"selected":"" ?>>ND I</option>
<option <?= ($student["level"]=="ND II")?"selected":"" ?>>ND II</option>
<option <?= ($student["level"]=="HND I")?"selected":"" ?>>HND I</option>
<option <?= ($student["level"]=="HND II")?"selected":"" ?>>HND II</option>

</select>

</div>

<button class="btn btn-success">

Update Student

</button>

<a href="list.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>