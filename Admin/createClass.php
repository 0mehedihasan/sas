<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {

  $className = $_POST['className'];

  // Determine which database connection to use based on the class name
  switch ($className) {
    case '6':
      $dbConnection = $conn['sas_six'];
      break;
    case '7':
      $dbConnection = $conn['sas_seven'];
      break;
    case '8':
      $dbConnection = $conn['sas_eight'];
      break;
    case '9':
    case '10':
    case '11':
    case '12':
      $dbConnection = $conn['sas_other'];
      break;
    default:
      $dbConnection = $conn['sas_other'];
      break;
  }

  // Check if the class already exists in the determined database
  $query = mysqli_query($dbConnection, "SELECT * FROM tblclass WHERE className ='$className'");
  $ret = mysqli_fetch_array($query);

  if ($ret > 0) {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Class Already Exists!</div>";
  } else {
    // Insert into the determined database
    $query = mysqli_query($dbConnection, "INSERT INTO tblclass(className) VALUES('$className')");

    if ($query) {
      $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];

  // For edit, assume the record exists in 'sas_six' and use that connection
  $query = mysqli_query($conn['sas_six'], "SELECT * FROM tblclass WHERE Id ='$Id'");
  $row = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

  if (isset($_POST['update'])) {
    $className = $_POST['className'];

    // Determine the correct database connection for the updated class name
    switch ($className) {
      case '6':
        $dbConnection = $conn['sas_six'];
        break;
      case '7':
        $dbConnection = $conn['sas_seven'];
        break;
      case '8':
        $dbConnection = $conn['sas_eight'];
        break;
      case '9':
      case '10':
      case '11':
      case '12':
        $dbConnection = $conn['sas_other'];
        break;
      default:
        $dbConnection = $conn['sas_other'];
        break;
    }

    // Update the class name in the determined database
    $query = mysqli_query($dbConnection, "UPDATE tblclass SET className='$className' WHERE Id='$Id'");

    if ($query) {
      echo "<script type = \"text/javascript\">
                window.location = (\"createClass.php\")
                </script>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//--------------------------------DELETE------------------------------------------------------------------

// Check if delete action is requested
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['dbKey'])) {
  $Id = $_GET['Id'];
  $dbKey = $_GET['dbKey']; // Get the dbKey from the URL to identify the database

  // Determine the correct database connection based on dbKey
  switch ($dbKey) {
    case 'sas_six':
      $dbConnection = $conn['sas_six'];
      break;
    case 'sas_seven':
      $dbConnection = $conn['sas_seven'];
      break;
    case 'sas_eight':
      $dbConnection = $conn['sas_eight'];
      break;
    case 'sas_other':
      $dbConnection = $conn['sas_other'];
      break;
    default:
      $dbConnection = null; // Invalid database key
  }

  // Proceed if a valid database connection was found
  if ($dbConnection) {
    $query = mysqli_query($dbConnection, "DELETE FROM tblclass WHERE Id='$Id'");
    
    if ($query) {
      echo "<script type=\"text/javascript\">
              alert('Record deleted successfully');
              window.location = ('createClass.php');
            </script>";
    } else {
      echo "<div class='alert alert-danger'>An error occurred while deleting the record!</div>";
    }
  } else {
    echo "<div class='alert alert-danger'>Invalid database selected!</div>";
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
    <?php include "Includes/sidebar.php"; ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include "Includes/topbar.php"; ?>
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Class</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Class Name<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="className" id="classSelect" required>
                          <option value="">Select Class</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                        </select>
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

              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Classes</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Class Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <!-- Replace the current code inside the <tbody> section with the following: -->

                        <tbody>
                          <?php
                          $allClasses = [];

                          // Query each database and combine the results
                          $databases = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];

                          foreach ($databases as $dbKey) {
                            $query = "SELECT * FROM tblclass";
                            $result = $conn[$dbKey]->query($query);

                            while ($row = $result->fetch_assoc()) {
                              $row['dbKey'] = $dbKey; // Add the database key to each row
                              $allClasses[] = $row;
                            }
                          }

                          // Display combined results
                          $sn = 0;
                          foreach ($allClasses as $class) {
                            $sn++;
                            echo "
      <tr>
        <td>$sn</td>
        <td>{$class['className']}</td>
        <td><a href='?action=edit&Id={$class['Id']}&dbKey={$class['dbKey']}'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
        <td><a href='?action=delete&Id={$class['Id']}&dbKey={$class['dbKey']}'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
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
        </div>
        <?php include "Includes/footer.php"; ?>
      </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  </div>
</body>

</html>