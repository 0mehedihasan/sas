<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Function to get the correct database connection based on admin email
function getAdminDatabaseConnection($conn, $emailAddress) {
  $databases = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];
  foreach ($databases as $dbKey) {
    $query = "SELECT * FROM tbladmin WHERE emailAddress = '$emailAddress'";
    $result = $conn[$dbKey]->query($query);
    if ($result && $result->num_rows > 0) {
      return $conn[$dbKey];
    }
  }
  return null;
}

// Set a default full name
$fullName = "Guest";

$emailAddress = $_SESSION['emailAddress'];
$selectedConn = getAdminDatabaseConnection($conn, $emailAddress);

if ($selectedConn) {
  $query = "SELECT * FROM tbladmin WHERE emailAddress = '$emailAddress'";
  $rs = $selectedConn->query($query);
  if ($rs && $rs->num_rows > 0) {
    $rows = $rs->fetch_assoc();
    $fullName = $rows['firstName'] . " " . $rows['lastName'];
  }
}
?>

<nav class="navbar navbar-expand navbar-light bg-gradient-primary topbar mb-4 static-top">
  <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>
  <div class="text-white big" style="margin-left:100px;"><b></b></div>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
         aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search fa-fw"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
        <form class="navbar-search">
          <div class="input-group">
            <input type="text" class="form-control bg-light border-1 small" placeholder="What do you want to look for?"
                   aria-label="Search" aria-describedby="basic-addon2" style="border-color: #3f51b5;">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>
    <div class="topbar-divider d-none d-sm-block"></div>
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
         aria-haspopup="true" aria-expanded="false">
        <img class="img-profile rounded-circle" src="img/user-icn.png" style="max-width: 60px">
        <span class="ml-2 d-none d-lg-inline text-white small"><b>Welcome <?php echo $fullName; ?></b></span>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="logout.php">
          <i class="fas fa-power-off fa-fw mr-2 text-danger"></i>
          Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
