<?php 
error_reporting(0);
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
    die("Connection failed: " . $conn[$db]->connect_error);
  }
}

$query = "SELECT tblclass.className 
    FROM tblclassteacher
    INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
    WHERE tblclassteacher.Id = '$_SESSION[userId]'";

$rs = $conn['sas_six']->query($query); // Assuming the session userId is in sas_six database
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc() ?? ['className' => ''];

//session and Term
$querey = $conn['sas_six']->query("SELECT * FROM tblsessionterm WHERE isActive ='1'");
$rwws = $querey->fetch_assoc();
$sessionTermId = $rwws['Id'];

$dateTaken = date("Y-m-d");

$qurty = $conn['sas_six']->query("SELECT * FROM tblattendance WHERE classId = '$_SESSION[classId]' AND dateTimeTaken='$dateTaken'");
$count = $qurty->num_rows;

if ($count == 0) { //if Record does not exist, insert the new record
  //insert the students record into the attendance table on page load
  $qus = $conn['sas_six']->query("SELECT * FROM tblstudents WHERE classId = '$_SESSION[classId]'");
  while ($ros = $qus->fetch_assoc()) {
    $qquery = $conn['sas_six']->query("INSERT INTO tblattendance(admissionNo, classId, sessionTermId, status, dateTimeTaken) 
    VALUES('$ros[admissionNumber]', '$_SESSION[classId]', '$sessionTermId', '0', '$dateTaken')");
  }
}

if (isset($_POST['save'])) {
  $admissionNo = $_POST['admissionNo'];
  $check = $_POST['check'];
  $N = count($admissionNo);
  $status = "";

  //check if the attendance has not been taken i.e if no record has a status of 1
  $qurty = $conn['sas_six']->query("SELECT * FROM tblattendance WHERE classId = '$_SESSION[classId]' AND dateTimeTaken='$dateTaken' AND status = '1'");
  $count = $qurty->num_rows;

  if ($count > 0) {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Attendance has been taken for today!</div>";
  } else { //update the status to 1 for the checkboxes checked
    for ($i = 0; $i < $N; $i++) {
      if (isset($check[$i])) { //the checked checkboxes
        $qquery = $conn['sas_six']->query("UPDATE tblattendance SET status='1' WHERE admissionNo = '$check[$i]'");
        if ($qquery) {
          $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Attendance Taken Successfully!</div>";
        } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
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
                            if (isset($_SESSION['classId'])) {
                              $query = "SELECT tblstudents.Id, tblstudents.admissionNumber, tblclass.className, tblclass.Id AS classId, tblstudents.firstName,
                              tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber, tblstudents.dateCreated
                              FROM tblstudents
                              INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                              WHERE tblstudents.classId = '$_SESSION[classId]'";
                              
                              $rs = $conn['sas_six']->query($query); // Assuming the session classId is in sas_six database
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
                            } else {
                              echo "
                                <tr>
                                  <td colspan='7' class='text-center'>Session variables not set!</td>
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
  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>
