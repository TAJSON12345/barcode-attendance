<?php

require_once "../includes/auth.php";
require_once "../config/db.php";

include "../includes/header.php";
include "../includes/navbar.php";

// Only Administrators can access
if (!canManageUsers()) {

    die("Access Denied");

}

$sql = "
SELECT *
FROM users
ORDER BY id ASC
";

$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h2>User Management</h2>

<a href="add.php" class="btn btn-primary">
➕ Add New User
</a>

</div>
<?php if(isset($_GET["added"])){ ?>

<div class="alert alert-success alert-dismissible fade show">

User created successfully.

<button
type="button"
class="btn-close"
data-bs-dismiss="alert"></button>

</div>

<?php } ?>
<?php if(isset($_GET["updated"])){ ?>

<div class="alert alert-success alert-dismissible fade show">

User updated successfully.

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php } ?>
<?php if(isset($_GET["deleted"])){ ?>

<div class="alert alert-success alert-dismissible fade show">

User deleted successfully.

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php } ?>
<?php if(isset($_GET["deleted"])){ ?>

<div class="alert alert-success alert-dismissible fade show">

User deleted successfully.

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php } ?>
<?php if(isset($_GET["selfdelete"])){ ?>

<div class="alert alert-danger alert-dismissible fade show">

You cannot delete your own account.

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php } ?>
<?php if(isset($_GET["lastadmin"])){ ?>

<div class="alert alert-warning alert-dismissible fade show">

The last Administrator account cannot be deleted.

<button
type="button"
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php } ?>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Full Name</th>

<th>Username</th>

<th>Role</th>

<th width="180">Actions</th>

</tr>

</thead>

<tbody>

<?php

$count = 1;

foreach($users as $user){

?>

<tr>

<td><?= $count++ ?></td>

<td><?= htmlspecialchars($user["fullname"]) ?></td>

<td><?= htmlspecialchars($user["username"]) ?></td>

<td>

<?php

switch($user["role"]){

    case "Administrator":
        echo '<span class="badge bg-danger">Administrator</span>';
        break;

    case "Staff":
        echo '<span class="badge bg-success">Staff</span>';
        break;

    case "Lecturer":
        echo '<span class="badge bg-info text-dark">Lecturer</span>';
        break;

    default:
        echo '<span class="badge bg-secondary">'
            . htmlspecialchars($user["role"]) .
            '</span>';
}

?>

</td>

<td>

<a
href="edit.php?id=<?= $user["id"] ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete.php?id=<?= $user["id"] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this user?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>