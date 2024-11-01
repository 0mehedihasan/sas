<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Function to fetch data from a specific table in a specified database
function fetchData($conn, $dbName, $tableName) {
  // Create a connection to the selected database
  $dbConnection = $conn[$dbName];

  // Prepare the query to fetch data
  $query = "SELECT * FROM $tableName";
  $result = $dbConnection->query($query);

  // Check for errors
  if (!$result) {
    die("Error fetching data from $tableName in $dbName: " . $dbConnection->error);
  }

  // Fetch all rows as an associative array
  return $result->fetch_all(MYSQLI_ASSOC);
}

// Array to hold data from all databases
$allData = [];

// Loop through the databases and fetch data
foreach ($dbs as $dbName) {
  $students = fetchData($conn, $dbName, 'tblstudents');
  $classes = fetchData($conn, $dbName, 'tblclass');
  $classArms = fetchData($conn, $dbName, 'tblclassarms');
  $attendance = fetchData($conn, $dbName, 'tblattendance');
  $classTeachers = fetchData($conn, $dbName, 'tblclassteacher');
  $sessTerm = fetchData($conn, $dbName, 'tblsessionterm');
  $terms = fetchData($conn, $dbName, 'tblterm');

  // Store the counts
  $allData[$dbName] = [
    'students' => count($students),
    'classes' => count($classes),
    'classArms' => count($classArms),
    'attendance' => count($attendance),
    'classTeachers' => count($classTeachers),
    'sessTerm' => count($sessTerm),
    'terms' => count($terms)
  ];
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
  <?php include "Includes/sidebar.php";?>
  <!-- Sidebar -->
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
    <!-- TopBar -->
    <?php include "Includes/topbar.php";?>
    <!-- Topbar -->
    <!-- Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol>
      </div>

      <div class="row mb-3">
      <!-- Students Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
            <?php 
            // Display total students across all databases
            $totalStudents = array_sum(array_column($allData, 'students'));
            echo $totalStudents;
            ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-users fa-2x text-info"></i>
          </div>
          </div>
        </div>
        </div>
      </div>
      <!-- Class Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
        <div class="card-body">
          <div class="row align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-uppercase mb-1">Classes</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php 
            // Display total classes across all databases
            $totalClasses = array_sum(array_column($allData, 'classes'));
            echo $totalClasses;
            ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-chalkboard fa-2x text-primary"></i>
          </div>
          </div>
        </div>
        </div>
      </div>
      <!-- Class Arm Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-uppercase mb-1">Class Arms</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php 
            // Display total class arms across all databases
            $totalClassArms = array_sum(array_column($allData, 'classArms'));
            echo $totalClassArms;
            ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-code-branch fa-2x text-success"></i>
          </div>
          </div>
        </div>
        </div>
      </div>
      <!-- Std Att Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Student Attendance</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php 
            // Display total attendance across all databases
            $totalAttendance = array_sum(array_column($allData, 'attendance'));
            echo $totalAttendance;
            ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-calendar fa-2x text-secondary"></i>
          </div>
          </div>
        </div>
        </div>
      </div>
      <!-- Teachers Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-uppercase mb-1">Class Teachers</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php 
            // Display total class teachers across all databases
            $totalClassTeachers = array_sum(array_column($allData, 'classTeachers'));
            echo $totalClassTeachers;
            ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
          </div>
          </div>
        </div>
        </div>
      </div>
      <!-- Session and Terms Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-uppercase mb-1">Session & Terms</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php 
            // Display total sessions and terms across all databases
            $totalSessTerm = array_sum(array_column($allData, 'sessTerm'));
            echo $totalSessTerm;
            ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-calendar-alt fa-2x text-warning"></i>
          </div>
          </div>
        </div>
        </div>
      </div>
      <!-- Terms Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-uppercase mb-1">Terms</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php 
            // Display total terms across all databases
            $totalTerms = array_sum(array_column($allData, 'terms'));
            echo $totalTerms;
            ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-clipboard-list fa-2x text-primary"></i>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
      <!--Row-->
    </div>
    <!---Container Fluid-->
    </div>
    <!-- Footer -->
    <?php include 'includes/footer.php';?>
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
