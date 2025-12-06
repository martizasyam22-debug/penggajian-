<?php
require_once 'auth.php';

// Logout user
logoutUser();

// Redirect to login page
header('Location: index.php');
exit();
?>
