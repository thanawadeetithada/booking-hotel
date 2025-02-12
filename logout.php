<?php
session_start();

if (isset($_SESSION['userrole']) && $_SESSION['userrole'] === 'admin') {
    session_unset();
    session_destroy();
    echo "<script>
        alert('ออกจากระบบแล้ว'); 
        window.location.href = 'login.php';
    </script>";
} else {
    session_unset();
    session_destroy();
    echo "<script>
        alert('ออกจากระบบแล้ว'); 
        window.location.replace(document.referrer);
    </script>";
}
exit();
?>
