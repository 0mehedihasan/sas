<?php 
session_start();
include '../Includes/dbcon.php';
include '../Includes/session.php';

$host = 'localhost:5222';
$user = 'root';
$pass = '';
$dbs = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];
$conn = [];

foreach ($dbs as $db) {
    $conn[$db] = new mysqli($host, $user, $pass, $db);
    if ($conn[$db]->connect_error) {
        die("Connection failed for $db: " . $conn[$db]->connect_error);
    }
}

$statusMsg = ""; 
$attendanceRecords = [];
$searchQuery = '';

// Fetch class information for the logged-in teacher
$classId = null;
foreach ($dbs as $dbKey) {
    $query = "SELECT tblclass.className, tblclassteacher.classId 
              FROM tblclassteacher
              INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
              WHERE tblclassteacher.emailAddress = '".$_SESSION['emailAddress']."'";
    $rs = $conn[$dbKey]->query($query);
    if ($rs && $rs->num_rows > 0) {
        $rrw = $rs->fetch_assoc();
        $classId = $rrw['classId'];
        break;
    }
}

if ($classId && isset($_POST['search'])) {
    $admissionNumber = $_POST['admissionNumber'] ?? '';
    $studentName = $_POST['studentName'] ?? '';
    
    // Build search conditions based on provided input
    $conditions = [];
    if (!empty($admissionNumber)) {
        $conditions[] = "tblstudents.admissionNumber = '$admissionNumber'";
    }
    if (!empty($studentName)) {
        $conditions[] = "(tblstudents.firstName LIKE '%$studentName%' OR tblstudents.lastName LIKE '%$studentName%')";
    }
    
    if (count($conditions) > 0) {
        $searchQuery = "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
                        tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                        FROM tblattendance
                        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                        WHERE tblattendance.classId = '$classId' AND " . implode(' AND ', $conditions) . "
                        ORDER BY tblattendance.dateTimeTaken DESC";
        
        $rs = $conn[$dbKey]->query($searchQuery);

        if (!$rs) {
            die("Query failed: " . $conn[$dbKey]->error);
        }

        $attendanceRecords = $rs->fetch_all(MYSQLI_ASSOC);
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
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <?php include "Includes/sidebar.php";?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
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
                  <h6 class="m-0 font-weight-bold text-primary">Search for a Student</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row">
                      <div class="col-md-6">
                        <label for="admissionNumber">Admission Number</label>
                        <input type="text" class="form-control" id="admissionNumber" name="admissionNumber" placeholder="Enter Admission Number">
                      </div>
                      <div class="col-md-6">
                        <label for="studentName">Student Name</label>
                        <input type="text" class="form-control" id="studentName" name="studentName" placeholder="Enter Student Name">
                      </div>
                    </div>
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
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
                          <?php if (isset($attendanceRecords) && count($attendanceRecords) > 0): ?>
                            <?php foreach ($attendanceRecords as $i => $record): ?>
                              <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $record['firstName'] ?></td>
                                <td><?= $record['lastName'] ?></td>
                                <td><?= $record['otherName'] ?></td>
                                <td><?= $record['admissionNumber'] ?></td>
                                <td><?= $record['className'] ?></td>
                                <td><?= $record['status'] == '1' ? 'Present' : 'Absent' ?></td>
                                <td><?= $record['dateTimeTaken'] ?></td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <tr>
                              <td colspan="8" class="text-center">No Attendance Records Found</td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
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
</body>
</html>
