<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

if(!isset($_GET['id'])){
    die("Course ID not found.");
}
if (!canManageCourses()) {

    die("Access Denied");

}
$id = $_GET['id'];

// Fetch the selected course
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = :id");
$stmt->execute([
    ":id"=>$id
]);

$course = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$course){
    die("Course not found.");
}

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $course_code = strtoupper(trim($_POST["course_code"]));
    $course_title = trim($_POST["course_title"]);
    $semester = trim($_POST["semester"]);
    $session = trim($_POST["session"]);

    $sql="UPDATE courses
          SET
          course_code=:course_code,
          course_title=:course_title,
          semester=:semester,
          session=:session
          WHERE id=:id";

    $stmt=$conn->prepare($sql);

    $stmt->execute([
        ":course_code"=>$course_code,
        ":course_title"=>$course_title,
        ":semester"=>$semester,
        ":session"=>$session,
        ":id"=>$id
    ]);

    header("Location:list.php?updated=1");
    exit;
}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-warning">

<h3>Edit Course</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Course Code</label>

<input
type="text"
name="course_code"
class="form-control"
value="<?= htmlspecialchars($course["course_code"]) ?>"
required>

</div>

<div class="mb-3">

<label>Course Title</label>

<input
type="text"
name="course_title"
class="form-control"
value="<?= htmlspecialchars($course["course_title"]) ?>"
required>

</div>

<div class="mb-3">

<label>Semester</label>

<select
name="semester"
class="form-select">

<option
<?= ($course["semester"]=="First Semester")?"selected":"" ?>>

First Semester

</option>

<option
<?= ($course["semester"]=="Second Semester")?"selected":"" ?>>

Second Semester

</option>

</select>

</div>

<div class="mb-3">

<label>Academic Session</label>

<input
type="text"
name="session"
class="form-control"
value="<?= htmlspecialchars($course["session"]) ?>"
required>

</div>

<button class="btn btn-success">

Update Course

</button>

<a
href="list.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>