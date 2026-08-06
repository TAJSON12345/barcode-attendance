<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$message = "";
if (!canManageCourses()) {

    die("Access Denied");

}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $course_code = strtoupper(trim($_POST["course_code"]));
    $course_title = trim($_POST["course_title"]);
    $semester = trim($_POST["semester"]);
    $session = trim($_POST["session"]);

    try {

        $sql = "INSERT INTO courses
                (course_code, course_title, semester, session)
                VALUES
                (:course_code, :course_title, :semester, :session)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":course_code" => $course_code,
            ":course_title" => $course_title,
            ":semester" => $semester,
            ":session" => $session
        ]);

        header("Location: list.php?added=1");
exit;

    } catch(PDOException $e) {

        $message = $e->getMessage();

    }

}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">

<div class="card">

<div class="card-header bg-success text-white">
<h3>Add Course</h3>
</div>

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert alert-success">
<?= $message ?>
</div>

<?php } ?>

<form method="POST">

    <div class="mb-3">
        <label>Course Code</label>
        <input
            type="text"
            name="course_code"
            class="form-control"
            placeholder="e.g. CSC211"
            required>
    </div>

    <div class="mb-3">
        <label>Course Title</label>
        <input
            type="text"
            name="course_title"
            class="form-control"
            placeholder="Introduction to Programming"
            required>
    </div>

    <div class="mb-3">
        <label>Semester</label>
        <select
            name="semester"
            class="form-select">

            <option>First Semester</option>
            <option>Second Semester</option>

        </select>
    </div>

    <div class="mb-3">
        <label>Academic Session</label>
        <input
            type="text"
            name="session"
            class="form-control"
            placeholder="2025/2026"
            required>
    </div>

    <button class="btn btn-success">
        Save Course
    </button>

</form>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>