<?php
error_reporting(E_ALL); // Enable error reporting for debugging
include '../Includes/dbcon.php';
include '../Includes/session.php';

$dbName = "sas_six"; // Dynamically assign based on your specific needs
$selectedConn = $conn[$dbName]; // Choose the specific database connection

$statusMsg = ""; // Initialize the status message variable

// Combine classes from multiple databases
$allClasses = [];
$databases = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];

foreach ($databases as $dbKey) {
  $query = "SELECT c.*, ca.classArmName, ca.isAssigned FROM tblclass c LEFT JOIN tblclassarms ca ON c.Id = ca.classId";
  $result = $conn[$dbKey]->query($query);

  while ($row = $result->fetch_assoc()) {
    $row['dbKey'] = $dbKey; // Add the database key to each row
    $allClasses[] = $row;
  }
}

//------------------------SAVE--------------------------------------------------
if (isset($_POST['save'])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $emailAddress = $_POST['emailAddress'];
  $phoneNo = $_POST['phoneNo'];
  $classId = $_POST['classId'];
  $dateCreated = date("Y-m-d");
  $dbKey = $_POST['dbKey']; // Get the dbKey from the form

  // Check if the dbKey is set and valid
  if (isset($conn[$dbKey])) {
    $selectedConn = $conn[$dbKey]; // Initialize the selected connection

    // Check for existing email address
    $stmt = $selectedConn->prepare("SELECT * FROM tblclassteacher WHERE emailAddress = ?");
    $stmt->bind_param("s", $emailAddress);
    $stmt->execute();
    $result = $stmt->get_result();

    $sampPass = "pass123";

    if ($result->num_rows > 0) {
      $statusMsg = "<div class='alert alert-danger'>This Email Address Already Exists!</div>";
    } else {
      // Insert new class teacher
      $stmt = $selectedConn->prepare("INSERT INTO tblclassteacher (firstName, lastName, emailAddress, password, phoneNo, classId, dateCreated) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssis", $firstName, $lastName, $emailAddress, $sampPass, $phoneNo, $classId, $dateCreated);
      if ($stmt->execute()) {
        $statusMsg = "<div class='alert alert-success'>Created Successfully!</div>";
      } else {
        $statusMsg = "<div class='alert alert-danger'>An error Occurred: " . $stmt->error . "</div>"; // Show the error
      }
    }
    $stmt->close();
  } else {
    $statusMsg = "<div class='alert alert-danger'>Invalid database selection.</div>";
  }
}

//---------------------------------------EDIT-------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];
  $dbKey = $_GET['dbKey'];

  // Check if the dbKey is set and valid
  if (isset($conn[$dbKey])) {
    $stmt = $conn[$dbKey]->prepare("SELECT * FROM tblclassteacher WHERE Id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    //------------UPDATE-----------------------------
    if (isset($_POST['update'])) {
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $emailAddress = $_POST['emailAddress'];
      $phoneNo = $_POST['phoneNo'];
      $classId = $_POST['classId'];
      $dateCreated = date("Y-m-d");

      $stmt = $conn[$dbKey]->prepare("UPDATE tblclassteacher SET firstName = ?, lastName = ?, emailAddress = ?, phoneNo = ?, classId = ? WHERE Id = ?");
      $stmt->bind_param("ssssii", $firstName, $lastName, $emailAddress, $phoneNo, $classId, $Id);
      if ($stmt->execute()) {
        header("Location: createClassTeacher.php");
        exit();
      } else {
        $statusMsg = "<div class='alert alert-danger'>An error Occurred: " . $stmt->error . "</div>"; // Show the error
      }
      $stmt->close();
    }
  } else {
    $statusMsg = "<div class='alert alert-danger'>Invalid database selection.</div>";
  }
}

//--------------------------------DELETE------------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];
  $dbKey = $_GET['dbKey'];

  // Check if the dbKey is set and valid
  if (isset($conn[$dbKey])) {
    $selectedConn = $conn[$dbKey];

    // Debugging output to confirm parameters
    echo "Attempting to delete Id: $Id, DB Key: $dbKey<br>";

    // Check if the record exists before deletion
    $checkStmt = $selectedConn->prepare("SELECT * FROM tblclassteacher WHERE Id = ?");
    $checkStmt->bind_param("i", $Id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows == 0) {
      $statusMsg = "<div class='alert alert-danger'>No record found for Id: $Id.</div>";
      exit();
    }
    $checkStmt->close();

    // Start a transaction
    $selectedConn->begin_transaction();

    try {
      // Delete the class teacher
      $stmt = $selectedConn->prepare("DELETE FROM tblclassteacher WHERE Id = ?");
      $stmt->bind_param("i", $Id);
      if (!$stmt->execute()) {
        throw new Exception("Error deleting class teacher: " . $stmt->error);
      }
      $stmt->close();

      // Commit the transaction
      $selectedConn->commit();
      header("Location: createClassTeacher.php");
      exit();
    } catch (Exception $e) {
      // Rollback the transaction on error
      $selectedConn->rollback();
      $statusMsg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>"; // Show the error message
    }
  } else {
    $statusMsg = "<div class='alert alert-danger'>Invalid database selection.</div>";
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



  <script>
    function classArmDropdown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "ajaxClassArms.php?cid=" + str, true);
        xmlhttp.send();
      }
    }

    function updateDbKey() {
      var classIdSelect = document.getElementById("classId");
      var selectedOption = classIdSelect.options[classIdSelect.selectedIndex];
      var dbKey = selectedOption.getAttribute("data-dbkey");
      document.getElementById("dbKey").value = dbKey;
    }
  </script>
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
            <h1 class="h3 mb-0 text-gray-800">Create Class Teachers</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class Teachers</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class Teachers</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="firstName" value="<?php echo isset($row['firstName']) ? $row['firstName'] : ''; ?>" id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="lastName" value="<?php echo isset($row['lastName']) ? $row['lastName'] : ''; ?>" id="exampleInputFirstName">
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="emailAddress" value="<?php echo isset($row['emailAddress']) ? $row['emailAddress'] : ''; ?>" id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="phoneNo" value="<?php echo isset($row['phoneNo']) ? $row['phoneNo'] : ''; ?>" id="exampleInputFirstName">
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
                      <h6 class="m-0 font-weight-bold text-primary">All Class Teachers</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Phone No</th>
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
                                    tblclassteacher.Id, 
                                    tblclass.className, 
                                    tblclassteacher.firstName,
                                    tblclassteacher.lastName, 
                                    tblclassteacher.emailAddress, 
                                    tblclassteacher.phoneNo, 
                                    tblclassteacher.dateCreated
                                FROM tblclassteacher
                                INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
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
                                            <td>{$rows['emailAddress']}</td>
                                            <td>{$rows['phoneNo']}</td>
                                            <td>{$rows['className']}</td>
                                            <td>{$rows['dateCreated']}</td>
                                            <td><a href='?action=edit&Id={$rows['Id']}&dbKey=$dbKey'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                            <td><a href='?action=delete&Id={$rows['Id']}&dbKey=$dbKey' onclick='return confirm(\"Are you sure you want to delete this record?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                        </tr>";
                                }
                              } else {
                                echo "<tr><td colspan='9' class='text-center'>No Record Found in $dbKey!</td></tr>"; // Adjusted colspan to 9
                              }
                              $stmt->close();
                            }
                          }
                          if ($sn == 0) {
                            echo "<tr><td colspan='9' class='text-center'>No Record Found!</td></tr>"; // Adjusted colspan to 9
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!--Row-->


              <!-- Documentation Link -->
              <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

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
      </script>
</body>

</html>
