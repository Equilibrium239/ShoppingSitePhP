<?php
if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user'] = $admin['username'];
    header("Location: dashboard.php"); 
    exit();
}

?>
