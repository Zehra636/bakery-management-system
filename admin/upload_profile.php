<?php
session_start();
require_once '../includes/functions.php';

if (!isAdmin())
    die("Yetkisiz erişim");

if ($_FILES['profile_image']) {
    $target = '../assets/images/admin_profile.png';
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $target);
    echo "OK";
}
?>