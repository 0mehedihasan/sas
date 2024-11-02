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

// Ensure session variables are set
if (!isset($_SESSION['userId']) || !isset($_SESSION['classId'])) {
  die("Session variables not set. Please log in again.");
}

// Query to get the class name for the logged-in user
$query = "SELECT tblclass.className 
          FROM tblclassteacher
          INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
          WHERE tblclassteacher.Id = '{$_SESSION['userId']}'";
$rs = $conn['sas_six']->query($query); // Assuming the session userId is in sas_six database

if (!$rs) {
  die("Query failed: " . $conn['sas_six']->error);
}

if ($rs->num_rows == 0) {
  die("No class found for the logged-in user.");
}

$classData = $rs->fetch_assoc();
$className = $classData['className'] ?? '';

// Date taken for attendance
$dateTaken = date("Y-m-d");

// Check if attendance record exists for today
$qurty = $conn['sas_six']->query("SELECT * FROM tblattendance WHERE classId = '{$_SESSION['classId']}' AND dateTimeTaken='$dateTaken'");

if (!$qurty) {
  die("Query failed: " . $conn['sas_six']->error);
}

if ($qurty->num_rows == 0) {
  // Insert attendance records if none exist for today
  $qus = $conn['sas_six']->query("SELECT * FROM tblstudents WHERE classId = '{$_SESSION['classId']}'");
  if (!$qus) {
    die("Query failed: " . $conn['sas_six']->error);
  }
  while ($student = $qus->fetch_assoc()) {
    $conn['sas_six']->query("INSERT INTO tblattendance(admissionNo, classId, status, dateTimeTaken) 
                             VALUES('{$student['admissionNumber']}', '{$_SESSION['classId']}', '0', '$dateTaken')");
  }
}

// Handling form submission to view attendance
if (isset($_POST['view'])) {
  $admissionNumber = $_POST['admissionNumber'];
  $type = $_POST['type'];

  if ($type == "1") { // All Attendance
    $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
              tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
              FROM tblattendance
              INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
              INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
              WHERE tblattendance.admissionNo = '$admissionNumber' AND tblattendance.classId = '{$_SESSION['classId']}'";
  } elseif ($type == "2") { // Single Date Attendance
    $singleDate = $_POST['singleDate'] ?? '';
    $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
              tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
              FROM tblattendance
              INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
              INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
              WHERE tblattendance.dateTimeTaken = '$singleDate' AND tblattendance.admissionNo = '$admissionNumber' AND tblattendance.classId = '{$_SESSION['classId']}'";
  } elseif ($type == "3") { // Date Range Attendance
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $query = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
              tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
              FROM tblattendance
              INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
              INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
              WHERE tblattendance.dateTimeTaken BETWEEN '$fromDate' AND '$toDate' AND tblattendance.admissionNo = '$admissionNumber' AND tblattendance.classId = '{$_SESSION['classId']}'";
  }

  $rs = $conn['sas_six']->query($query);
  if (!$rs) {
    die("Query failed: " . $conn['sas_six']->error);
  }

  $attendanceRecords = $rs->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php";?>

        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Student Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Student Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Student Attendance</h6>
                  <?php echo $statusMsg ?? ''; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Student<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry= "SELECT * FROM tblstudents WHERE classId = '{$_SESSION['classId']}' ORDER BY firstName ASC";
                        $result = $conn['sas_six']->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo '<select required name="admissionNumber" class="form-control mb-3">';
                          echo '<option value="">--Select Student--</option>';
                          while ($rows = $result->fetch_assoc()){
                            echo '<option value="'.$rows['admissionNumber'].'">'.$rows['firstName'].' '.$rows['lastName'].'</option>';
                          }
                          echo '</select>';
                        }
                        ?>  
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Type<span class="text-danger ml-2">*</span></label>
                        <select required name="type" onchange="typeDropDown(this.value)" class="form-control mb-3">
                          <option value="">--Select--</option>
                          <option value="1">All</option>
                          <option value="2">By Single Date</option>
                          <option value="3">By Date Range</option>
                        </select>
                      </div>
                    </div>
                    <div id="txtHint"></div>
                    <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
                  </form>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Class Attendance</h6>
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
