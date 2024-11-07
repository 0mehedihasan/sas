<?php 
error_reporting(0);
session_start();
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Check if the emailAddress session variable is set
if (!isset($_SESSION['emailAddress'])) {
  die("Session variable 'emailAddress' is not set. Please log in.");
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
    die("Connection failed: " . $conn[$db]->connect_error);
  }
}

$statusMsg = ""; // Initialize the status message variable

// Fetch class name for the class teacher from multiple databases
$rrw = ['className' => ''];
$classId = null;
foreach ($dbs as $dbKey) {
  $query = "SELECT tblclass.className, tblclassteacher.classId 
            FROM tblclassteacher
            INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
            WHERE tblclassteacher.emailAddress = '$_SESSION[emailAddress]'";
  $rs = $conn[$dbKey]->query($query);
  if ($rs && $rs->num_rows > 0) {
    $rrw = $rs->fetch_assoc();
    $classId = $rrw['classId'];
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
            <h1 class="h3 mb-0 text-gray-800">All Student in (<?php echo $rrw['className'];?>) Class</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Student In Class</h6>
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
                          </tr>
                        </thead>
                        
                        <tbody>
                          <?php
                          if ($classId) {
                            $query = "SELECT tblstudents.Id, tblclass.className, tblstudents.firstName,
                            tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber, tblstudents.dateCreated
                            FROM tblstudents
                            INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                            WHERE tblstudents.classId = '$classId'";
                                                      
                            $rs = $conn[$dbKey]->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            if($num > 0) { 
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
                                  </tr>";
                              }
                            } else {
                              echo "
                                <tr>
                                  <td colspan='6' class='text-center'>No Record Found!</td>
                                </tr>";
                            }
                          } else {
                            echo "
                              <tr>
                                <td colspan='6' class='text-center'>Session variables not set!</td>
                              </tr>";
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
