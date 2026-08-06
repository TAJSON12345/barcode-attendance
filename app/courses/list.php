<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

include "../includes/header.php";
include "../includes/navbar.php";
if (!canManageCourses()) {

    die("Access Denied");

}
// Fetch all courses
$sql = "SELECT * FROM courses ORDER BY course_code ASC";
$stmt = $conn->query($sql);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Course Management</h2>

        <a href="add.php" class="btn btn-primary">
            + Add New Course
        </a>

    </div>

    <!-- Success Messages -->

    <?php if(isset($_GET['added'])) { ?>

        <div class="alert alert-success alert-dismissible fade show">

            Course added successfully.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php } ?>

    <?php if(isset($_GET['updated'])) { ?>

        <div class="alert alert-success alert-dismissible fade show">

            Course updated successfully.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php } ?>

    <?php if(isset($_GET['deleted'])) { ?>

        <div class="alert alert-danger alert-dismissible fade show">

            Course deleted successfully.

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    <?php } ?>
<?php if(isset($_GET['error']) && $_GET['error'] == "used") { ?>

<div class="alert alert-warning alert-dismissible fade show">

    This course cannot be deleted because attendance records already exist for it.

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php } ?>
    <div class="card shadow">

        <div class="card-body">

            <?php if(count($courses) > 0){ ?>

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                  <tr>

<th>#</th>

<th>Course Code</th>

<th>Course Title</th>

<th width="180">Actions</th>

</tr>

                </thead>

                <tbody>

                <?php

                $count = 1;

                foreach($courses as $course){

                ?>

                    <tr>

                        <td><?= $count++ ?></td>

                        <td><?= htmlspecialchars($course["course_code"]) ?></td>

<td><?= htmlspecialchars($course["course_title"]) ?></td>

                        <td>

                            <a
                                href="edit.php?id=<?= $course["id"] ?>"
                                class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a
                                href="delete.php?id=<?= $course["id"] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this course?');">

                                Delete

                            </a>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

            <?php } else { ?>

                <div class="alert alert-info">

                    No courses have been added yet.

                </div>

            <?php } ?>

        </div>

    </div>

</div>

<?php include "../includes/footer.php"; ?>