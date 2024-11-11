<?php
include 'Includes/dbcon.php';
session_start();

// Define the database connection variables
$host = 'localhost:5222';
$user = 'root';
$pass = '';

// Define the databases
$dbs = ['sas_six', 'sas_seven', 'sas_eight', 'sas_other'];
$conn = [];

// Create database connections
foreach ($dbs as $db) {
    $conn[$db] = new mysqli($host, $user, $pass, $db);
    if ($conn[$db]->connect_error) {
        die("Connection failed: " . $conn[$db]->connect_error);
    }
}

if (isset($_POST['login'])) {
    $userType = $_POST['userType'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Use plain text for testing

    // Prepare query based on user type
    if ($userType == "Administrator") {
        foreach ($dbs as $db) {
            $dbConnection = $conn[$db];
            $query = $dbConnection->prepare("SELECT * FROM tbladmin WHERE emailAddress = ?");
            if ($query) {
                $query->bind_param("s", $username);
                $query->execute();
                $result = $query->get_result();

                if ($result->num_rows > 0) {
                    $rows = $result->fetch_assoc();

                    // Use password hashing in production
                    if ($password === $rows['password']) { // Consider using password_verify() here
                        $_SESSION['userId'] = $rows['Id'];
                        $_SESSION['firstName'] = $rows['firstName'];
                        $_SESSION['lastName'] = $rows['lastName'];
                        $_SESSION['emailAddress'] = $rows['emailAddress'];

                        // Redirect based on user type
                        $redirectLocation = "Admin/index.php";
                        echo "<script type='text/javascript'>window.location = ('$redirectLocation')</script>";
                        exit;
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
                    }
                }
                $query->close();
            }
        }
    } else if ($userType == "ClassTeacher") {
        foreach ($dbs as $db) {
            $dbConnection = $conn[$db];
            $query = $dbConnection->prepare("SELECT * FROM tblclassteacher WHERE emailAddress = ?");
            if ($query) {
                $query->bind_param("s", $username);
                $query->execute();
                $result = $query->get_result();

                if ($result->num_rows > 0) {
                    $rows = $result->fetch_assoc();

                    // Use password hashing in production
                    if ($password === $rows['password']) { // Consider using password_verify() here
                        $_SESSION['userId'] = $rows['Id'];
                        $_SESSION['firstName'] = $rows['firstName'];
                        $_SESSION['lastName'] = $rows['lastName'];
                        $_SESSION['emailAddress'] = $rows['emailAddress'];

                        // Redirect based on user type
                        $redirectLocation = "ClassTeacher/index.php";
                        echo "<script type='text/javascript'>window.location = ('$redirectLocation')</script>";
                        exit;
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
                    }
                }
                $query->close();
            }
        }
        echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
    } else if ($userType == "Student") {
        foreach ($dbs as $db) {
            $dbConnection = $conn[$db];
            $query = $dbConnection->prepare("SELECT * FROM tblstudents WHERE admissionNumber = ?");
            if ($query) {
                $query->bind_param("s", $username);
                $query->execute();
                $result = $query->get_result();

                if ($result->num_rows > 0) {
                    $rows = $result->fetch_assoc();

                    // Use password hashing in production
                    if ($password === $rows['password']) { // Consider using password_verify() here
                        $_SESSION['userId'] = $rows['Id'];
                        $_SESSION['firstName'] = $rows['firstName'];
                        $_SESSION['lastName'] = $rows['lastName'];
                        $_SESSION['admissionNumber'] = $rows['admissionNumber'];

                        // Redirect based on user type
                        $redirectLocation = "Student/index.php";
                        echo "<script type='text/javascript'>window.location = ('$redirectLocation')</script>";
                        exit;
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
                    }
                }
                $query->close();
            }
        }
        echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Invalid User Role!</div>";
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
    <title>SAS - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpe00g');">
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <h5 align="center">STUDENT ATTENDANCE SYSTEM</h5>
                                    <div class="text-center">
                                        <img src="img/logo/attnlg.jpg" style="width:100px;height:100px">
                                        <br><br>
                                        <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <select required name="userType" class="form-control mb-3">
                                                <option value="">--Select User Roles--</option>
                                                <option value="Administrator">Administrator</option>
                                                <option value="ClassTeacher">ClassTeacher</option>
                                                <option value="Student">Student</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success btn-block" value="Login" name="login" />
                                        </div>
                                    </form>
                                    <div class="text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>

</html>