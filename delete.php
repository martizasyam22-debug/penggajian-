<?php
require_once 'auth.php';
requireAdmin();

include("connection.php");

if (isset($_GET['employee_id'])) {
    $employee_id = mysqli_real_escape_string($conn, $_GET['employee_id']);
    
    // Delete user account first (due to foreign key)
    mysqli_query($conn, "DELETE FROM users WHERE employee_id = $employee_id");
    
    // Delete salary slips
    mysqli_query($conn, "DELETE FROM salary_slips WHERE employee_id = $employee_id");
    
    // Delete employee
    $result = mysqli_query($conn, "DELETE FROM employee WHERE employee_id = $employee_id");
    
    if ($result) {
        header("Location: payroll.php#employees");
    } else {
        echo "Error deleting employee: " . mysqli_error($conn);
    }
} else {
    header("Location: payroll.php");
}

mysqli_close($conn);
?>
