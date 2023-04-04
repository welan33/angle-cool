<?php
// Initialize the session
session_start();

// Destroy the session.
session_destroy();
 
// go vers l'index
header("location: /php_exam");
exit;
?>