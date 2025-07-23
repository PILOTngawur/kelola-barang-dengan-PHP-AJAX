<?php

session_start();

session_destroy();

header("location:../src/admin/login.php");

?>