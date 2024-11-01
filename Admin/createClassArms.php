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
  $classId = $_POST['classId'];
  $classArmName = $_POST['classArmName'];
  $dbKey = $_POST['dbKey']; // Get the dbKey from the form

  // Check if the dbKey is set and valid
  if (isset($conn[$dbKey])) {
    $selectedConn = $conn[$dbKey]; // Initialize the selected connection

    // Check for existing class arm name
    $stmt = $selectedConn->prepare("SELECT * FROM tblclassarms WHERE classArmName = ? AND classId = ?");
    $stmt->bind_param("si", $classArmName, $classId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $statusMsg = "<div class='alert alert-danger'>This Class Arm Already Exists!</div>";
    } else {
      // Insert new class arm
      $stmt = $selectedConn->prepare("INSERT INTO tblclassarms (classId, classArmName, isAssigned) VALUES (?, ?, 0)");
      $stmt->bind_param("is", $classId, $classArmName);
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
    $stmt = $conn[$dbKey]->prepare("SELECT * FROM tblclassarms WHERE Id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    //------------UPDATE-----------------------------
    if (isset($_POST['update'])) {
      $classId = $_POST['classId'];
      $classArmName = $_POST['classArmName'];

      $stmt = $conn[$dbKey]->prepare("UPDATE tblclassarms SET classId = ?, classArmName = ? WHERE Id = ?");
      $stmt->bind_param("ssi", $classId, $classArmName, $Id);
      if ($stmt->execute()) {
        header("Location: createClassArms.php");
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
  $stmt = $conn[$dbKey]->prepare("DELETE FROM tblclassarms WHERE Id = ?");
  $stmt->bind_param("i", $Id);
  if ($stmt->execute()) {
    header("Location: createClassArms.php");
    exit();
  } else {
    $statusMsg = "<div class='alert alert-danger'>An error Occurred!</div>";
  }
  $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>

        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Class Arms</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class Arms</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class Arms</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
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
                      <div class="col-xl-6">
                        <label class="form-control-label">Class Arm Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="classArmName" value="<?php echo isset($row['classArmName']) ? $row['classArmName'] : ''; ?>" placeholder="Class Arm Name" required>
                      </div>
                    </div>
                    <?php if (isset($Id)) { ?>
                      <button type="submit" name="update" class="btn btn-warning">Update</button>
                    <?php } else { ?>
                      <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php } ?>
                  </form>
                </div>
              </div>

              <!-- All Class Arm Table -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Class Arm</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Class Name</th>
                            <th>Class Arm Name</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          foreach ($databases as $dbKey) {
                            $query = "SELECT tblclassarms.Id, tblclassarms.isAssigned, tblclass.className, tblclassarms.classArmName 
                                  FROM tblclassarms
                                  INNER JOIN tblclass ON tblclass.Id = tblclassarms.classId";
                            $rs = $conn[$dbKey]->query($query);
                            if ($rs->num_rows > 0) {
                              $sn = 0;
                              while ($rows = $rs->fetch_assoc()) {
                                $status = ($rows['isAssigned'] == '1') ? "Assigned" : "UnAssigned";
                                $sn++;
                                echo "
                                <tr>
                                  <td>$sn</td>
                                  <td>{$rows['className']}</td>
                                  <td>{$rows['classArmName']}</td>
                                  <td>$status</td>
                                  <td><a href='?action=edit&Id={$rows['Id']}&dbKey=$dbKey'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                  <td><a href='?action=delete&Id={$rows['Id']}&dbKey=$dbKey'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                </tr>";
                              }
                            } else {
                              echo "<tr><td colspan='6' class='text-center'>No Record Found in $dbKey!</td></tr>";
                            }
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

      </div>

    </div>
  </div>
  </div>
  </div>
  <?php include "Includes/footer.php"; ?>
  </div>
  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>

  <script>
    // JavaScript function to update the hidden dbKey input based on the selected class
    function updateDbKey() {
      const selectElement = document.getElementById('classId');
      const selectedOption = selectElement.options[selectElement.selectedIndex];
      const dbKey = selectedOption.getAttribute('data-dbkey');
      document.getElementById('dbKey').value = dbKey; // Update the hidden input
    }
  </script>
</body>

</html>