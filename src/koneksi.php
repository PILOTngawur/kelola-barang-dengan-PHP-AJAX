<?php

//deklasrasi variabel
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "db_ajax_php";    

$coon = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if($coon) {
    //echo "Koneksi Berhasil!";
} else {
    echo "Koneksi Gagal! : ". mysqli_connect_error();
}

?>