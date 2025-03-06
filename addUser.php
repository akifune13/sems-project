<?php
include 'inc/header.php';
Session::CheckSession();

// Get current user's role
$role = Session::get('roleid');

// Restrict access to admins and faculty members
$allowedRoles = [1, 2];
if (!in_array($role, $allowedRoles)) {
    header('Location: profile.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addUser'])) {
    // Sanitize POST data
    $data = [
        'name' => trim($_POST['name']),
        'username' => trim($_POST['username']),
        'email' => trim($_POST['email']),
        'mobile' => trim($_POST['mobile']),
        'roleid' => trim($_POST['roleid']),
        'password' => trim($_POST['password']),
    ];

    // Add user and check result
    $result = $users->addNewUserByAdmin($data);

    // Determine if the operation was successful
    if (strpos($result, 'alert-success') !== false) {
        Session::set('msg', $result);
        header('Location: index.php');
        exit();
    } else {
        // Store error message for display
        $errorMessage = $result;
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="text-center">Add New User</h3>
    </div>
    <div class="card-body">
        <div style="width:600px; margin:0px auto">
            <form action="" method="post">
                <?php if (isset($errorMessage)): ?>
                    <?= $errorMessage; ?>
                <?php endif; ?>
                <div class="form-group pt-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($_POST['mobile'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="roleid">Role</label>
                    <select class="form-control" name="roleid" required>
                        <option value="1" <?= ($_POST['roleid'] ?? '') == '1' ? 'selected' : ''; ?>>Admin</option>
                        <option value="2" <?= ($_POST['roleid'] ?? '') == '2' ? 'selected' : ''; ?>>Faculty Member</option>
                        <option value="3" <?= ($_POST['roleid'] ?? '') == '3' ? 'selected' : ''; ?>>Student</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="addUser" class="btn btn-success">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>