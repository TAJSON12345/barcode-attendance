<?php

require_once "../includes/auth.php";
require_once "../config/db.php";

if (!canManageUsers()) {

    die("Access Denied");

}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];
    $role = $_POST["role"];

    if ($password != $confirm) {

        $message = '<div class="alert alert-danger">
                        Passwords do not match.
                    </div>';

    } else {

        // Check username
        $check = $conn->prepare("
            SELECT id
            FROM users
            WHERE username = :username
        ");

        $check->execute([
            ":username" => $username
        ]);

        if ($check->rowCount() > 0) {

            $message = '<div class="alert alert-danger">
                            Username already exists.
                        </div>';

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "
            INSERT INTO users
            (fullname, username, password, role)
            VALUES
            (:fullname, :username, :password, :role)
            ";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ":fullname" => $fullname,
                ":username" => $username,
                ":password" => $hash,
                ":role" => $role
            ]);

            header("Location: list.php?added=1");
            exit();

        }

    }

}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Add New User</h3>

</div>

<div class="card-body">

<?= $message ?>

<form method="POST">

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="fullname"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Role</label>

<select name="role" class="form-select">

    <option value="Administrator">Administrator</option>

    <option value="Staff">Staff</option>

    <option value="Lecturer">Lecturer</option>

</select>

</div>

<button class="btn btn-success">

Save User

</button>

<a href="list.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>