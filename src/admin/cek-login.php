<?php

session_start();

require_once '../koneksi.php';

$username     = $_POST['username'];
$password      =$_POST['password'];

//query
$query  = "SELECT * FROM tbl_users WHERE username='$username' AND password='$password'";
$result     = mysqli_query($coon, $query);
$num_row     = mysqli_num_rows($result);
$row         = mysqli_fetch_array($result);

if($num_row >=1) {
    
    echo "success";

    $_SESSION['id_user']       = $row['id_user'];
    $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
    $_SESSION['username']       = $row['username'];

} else {
    
    echo "error";

}

?>