<?php

require_once "../includes/auth.php";
require_once "../config/db.php";

if (!canManageUsers()) {

    die("Access Denied");

}

if (!isset($_GET["id"])) {
    die("User not found.");
}

$id = $_GET["id"];

// Fetch user
$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=:id
");

$stmt->execute([
    ":id"=>$id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user){
    die("User not found.");
}

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $role = $_POST["role"];
    $password = $_POST["password"];

    // Check duplicate username
    $check = $conn->prepare("
    SELECT id
    FROM users
    WHERE username=:username
    AND id<>:id
    ");

    $check->execute([
        ":username"=>$username,
        ":id"=>$id
    ]);

    if($check->rowCount()>0){

        $message = '<div class="alert alert-danger">
        Username already exists.
        </div>';

    }else{

        if(!empty($password)){

            $hash = password_hash($password,PASSWORD_DEFAULT);

            $sql="
            UPDATE users
            SET
            fullname=:fullname,
            username=:username,
            role=:role,
            password=:password
            WHERE id=:id
            ";

            $stmt=$conn->prepare($sql);

            $stmt->execute([
                ":fullname"=>$fullname,
                ":username"=>$username,
                ":role"=>$role,
                ":password"=>$hash,
                ":id"=>$id
            ]);

        }else{

            $sql="
            UPDATE users
            SET
            fullname=:fullname,
            username=:username,
            role=:role
            WHERE id=:id
            ";

            $stmt=$conn->prepare($sql);

            $stmt->execute([
                ":fullname"=>$fullname,
                ":username"=>$username,
                ":role"=>$role,
                ":id"=>$id
            ]);

        }

        header("Location:list.php?updated=1");
        exit();

    }

}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-warning">

<h3>Edit User</h3>

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
value="<?= htmlspecialchars($user["fullname"]) ?>"
required>

</div>

<div class="mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
value="<?= htmlspecialchars($user["username"]) ?>"
required>

</div>

<div class="mb-3">

<label>New Password</label>

<input
type="password"
name="password"
class="form-control">

<small class="text-muted">

Leave blank to keep the current password.

</small>

</div>

<div class="mb-3">

<label>Role</label>

<select name="role" class="form-select">

<option
value="Administrator"
<?= $user["role"]=="Administrator" ? "selected" : "" ?>>
Administrator
</option>

<option
value="Staff"
<?= $user["role"]=="Staff" ? "selected" : "" ?>>
Staff
</option>

<option
value="Lecturer"
<?= $user["role"]=="Lecturer" ? "selected" : "" ?>>
Lecturer
</option>

</select>

</div>

<button class="btn btn-success">

Update User

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