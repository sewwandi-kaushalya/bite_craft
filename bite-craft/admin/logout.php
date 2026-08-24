
<?php

session_start();

// ========================================
// DESTROY ADMIN SESSION
// ========================================

$_SESSION = [];

session_unset();
session_destroy();

// ========================================
// REDIRECT TO LOGIN PAGE
// ========================================

header("Location: ../login.php");
exit;

?>

