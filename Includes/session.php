<?php
// Start the session only if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userId'])) {
    echo "<script type=\"text/javascript\">
    window.location = (\"../index.php\");
    </script>";
}

// Uncomment to enforce session expiry after 30 minutes
// $expiry = 1800;
// if (isset($_SESSION['LAST']) && (time() - $_SESSION['LAST'] > $expiry)) {
//     session_unset();
//     session_destroy();
//     echo "<script type=\"text/javascript\">
//           window.location = (\"../index.php\");
//           </script>";
// }
// $_SESSION['LAST'] = time();
?>
