<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
if (!canManageStudents()) {

    die("Access Denied");

}

$message = "";

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $student_id = strtoupper(trim($_POST["student_id"]));
    $fullname   = trim($_POST["fullname"]);
    $department = trim($_POST["department"]);
    $level      = trim($_POST["level"]);
    $photoName = "";
    /*
|--------------------------------------------------------------------------
| Check if Student ID already exists
|--------------------------------------------------------------------------
*/

$check = $conn->prepare("
SELECT id
FROM students
WHERE student_id = :student_id
");

$check->execute([
    ":student_id" => $student_id
]);

if($check->rowCount() > 0){

    $message = "Student ID already exists.";

}

if($message == "" && isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){

    $allowed = ["jpg","jpeg","png","gif","webp"];

$extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));

if(in_array($extension, $allowed)){

    if(!is_dir("../uploads/students")){

        mkdir("../uploads/students",0777,true);

    }

    $photoName = uniqid() . "." . $extension;

    move_uploaded_file(

        $_FILES["photo"]["tmp_name"],

        "../uploads/students/" . $photoName

    );

}else{

    $message = "Only JPG, PNG, GIF and WEBP images are allowed.";

}

}

    // Generate barcode
    // Generate a unique barcode
$barcode = "FEPO-" . strtoupper($student_id);

    if($message == ""){

try {
       $sql = "INSERT INTO students
(
    student_id,
    fullname,
    department,
    level,
    barcode,
    photo
)
VALUES
(
    :student_id,
    :fullname,
    :department,
    :level,
    :barcode,
    :photo
)";
        $stmt = $conn->prepare($sql);

       $stmt->execute([
    ":student_id" => $student_id,
    ":fullname"   => $fullname,
    ":level"      => $level,
    ":barcode"    => $barcode,
    ":department" => $department,
    ":photo"      => $photoName
]);

        $message = "Student Registered Successfully.";
        $_POST = [];

    } catch(PDOException $e){

        $message = $e->getMessage();

    }
}
}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Add Student</h3>

</div>

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert <?= strpos($message,"Successfully") !== false ? "alert-success" : "alert-danger"; ?>">

<?= htmlspecialchars($message) ?>

</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label>Student ID</label>

<input
type="text"
name="student_id"
class="form-control"
value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>"
required>

</div>

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="fullname"
class="form-control"
value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
required>

</div>

<div class="mb-3">

<label>Department</label>

<select
name="department"
class="form-select"
required>

<option value="">Select Department</option>

<?php foreach($departments as $department){ ?>

<option
value="<?= $department ?>"
<?= (($_POST["department"] ?? "") == $department) ? "selected" : "" ?>>

<?= $department ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Level</label>

<select
name="level"
class="form-select">

<option>ND I</option>
<option>ND II</option>
<option>HND I</option>
<option>HND II</option>

</select>

</div>
<div class="mb-3">

<label class="form-label">

Student Passport

</label>

<input
type="file"
name="photo"
class="form-control"
accept="image/*">

</div>
<button class="btn btn-success">

Register Student

</button>

</form>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>