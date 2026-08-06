<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    require_once __DIR__ . "/auth.php";
}

// Detect whether we're in the root folder or a subfolder
$base = (basename(dirname($_SERVER['PHP_SELF'])) == "app") ? "" : "../";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

<div class="container">

<a class="navbar-brand fw-bold" href="<?= $base ?>index.php">
    🎓 Attendance System
</a>

<button class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarMenu">

    <span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navbarMenu">

<ul class="navbar-nav me-auto">

<li class="nav-item">
<a class="nav-link" href="<?= $base ?>index.php">Dashboard</a>
</li>

<?php if(canManageStudents()){ ?>

<li class="nav-item">
<a class="nav-link" href="<?= $base ?>students/list.php">Students</a>
</li>

<?php } ?>

<?php if(canManageCourses()){ ?>

<li class="nav-item">
<a class="nav-link" href="<?= $base ?>courses/list.php">Courses</a>
</li>

<?php } ?>

<?php if(canManageUsers()){ ?>

<li class="nav-item">
<a class="nav-link" href="<?= $base ?>users/list.php">
Users
</a>
</li>

<?php } ?>
<?php if(canTakeAttendance()){ ?>

<li class="nav-item">
<a class="nav-link" href="<?= $base ?>attendance/start.php">
Take Attendance
</a>
</li>

<?php } ?>

<?php if(canViewReports()){ ?>

<li class="nav-item">
<a class="nav-link" href="<?= $base ?>attendance/report.php">
Attendance Report
</a>
</li>

<?php } ?>

</ul>

<?php if(isset($_SESSION["fullname"])){ ?>

<span class="navbar-text text-white me-3">

<strong><?= htmlspecialchars($_SESSION["fullname"]) ?></strong>

<small>(<?= htmlspecialchars($_SESSION["role"]) ?>)</small>

</span>

<a href="<?= $base ?>logout.php"
class="btn btn-light btn-sm">

Logout

</a>

<?php } ?>

</div>

</div>

</nav>