<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
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
    die("Connection failed for $db: " . $conn[$db]->connect_error);
  }
}

$studentData = []; // Initialize student data array
$classTeacherName = ""; // Initialize class teacher name
$className = ""; // Initialize class name
$admissionNumber = $_SESSION['admissionNumber'] ?? null;

// Check if session admissionNumber is set
if ($admissionNumber) {
    // Debugging statement to log session admissionNumber
    error_log("Session admissionNumber: " . $admissionNumber);

    // Fetch student information, class name, and class teacher from multiple databases
    foreach ($dbs as $dbKey) {
        // Debugging statement to log the database being queried
        error_log("Querying database: " . $dbKey);

        $query = "SELECT tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, 
                         tblstudents.admissionNumber, tblstudents.classId, tblstudents.dateCreated, 
                         tblclass.className 
                  FROM tblstudents 
                  JOIN tblclass ON tblstudents.classId = tblclass.Id 
                  WHERE tblstudents.admissionNumber = '$admissionNumber'"; // Use admissionNumber from session
        $result = $conn[$dbKey]->query($query);

        if ($result && $result->num_rows > 0) {
            $studentData = $result->fetch_assoc();
            $className = $studentData['className']; // Set the class name

            // Debugging statement to log student data
            error_log("Student data from $dbKey: " . print_r($studentData, true));

            // Fetch class teacher's name based on the student's classId
            $classId = $studentData['classId'];
            $teacherQuery = "SELECT firstName, lastName FROM tblclassteacher WHERE classId = '$classId' LIMIT 1";
            $teacherResult = $conn[$dbKey]->query($teacherQuery);
            if ($teacherResult && $teacherResult->num_rows > 0) {
                $teacherData = $teacherResult->fetch_assoc();
                $classTeacherName = $teacherData['firstName'] . ' ' . $teacherData['lastName'];

                // Debugging statement to log teacher data
                error_log("Teacher data from $dbKey: " . print_r($teacherData, true));
            }
            break; // Stop searching once the data is found
        }
    }
} else {
    // Handle case when admissionNumber is not found in session
    $studentData = ['firstName' => 'Guest', 'lastName' => '', 'otherName' => '', 'admissionNumber' => 'N/A', 'className' => 'N/A', 'dateCreated' => 'N/A'];
}

// Debugging statement to log final student data
error_log("Final student data: " . print_r($studentData, true));
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
  <title>Student Dashboard</title>
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
            <h1 class="h3 mb-0 text-gray-800">Student Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Student Info Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Student Name</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $studentData['firstName'] . ' ' . $studentData['lastName'] . ' ' . $studentData['otherName']; ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Admission Number Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Admission Number</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $studentData['admissionNumber']; ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-id-card fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Class Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Class</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $className; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Class Teacher Name Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Class Teacher</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classTeacherName; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-tie fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Date Created Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Date Created</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $studentData['dateCreated']; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-alt fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

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
</body>

</html>
