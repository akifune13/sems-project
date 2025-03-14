<?php
include 'inc/header.php';

Session::CheckSession();

// Check if user is logged in and their role
$roleId = Session::get('roleid');
$userId = Session::get('id');

// Redirect students to their profile page
if ($roleId == '3') {
    header('Location: profile.php?id=' . $userId . '&view=1');
    exit();
}

$logMsg = Session::get('logMsg');
if (isset($logMsg)) {
    echo $logMsg;
}
$msg = Session::get('msg');
if (isset($msg)) {
    echo $msg;
}
Session::set("msg", NULL);
Session::set("logMsg", NULL);

if (isset($_GET['remove'])) {
    $remove = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['remove']);
    $removeUser = $users->deleteUserById($remove);
}

if (isset($removeUser)) {
    echo $removeUser;
}

if (isset($deactiveId)) {
    echo $deactiveId;
}

if (isset($activeId)) {
    echo $activeId;
}
?>
      <div class="card ">
        <div class="card-header">
          <h3><i class="fas fa-users mr-2"></i>User list <span class="float-right">Welcome! <strong>
            <span class="badge badge-lg <?php
              if (Session::get('roleid') == '1') {
                echo 'badge-success';
              } elseif (Session::get('roleid') == '2') {
                echo 'badge-info';
              } else {
                echo 'badge-dark';
              }
            ?> text-white">
