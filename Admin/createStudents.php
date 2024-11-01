<?php 
error_reporting(E_ALL); // Enable error reporting for debugging
include '../Includes/dbcon.php';
include '../Includes/session.php';

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

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $otherName = $_POST['otherName'];
  $admissionNumber = $_POST['admissionNumber'];
  $classId = $_POST['classId'];
  $dateCreated = date("Y-m-d");
  $dbKey = $_POST['dbKey']; // Get the dbKey from the form

  if (isset($conn[$dbKey])) {
    $selectedConn = $conn[$dbKey];

    $query = $selectedConn->prepare("SELECT * FROM tblstudents WHERE admissionNumber = ?");
    $query->bind_param("s", $admissionNumber);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Admission Number Already Exists!</div>";
    } else {
      $query = $selectedConn->prepare("INSERT INTO tblstudents (firstName, lastName, otherName, admissionNumber, password, classId, dateCreated) VALUES (?, ?, ?, ?, '12345', ?, ?)");
      $query->bind_param("ssssss", $firstName, $lastName, $otherName, $admissionNumber, $classId, $dateCreated);

      if ($query->execute()) {
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
      } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid database selection.</div>";
  }
}

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];
  $dbKey = $_GET['dbKey'];

  if (isset($conn[$dbKey])) {
    $query = $conn[$dbKey]->prepare("SELECT * FROM tblstudents WHERE Id = ?");
    $query->bind_param("i", $Id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    if (isset($_POST['update'])) {
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $otherName = $_POST['otherName'];
      $admissionNumber = $_POST['admissionNumber'];
      $classId = $_POST['classId'];
      $dateCreated = date("Y-m-d");

      $query = $conn[$dbKey]->prepare("UPDATE tblstudents SET firstName = ?, lastName = ?, otherName = ?, admissionNumber = ?, password = '12345', classId = ? WHERE Id = ?");
      $query->bind_param("sssssi", $firstName, $lastName, $otherName, $admissionNumber, $classId, $Id);

      if ($query->execute()) {
        echo "<script type='text/javascript'>window.location = ('createStudents.php')</script>";
      } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid database selection.</div>";
  }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];
  $dbKey = $_GET['dbKey'];

  if (isset($conn[$dbKey])) {
    $query = $conn[$dbKey]->prepare("DELETE FROM tblstudents WHERE Id = ?");
    $query->bind_param("i", $Id);

    if ($query->execute()) {
      echo "<script type='text/javascript'>window.location = ('createStudents.php')</script>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid database selection.</div>";
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
  <?php include 'includes/title.php'; ?>
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
            <h1 class="h3 mb-0 text-gray-800">Create Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Students</li>
            </ol>
          </div>

          <?php if (isset($statusMsg)) echo $statusMsg; ?>
          <div class="col-lg-12">
            <!-- Form Basic -->
            <div class="card mb-4">
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Create Students</h6>
              </div>
              <div class="card-body">
                <form method="post">
                  <div class="form-group row mb-3">
                    <div class="col-xl-6">
                      <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="firstName" value="<?php echo isset($row['firstName']) ? $row['firstName'] : ''; ?>" id="exampleInputFirstName">
                    </div>
                    <div class="col-xl-6">
                      <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="lastName" value="<?php echo isset($row['lastName']) ? $row['lastName'] : ''; ?>" id="exampleInputFirstName">
                    </div>
                  </div>
                  <div class="form-group row mb-3">
                    <div class="col-xl-6">
                      <label class="form-control-label">Other Name<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="otherName" value="<?php echo isset($row['otherName']) ? $row['otherName'] : ''; ?>" id="exampleInputFirstName">
                    </div>
                    <div class="col-xl-6">
                      <label class="form-control-label">Admission Number<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" required name="admissionNumber" value="<?php echo isset($row['admissionNumber']) ? $row['admissionNumber'] : ''; ?>" id="exampleInputFirstName">
                    </div>
                  </div>
                  <div class="form-group row mb-3">
                    <div class="col-xl-6">
                      <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                      <select required name="classId" class="form-control mb-3" id="classId" onchange="updateDbKey()">
                        <option value="">--Select Class--</option>
                        <?php
                        foreach ($allClasses as $class) {
                          echo '<option value="' . $class['Id'] . '" data-dbkey="' . $class['dbKey'] . '">' . $class['className'] . '</option>';
                        }
                        ?>
                      </select>
                      <input type="hidden" name="dbKey" id="dbKey">
                    </div>
                  </div>
                  <?php
                  if (isset($Id)) {
                  ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php
                  } else {
                  ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                  <?php
                  }
                  ?>
                </form>
              </div>
            </div>

            <!-- Input Group -->
            <div class="row">
              <div class="col-lg-12">
                <div class="card mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">All Students</h6>
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
                          <th>Date Created</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $sn = 0;
                        foreach ($databases as $dbKey) {
                          $query = "
                            SELECT 
                              tblstudents.Id, 
                              tblclass.className, 
                              tblstudents.firstName,
                              tblstudents.lastName, 
                              tblstudents.otherName, 
                              tblstudents.admissionNumber, 
                              tblstudents.dateCreated
                            FROM tblstudents
                            INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                          ";

                          if ($stmt = $conn[$dbKey]->prepare($query)) {
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                              while ($rows = $result->fetch_assoc()) {
                                $sn++;
                                echo "
                                  <tr>
                                    <td>$sn</td>
                                    <td>{$rows['firstName']}</td>
                                    <td>{$rows['lastName']}</td>
                                    <td>{$rows['otherName']}</td>
                                    <td>{$rows['admissionNumber']}</td>
                                    <td>{$rows['className']}</td>
                                    <td>{$rows['dateCreated']}</td>
                                    <td><a href='?action=edit&Id={$rows['Id']}&dbKey=$dbKey'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                    <td><a href='?action=delete&Id={$rows['Id']}&dbKey=$dbKey' onclick='return confirm(\"Are you sure you want to delete this record?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                  </tr>";
                              }
                            } else {
                              echo "<tr><td colspan='9' class='text-center'>No Record Found in $dbKey!</td></tr>";
                            }
                            $stmt->close();
                          }
                        }
                        if ($sn == 0) {
                          echo "<tr><td colspan='9' class='text-center'>No Record Found!</td></tr>";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!--Row-->
          </div>
          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include "Includes/footer.php"; ?>
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
      $(document).ready(function() {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });

      function updateDbKey() {
        var classIdSelect = document.getElementById("classId");
        var selectedOption = classIdSelect.options[classIdSelect.selectedIndex];
        var dbKey = selectedOption.getAttribute("data-dbkey");
        document.getElementById("dbKey").value = dbKey;
      }
    </script>
</body>

</html>
