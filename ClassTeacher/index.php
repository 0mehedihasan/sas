<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Define the database connection variables
$host = 'localhost:5222';
$user = 'root';
$pass = '';

// Define the databases
$dbs = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];

// Define the database connections
$conn = [];
foreach ($dbs as $db) {
  $conn[$db] = new mysqli($host, $user, $pass, $db);
  if ($conn[$db]->connect_error) {
    die("Connection failed: " . $conn[$db]->connect_error);
  }
}

$statusMsg = ""; // Initialize the status message variable

// Combine classes from multiple databases
$allClasses = [];
$databases = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];

foreach ($databases as $dbKey) {
  $query = "SELECT * FROM tblclass";
  $result = $conn[$dbKey]->query($query);

  while ($row = $result->fetch_assoc()) {
    $row['dbKey'] = $dbKey; // Add the database key to each row
    $allClasses[] = $row;
  }
}

// Fetch class name for the class teacher from multiple databases
$rrw = ['className' => ''];
foreach ($databases as $dbKey) {
  $query = "SELECT tblclass.className 
            FROM tblclassteacher
            INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
            WHERE tblclassteacher.Id = '$_SESSION[userId]'";
  $rs = $conn[$dbKey]->query($query);
  if ($rs && $rs->num_rows > 0) {
    $rrw = $rs->fetch_assoc();
    break;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Class Teacher Dashboard (<?php echo $rrw['className']; ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- New User Card Example -->
            <?php
            $students = 0;
            foreach ($databases as $dbKey) {
              $classId = null;
              $query = "SELECT classId FROM tblclassteacher WHERE Id = '$_SESSION[userId]'";
              $result = $conn[$dbKey]->query($query);
              if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $classId = $row['classId'];
              }
              if ($classId) {
                $query1 = $conn[$dbKey]->prepare("SELECT * FROM tblstudents WHERE classId = ?");
                $query1->bind_param("i", $classId);
                $query1->execute();
                $result = $query1->get_result();
                $students += $result->num_rows;
                $query1->close();
              }
            }
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students; ?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <?php
            $class = 0;
            foreach ($databases as $dbKey) {
              $query1 = mysqli_query($conn[$dbKey], "SELECT * from tblclass");
              $class += mysqli_num_rows($query1);
            }
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Classes</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $class; ?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <?php
            $totAttendance = 0;
            foreach ($databases as $dbKey) {
              $query1 = mysqli_query($conn[$dbKey], "SELECT * from tblattendance WHERE classId = '$classId'");
              $totAttendance += mysqli_num_rows($query1);
            }
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Student Attendance</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totAttendance; ?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                        <span>Since yesterday</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--Row-->

            <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>Do you like this template ? you can download from <a href="https://github.com/indrijunanda/RuangAdmin"
                  class="btn btn-primary btn-sm" target="_blank"><i class="fab fa-fw fa-github"></i>&nbsp;GitHub</a></p>
            </div>
          </div> -->

          </div>
          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- Footer -->
      </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>
</body>

</html>