<?php
$username = Session::get('name');
if (isset($username)) {
    echo $username;
}
 ?></span>

          </strong></span></h3>
        </div>
        <div class="card-body pr-2 pl-2">

        <div class="table-responsive">
          <table id="example" class="table table-striped table-bordered">
              <thead>
                  <tr>
                      <th class="text-center" style="width: 5%;">SL</th>
                      <th class="text-center" style="width: 15%;">Name</th>
                      <th class="text-center" style="width: 10%;">Role</th>
                      <th class="text-center" style="width: 10%;">Mobile</th>
                      <th class="text-center" style="width: 10%;">Status</th>
                      <th class="text-center" style="width: 10%;">Grade Level</th>
                      <th class="text-center" style="width: 10%;">Strand</th>
                      <th class="text-center" style="width: 10%;">Enrolled/Added</th>
                      <th class="text-center" style="width: 25%;">Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $allUser = $users->selectAllUserData();
                  if ($allUser) {
                      $i = 0;
                      foreach ($allUser as $value) {
                          $i++;
                  ?>
                          <tr class="text-center"
                              <?php if (Session::get("id") == $value->id) {
                                  echo "style='background:#d9edf7' ";
                              } ?>>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value->name; ?></td>
                              <td>
                                  <?php
                                  if ($value->roleid == '1') {
                                      echo "<span class='badge badge-lg badge-dark text-white'>Admin</span>";
                                  } elseif ($value->roleid == '2') {
                                      echo "<span class='badge badge-lg badge-secondary text-white'>Faculty Member</span>";
                                  } elseif ($value->roleid == '3') {
                                      echo "<span class='badge badge-lg badge-primary text-white'>Student</span>";
                                  }
                                  ?>
                              </td>
                              <td><span class="text-center"><?php echo $value->mobile; ?></span></td>
                              <td>
                                  <?php if ($value->status == '0') { ?>
                                      <span class="badge badge-lg badge-success text-white">Enrolled</span>
                                  <?php } elseif ($value->status == '1') { ?>
                                      <span class="badge badge-lg badge-secondary text-white">Not Enrolled</span>
                                  <?php } elseif ($value->status == '2') { ?>
                                      <span class="badge badge-lg badge-warning text-dark">Transferred</span>
                                  <?php } elseif ($value->status == '3') { ?>
                                      <span class="badge badge-lg badge-danger text-white">Dropped</span>
                                  <?php } ?>
                              </td>

                              <td>
                                  <?php if ($value->level == '0') { ?>
                                      <span class="badge badge-lg badge-secondary text-white">Not Enrolled</span>
                                  <?php } elseif ($value->level == '1') { ?>
                                      <span class="badge badge-lg badge-light text-dark">Grade 11</span>
                                  <?php } elseif ($value->level == '2') { ?>
                                      <span class="badge badge-lg badge-dark text-white">Grade 12</span>
                                  <?php }
                                   ?>
                              </td>

                              <td>
                                  <?php if ($value->strand == '0') { ?>
                                      <span class="badge badge-lg badge-secondary text-white">Not Enrolled</span>
                                  <?php } elseif ($value->strand == '1') { ?>
                                      <span class="badge badge-lg badge-info text-white">Academic - STEM</span>
                                  <?php } elseif ($value->strand == '2') { ?>
                                      <span class="badge badge-lg badge-info text-white">Academic - ABM</span>
                                  <?php } elseif ($value->strand == '3') { ?>
                                      <span class="badge badge-lg badge-info text-white">Academic - HUMSS</span>
                                  <?php } elseif ($value->strand == '4') { ?>
                                      <span class="badge badge-lg badge-info text-white">TVL - HE</span>
                                  <?php } elseif ($value->strand == '5') { ?>
                                      <span class="badge badge-lg badge-info text-white">TVL - EIM</span>
                                  <?php } elseif ($value->strand == '6') { ?>
                                      <span class="badge badge-lg badge-info text-white">TVL - CSS</span>
                                  <?php } elseif ($value->strand == '7') { ?>
                                      <span class="badge badge-lg badge-info text-white">TVL - TD</span>
                                  <?php } else { ?>
                                      <span class="badge badge-lg badge-danger text-white">Unknown</span>
                                  <?php } ?>
                              </td>
                              <td><span class="text-center"><?php echo $users->formatDate($value->created_at); ?></span></td>
                              <td>
                                  <?php if (Session::get("roleid") == '1') { ?>
                                      <a class="btn btn-success btn-sm" href="profile.php?id=<?php echo $value->id; ?>&view=1">View</a>
                                      <a class="btn btn-info btn-sm" href="profile.php?id=<?php echo $value->id; ?>">Edit</a>
                                      <a onclick="return confirm('Are you sure want to remove this user?')" class="btn btn-danger
                                          <?php if (Session::get("id") == $value->id) {
                                              echo "disabled";
                                          } ?>
                                          btn-sm" href="?remove=<?php echo $value->id; ?>">Remove</a>
                                  <?php } elseif (Session::get("id") == $value->id && Session::get("roleid") == '2') { ?>
                                      <a class="btn btn-success btn-sm" href="profile.php?id=<?php echo $value->id; ?>&view=1">View</a>
                                      <a class="btn btn-info btn-sm" href="profile.php?id=<?php echo $value->id; ?>">Edit</a>
                                  <?php } elseif (Session::get("roleid") == '2') { ?>
                                      <a class="btn btn-success btn-sm" href="profile.php?id=<?php echo $value->id; ?>&view=1">View</a>
                                      <a class="btn btn-info btn-sm
                                          <?php if ($value->roleid == '1') {
                                              echo "disabled";
                                          } ?>
                                          " href="profile.php?id=<?php echo $value->id; ?>">Edit</a>
                                  <?php } elseif (Session::get("id") == $value->id && Session::get("roleid") == '3') { ?>
                                      <a class="btn btn-success btn-sm" href="profile.php?id=<?php echo $value->id; ?>&view=1">View</a>
                                  <?php } else { ?>
                                      <a class="btn btn-success btn-sm
                                          <?php if ($value->roleid == '1') {
                                              echo "disabled";
                                          } ?>
                                          " href="profile.php?id=<?php echo $value->id; ?>">View</a>
                                  <?php } ?>
                              </td>
                          </tr>
                  <?php }
                  } else { ?>
                      <tr class="text-center">
                          <td colspan="9">No user available now!</td>
                      </tr>
                  <?php } ?>
              </tbody>
          </table>
      </div>

      </div>

<?php
include 'inc/footer.php';

?>