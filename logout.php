<?php
require_once 'config.php';

// Clear session and redirect
session_destroy();
redirect('index.php');
?>
