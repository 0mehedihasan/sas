<?php 
session_start();
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Check if the teacher is logged in
if (!isset($_SESSION['emailAddress'])) {
  header("Location: ../login.php");
  exit();
}

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
  die("Connection failed to $db: " . $conn[$db]->connect_error);
  }
}

$statusMsg = ""; // Initialize the status message variable

// Fetch class name for the class teacher from multiple databases
$rrw = ['className' => ''];
$classId = null;
$dbKey = null;
foreach ($dbs as $db) {
  $query = "SELECT tblclass.className, tblclassteacher.classId 
      FROM tblclassteacher
      INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
      WHERE tblclassteacher.emailAddress = '".$_SESSION['emailAddress']."'";
  $rs = $conn[$db]->query($query);
  if (!$rs) {
  die("Query failed for $db: " . $conn[$db]->error);
  }
  if ($rs && $rs->num_rows > 0) {
  $rrw = $rs->fetch_assoc();
  $classId = $rrw['classId'];
  $dbKey = $db;
  break;
  }
}


$dateTaken = date("Y-m-d");

if ($classId) {
  $dbKey = null;
  foreach ($dbs as $key => $db) {
  $query = "SELECT Id FROM tblclass WHERE Id = '$classId'";
  $result = $conn[$db]->query($query);
  if ($result && $result->num_rows > 0) {
    $dbKey = $db;
    break;
  }
  }
  $query = "SELECT * FROM tblattendance WHERE classId = '$classId' AND dateTimeTaken='$dateTaken'";
  $qurty = $conn[$dbKey]->query($query);
  $count = $qurty->num_rows;

  if ($count == 0) { //if Record does not exist, insert the new record
  //insert the students record into the attendance table on page load
  $query = "SELECT * FROM tblstudents WHERE classId = '$classId'";
  $qus = $conn[$dbKey]->query($query);
  while ($ros = $qus->fetch_assoc()) {
    $query = "INSERT INTO tblattendance(admissionNo, classId, status, dateTimeTaken) 
        VALUES('".$ros['admissionNumber']."', '$classId','0', '$dateTaken')";
    $conn[$dbKey]->query($query);
  }
  }

  if (isset($_POST['save'])) {
  $admissionNo = $_POST['admissionNo'];
  $check = $_POST['check'];
  $N = count($admissionNo);

  //check if the attendance has not been taken i.e if no record has a status of 1
  $query = "SELECT * FROM tblattendance WHERE classId = '$classId' AND dateTimeTaken='$dateTaken' AND status = '1'";
  $qurty = $conn[$dbKey]->query($query);
  $count = $qurty->num_rows;

  if ($count > 0) {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Attendance has been taken for today!</div>";
  } else { //update the status to 1 for the checkboxes checked
    for ($i = 0; $i < $N; $i++) {
    if (isset($check[$i])) { //the checked checkboxes
      $query = "UPDATE tblattendance SET status='1' WHERE admissionNo = '".$check[$i]."'";
      if ($conn[$dbKey]->query($query)) {
      $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Attendance Taken Successfully!</div>";
      } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
    }
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
      <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date : <?php echo date("m-d-Y");?>)</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
      </ol>
      </div>

      <div class="row">
      <div class="col-lg-12">
        <!-- Form Basic -->

        <!-- Input Group -->
        <form method="post">
        <div class="row">
          <div class="col-lg-12">
          <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Student in (<?php echo $rrw['className'];?>) Class</h6>
            <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the checkboxes besides each student to take attendance!</i></h6>
            </div>
            <div class="table-responsive p-3">
            <?php echo $statusMsg; ?>
            <table class="table align-items-center table-flush table-hover">
              <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Other Name</th>
                <th>Admission No</th>
                <th>Class</th>
                <th>Check</th>
              </tr>
              </thead>
              
              <tbody>
              <?php
                $query = "SELECT tblstudents.Id, tblstudents.admissionNumber, tblclass.className, tblclass.Id AS classId, tblstudents.firstName,
                tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber, tblstudents.dateCreated
                FROM tblstudents
                INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                WHERE tblstudents.classId = '$classId'";
                
                $rs = $conn[$dbKey]->query($query);
                $num = $rs->num_rows;
                $sn = 0;
                if ($num > 0) { 
                while ($rows = $rs->fetch_assoc()) {
                  $sn++;
                  echo "
                  <tr>
                    <td>".$sn."</td>
                    <td>".$rows['firstName']."</td>
                    <td>".$rows['lastName']."</td>
                    <td>".$rows['otherName']."</td>
                    <td>".$rows['admissionNumber']."</td>
                    <td>".$rows['className']."</td>
                    <td><input name='check[]' type='checkbox' value=".$rows['admissionNumber']." class='form-control'></td>
                  </tr>";
                  echo "<input name='admissionNo[]' value=".$rows['admissionNumber']." type='hidden' class='form-control'>";
                }
                } else {
                echo "
                  <tr>
                  <td colspan='7' class='text-center'>No Record Found!</td>
                  </tr>";
                }
              ?>
              </tbody>
            </table>
            <br>
            <button type="submit" name="save" class="btn btn-primary">Take Attendance</button>
            </form>
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
    <?php include "Includes/footer.php";?>
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
