<?php
include 'inc/header.php';
Session::CheckSession();

if (isset($_GET['id'])) {
    $userid = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $currentUserId = Session::get('id');
    $currentRoleId = Session::get('roleid');
    $canEdit = ((int)$currentRoleId === 1) || ($currentUserId === $userid);
    
    if ($canEdit) {
        $updateData = [
            'name' => $_POST['name'],
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'mobile' => $_POST['mobile'],
            'status' => $_POST['status']
        ];

        if ($currentRoleId === 1 || $currentRoleId === 2) {
            $updateData['strand'] = $_POST['strand'];
        }

        if ($currentRoleId === 1) {
            $updateData['roleid'] = $_POST['roleid'];
        }

        // Add the level field if the user is an admin or faculty member
        if ($currentRoleId === 1 || $currentRoleId === 2) {
            $updateData['level'] = $_POST['level'];
        }

        $updateUser = $users->updateUserByIdInfo($userid, $updateData);
        if (isset($updateUser)) {
            echo $updateUser;
        }
    }
}

$getUinfo = $users->getUserInfoById($userid);
if (!$getUinfo) {
    header('Location: index.php');
    exit();
}

$isViewMode = isset($_GET['view']) ? (int)$_GET['view'] === 1 : false;
$currentRoleId = (int)Session::get('roleid');
$currentUserId = Session::get('id');

$strands = [
    0 => "Not Enrolled",
    1 => "Academic - STEM",
    2 => "Academic - ABM",
    3 => "Academic - HUMSS",
    4 => "TVL - HE",
    5 => "TVL - EIM",
    6 => "TVL - CSS",
    7 => "TVL - TD"
];

$gradeLevels = [
    0 => "Not Enrolled",
    1 => "Grade 11",
    2 => "Grade 12"
];
?>

<div class="card">
    <div class="card-header">
        <h3>Details of <b><?php echo htmlspecialchars($getUinfo->name); ?></b> 
            <?php if ($currentRoleId !== 3): ?>
            <span class="float-right">
                <a href="index.php" class="btn btn-primary">Back</a>
            </span>
            <?php endif; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if ($isViewMode): ?>
            <div style="width: 600px; margin: 0px auto;">
                <h4 class="text-center">User Details</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr><th>Name</th><td><?php echo htmlspecialchars($getUinfo->name); ?></td></tr>
                        <tr><th>Username</th><td><?php echo htmlspecialchars($getUinfo->username); ?></td></tr>
                        <tr><th>Email</th><td><?php echo htmlspecialchars($getUinfo->email); ?></td></tr>
                        <tr><th>Mobile</th><td><?php echo htmlspecialchars($getUinfo->mobile); ?></td></tr>
                        
                        <tr><th>Status</th>
                            <td>
                                <?php
                                $statusText = [0 => 'Enrolled', 1 => 'Not Enrolled', 2 => 'Transferred', 3 => 'Dropped'];
                                echo $statusText[$getUinfo->status] ?? 'Unknown';
                                ?>
                            </td>
                        </tr>
                        
                        <tr><th>Strand</th>
                            <td><?php echo $strands[$getUinfo->strand] ?? 'Unknown'; ?></td>
                        </tr>
                        
                        <tr><th>Grade Level</th>
                            <td><?php echo $gradeLevels[$getUinfo->level] ?? 'Unknown'; ?></td>
                        </tr>

                        <?php if ($currentRoleId === 1 && $getUinfo->roleid !== 1): ?>
                        <tr><th>Role</th>
                            <td>
                                <?php
                                echo ($getUinfo->roleid === 1) ? 'Admin' : (($getUinfo->roleid === 2) ? 'Faculty Member' : 'Student');
                                ?>
                            </td>
                        </tr>
                        
                        <?php endif; ?>

                        <tr><th>Created</th><td><?php echo htmlspecialchars($users->formatDate($getUinfo->created_at)); ?></td></tr>
                    </tbody>
                </table>
                <?php if ($currentRoleId !== 3): ?>
                <div class="text-center">
                    <a class="btn btn-primary" href="profile.php?id=<?php echo $userid; ?>">Edit Profile</a>
                </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="width: 600px; margin: 0px auto;">
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($getUinfo->name); ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($getUinfo->username); ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($getUinfo->email); ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Mobile:</label>
                        <input type="text" name="mobile" value="<?php echo htmlspecialchars($getUinfo->mobile); ?>" class="form-control">
                    </div>


                    <div class="form-group">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="0" <?php if ($getUinfo->status === 0) echo 'selected'; ?>>Enrolled</option>
                            <option value="1" <?php if ($getUinfo->status === 1) echo 'selected'; ?>>Not Enrolled</option>
                            <option value="2" <?php if ($getUinfo->status === 2) echo 'selected'; ?>>Transferred</option>
                            <option value="3" <?php if ($getUinfo->status === 3) echo 'selected'; ?>>Dropped</option>
                        </select>
                    </div>

                    <?php if ($currentRoleId === 1 || $currentRoleId === 2): ?>
                    <div class="form-group">
                        <label>Strand:</label>
                        <select name="strand" class="form-control">
                            <?php foreach ($strands as $key => $value): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($getUinfo->strand == $key) ? 'selected' : ''; ?>>
                                    <?php echo $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if ($currentRoleId === 1 || $currentRoleId === 2): ?>
                    <div class="form-group">
                        <label>Grade Level:</label>
                        <select name="level" class="form-control">
                            <?php foreach ($gradeLevels as $key => $value): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($getUinfo->level == $key) ? 'selected' : ''; ?>>
                                    <?php echo $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if ($currentRoleId === 1): ?>
                    <div class="form-group">
                        <label>Role:</label>
                        <select name="roleid" class="form-control">
                            <option value="1" <?php if ($getUinfo->roleid === 1) echo 'selected'; ?>>Admin</option>
                            <option value="2" <?php if ($getUinfo->roleid === 2) echo 'selected'; ?>>Faculty Member</option>
                            <option value="3" <?php if ($getUinfo->roleid === 3) echo 'selected'; ?>>Student</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <button type="submit" name="update" class="btn btn-success">Update Details</button>
                        <a href="changepass.php?id=<?php echo $getUinfo->id; ?>&self=<?php echo ($currentUserId === $userid) ? '1' : '0'; ?>" class="btn btn-primary">Password Change</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>