<?php
session_start();
require_once '../includes/functions.php';

if (!isAdmin())
    die("Yetkisiz erişim");

$file = '../assets/images/admin_profile.png';
if (file_exists($file)) {
    unlink($file);
}
echo "OK";
?>