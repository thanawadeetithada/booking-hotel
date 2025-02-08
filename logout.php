<?php
session_start();
session_destroy();
echo "<script>alert('ออกจากระบบแล้ว'); window.location='login.php';</script>";
?>
