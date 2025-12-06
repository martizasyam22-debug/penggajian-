<?php
session_start();

// Include database connection
require_once 'connection.php';

/**
 * Authenticate user with username and password
 * @param string $username
 * @param string $password
 * @return array|false User data on success, false on failure
 */
function authenticateUser($username, $password) {
    global $conn;
    
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT u.*, e.name as employee_name 
              FROM users u 
              LEFT JOIN employee e ON u.employee_id = e.employee_id 
              WHERE u.username = '$username'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password - support both hashed and plain text
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            return $user;
        }
    }
    
    return false;
}

/**
 * Login user and create session
 * @param array $user User data from database
 */
function loginUser($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['employee_id'] = $user['employee_id'];
    $_SESSION['employee_name'] = $user['employee_name'] ?? 'Admin';
    $_SESSION['logged_in'] = true;
}

/**
 * Logout user and destroy session
 */
function logoutUser() {
    session_unset();
    session_destroy();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Check if current user is admin
 * @return bool
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if current user is employee
 * @return bool
 */
function isEmployee() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'employee';
}

/**
 * Require admin access - redirect if not admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

/**
 * Require employee access - redirect if not employee
 */
function requireEmployee() {
    if (!isEmployee()) {
        header('Location: index.php');
        exit();
    }
}

/**
 * Require any login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}

/**
 * Get current user's employee ID
 * @return int|null
 */
function getCurrentEmployeeId() {
    return $_SESSION['employee_id'] ?? null;
}

/**
 * Get current user's display name
 * @return string
 */
function getCurrentUserName() {
    return $_SESSION['employee_name'] ?? 'User';
}

/**
 * Get current user's role
 * @return string
 */
function getCurrentRole() {
    return $_SESSION['role'] ?? '';
}

/**
 * Format currency to Indonesian Rupiah
 * @param float $amount
 * @return string
 */
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Get month name in Indonesian
 * @param int $month
 * @return string
 */
function getMonthName($month) {
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    return $months[$month] ?? '';
}
?>
