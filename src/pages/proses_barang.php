<?php
$coon = mysqli_connect("localhost", "root", "", "db_ajax_php");

// Tambah barang
if ($_POST['aksi'] == 'tambah') {
    $nama = $_POST['nama_barang'];
    $kode = $_POST['kode_barang'];
    $stok = $_POST['stok_barang'];
    $lokasi = $_POST['lokasi'];
    $keadaan = $_POST['keadaan'];

    $coon->query("INSERT INTO barang (nama_barang, kode_barang, stok_barang, lokasi, keadaan) 
                     VALUES ('$nama', '$kode', '$stok', '$lokasi', '$keadaan')");
}

// Tampil semua barang
if ($_POST['aksi'] == 'tampil') {
    $result = $coon->query("SELECT * FROM barang ORDER BY id DESC");

    echo '<table class="table table-bordered table-sm mt-3">';
    echo '<thead><tr><th>Nama</th><th>Kode</th><th>Stok</th><th>Lokasi</th><th>Keadaan</th><th>Aksi</th></tr></thead><tbody>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['nama_barang']}</td>
                <td>{$row['kode_barang']}</td>
                <td>{$row['stok_barang']}</td>
                <td>{$row['lokasi']}</td>
                <td>{$row['keadaan']}</td>
                <td>
                  <button class='btn btn-sm btn-warning editBarang' data-id='{$row['id']}'>Edit</button>
                  <button class='btn btn-sm btn-danger hapusBarang' data-id='{$row['id']}'>Hapus</button>
                  <button class='btn btn-sm btn-primary printBarang' data-id='{$row['id']}'>Print</button>
                </td>
              </tr>";
    }
    echo '</tbody></table>';
}


if ($_POST['aksi'] == 'get') {
    $id = $_POST['id'];
    $q = $coon->query("SELECT * FROM barang WHERE id = $id");
    echo json_encode($q->fetch_assoc());
}

// Update barang
if ($_POST['aksi'] == 'update') {
    $id = $_POST['id_barang'];
    $nama = $_POST['nama_barang'];
    $kode = $_POST['kode_barang'];
    $stok = $_POST['stok_barang'];
    $lokasi = $_POST['lokasi'];
    $keadaan = $_POST['keadaan'];

    $coon->query("UPDATE barang SET 
        nama_barang='$nama', 
        kode_barang='$kode', 
        stok_barang='$stok',
        lokasi='$lokasi',
        keadaan='$keadaan'
        WHERE id=$id
    ");
}

// Hapus barang
if ($_POST['aksi'] == 'hapus') {
    $id = $_POST['id'];
    $coon->query("DELETE FROM barang WHERE id = $id");
}

if ($_POST['aksi'] == 'print') {
    $id = $_POST['id'];
    $q = $coon->query("SELECT * FROM barang WHERE id = $id");
    $data = $q->fetch_assoc();

    echo "<h1>Detail Barang</h1>";
    echo "<p>Nama: {$data['nama_barang']}</p>";
    echo "<p>Kode: {$data['kode_barang']}</p>";
    echo "<p>Stok: {$data['stok_barang']}</p>";
    echo "<p>Lokasi: {$data['lokasi']}</p>";
    echo "<p>Keadaan: {$data['keadaan']}</p>";
}
?>
