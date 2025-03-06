<?php
include 'inc/header.php';
Session::CheckSession();

if (isset($_GET['id'])) {
    $userid = (int)$_GET['id'];
} else {
    die("User ID not provided.");
}

$currentUserId = (int)Session::get('id');
$currentRoleId = (int)Session::get('roleid');

// Determine if the current user is changing their own password
$selfChange = ($userid === $currentUserId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changepass'])) {
    if ($selfChange) {
        // For self-change, require old password verification.
        $changePass = $users->changePasswordBysingelUserId($userid, $_POST);
    } else {
        // For admin/faculty changing another user's password,
        // we only need the new password (old password is not required).
        if ($currentRoleId === 1 || $currentRoleId === 2) {
            $changePass = $users->changePasswordBysingelUserId($userid, ['new_password' => $_POST['new_password']]);
        } else {
            $changePass = "<div class='alert alert-danger'>Unauthorized action.</div>";
        }
    }
}

if (isset($changePass)) {
    echo $changePass;
}
?>

<div class="card">
    <div class="card-header">
        <h3>Change Password
            <span class="float-right">
                <a href="profile.php?id=<?php echo $userid; ?>" class="btn btn-primary">Back</a>
            </span>
        </h3>
    </div>
    <div class="card-body">
        <div style="width:600px; margin:0px auto">
            <form action="" method="POST">
                <?php if ($selfChange): ?>
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" name="old_password" class="form-control">
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" name="changepass" class="btn btn-success">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
