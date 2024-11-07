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

$statusMsg = ""; // Initialize the status message variable

// Get the student's admission number from session
$admissionNumber = $_SESSION['admissionNumber'] ?? null;
$dateTaken = date("Y-m-d");

if ($admissionNumber) {
  // If form is submitted to view attendance on a specific date
  if (isset($_POST['view'])) {
    $dateTaken = $_POST['dateTaken'];

    $attendanceRecords = [];
    $attendanceTaken = false;
    foreach ($dbs as $db) {
      $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
                tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                FROM tblattendance
                INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                WHERE tblattendance.dateTimeTaken = '$dateTaken' AND tblattendance.admissionNo = '$admissionNumber'";
      $rs = $conn[$db]->query($query);

      if ($rs && $rs->num_rows > 0) {
        $attendanceTaken = true;
        $attendanceRecords = array_merge($attendanceRecords, $rs->fetch_all(MYSQLI_ASSOC));
      }
    }

    if (!$attendanceTaken) {
      $statusMsg = "Attendance was not taken by the teacher on the given date.";
    } elseif (empty($attendanceRecords)) {
      $statusMsg = "No attendance records found for the given date.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Student Attendance Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php";?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php";?>

        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">My Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Attendance</h6>
                  <?php echo $statusMsg ?? ''; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="dateTaken" required>
                      </div>
                    </div>
                    <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
                  </form>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Attendance Records</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Other Name</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (isset($attendanceRecords) && count($attendanceRecords) > 0) {
                            $sn = 0;
                            foreach ($attendanceRecords as $record) {
                              $sn++;
                              $status = $record['status'] == '1' ? "Present" : "Absent";
                              $color = $record['status'] == '1' ? "#00FF00" : "#FF0000";
                              echo "<tr>
                                      <td>{$sn}</td>
                                      <td>{$record['firstName']}</td>
                                      <td>{$record['lastName']}</td>
                                      <td>{$record['otherName']}</td>
                                      <td>{$record['admissionNumber']}</td>
                                      <td>{$record['className']}</td>
                                      <td style='background-color:{$color}'>{$status}</td>
                                      <td>{$record['dateTimeTaken']}</td>
                                    </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='8' class='text-center text-danger'>No Record Found!</td></tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include "Includes/footer.php";?>
      </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
      $(document).ready(function () {
        $('#dataTableHover').DataTable();
      });
    </script>
  </body>
</html>
