<?php
session_start();
if (!isset($_SESSION['nbs_current_user'])) {
    header('Location: login.php');
    exit();
}
?>