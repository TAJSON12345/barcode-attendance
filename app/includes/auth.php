<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

/*
|--------------------------------------------------------------------------
| User Roles
|--------------------------------------------------------------------------
*/

function isAdmin()
{
    return isset($_SESSION["role"]) &&
           $_SESSION["role"] === "Administrator";
}

function isStaff()
{
    return isset($_SESSION["role"]) &&
           $_SESSION["role"] === "Staff";
}

function isLecturer()
{
    return isset($_SESSION["role"]) &&
           $_SESSION["role"] === "Lecturer";
}

/*
|--------------------------------------------------------------------------
| Permissions
|--------------------------------------------------------------------------
*/

function canManageUsers()
{
    return isAdmin();
}

function canManageStudents()
{
    return isAdmin() || isStaff();
}

function canManageCourses()
{
    return isAdmin() || isStaff();
}

function canTakeAttendance()
{
    return isAdmin() || isStaff() || isLecturer();
}

function canViewReports()
{
    return isAdmin() || isStaff() || isLecturer();
}

function canDownloadReports()
{
    return isAdmin() || isStaff() || isLecturer();
}