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
//update (modal)
if ($_POST['aksi'] == 'get') {
    $id = $_POST['id'];
    $q = $coon->query("SELECT * FROM barang WHERE id = $id");
    echo json_encode($q->fetch_assoc());
}

// Hapus barang
if ($_POST['aksi'] == 'hapus') {
    $id = $_POST['id'];
    $coon->query("DELETE FROM barang WHERE id = $id");
}

//function untuk render tabel barang
function render_table_barang($result, $with_action = false) {
    $html = '<table class="table table-bordered table-sm mt-3">';
    $html .= '<thead><tr><th>Nama</th><th>Kode</th><th>Stok</th><th>Lokasi</th><th>Keadaan</th>';
    if ($with_action) $html .= '<th>Aksi</th>';
    $html .= '</tr></thead><tbody>';

    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
            <td>{$row['nama_barang']}</td>
            <td>{$row['kode_barang']}</td>
            <td>{$row['stok_barang']}</td>
            <td>{$row['lokasi']}</td>
            <td>{$row['keadaan']}</td>";
        
        if ($with_action) {
            $html .= "<td>
                <button class='btn btn-sm btn-warning editBarang' data-id='{$row['id']}'>✏️ Edit</button>
                <button class='btn btn-sm btn-danger hapusBarang' data-id='{$row['id']}'>🗑️ Hapus</button>
                <button class='btn btn-sm btn-info printBarang' data-id='{$row['id']}'>🖨️ Print</button>
            </td>";
        }

        $html .= '</tr>';
    }

    $html .= '</tbody></table>';
    return $html;
}


//tampil dashboard
if ($_POST['aksi'] == 'tampil_dashboard') {
    $result = $coon->query("SELECT * FROM barang ORDER BY id DESC");
    echo render_table_barang($result);
}

// Tampil semua barang
if ($_POST['aksi'] == 'tampil') {
    $result = $coon->query("SELECT * FROM barang ORDER BY id DESC");
    echo render_table_barang($result, true);
}

//cari dashboard
if ($_POST['aksi'] == 'cari_dashboard') {
    $keyword = $_POST['keyword'];
    $result = $coon->query("SELECT * FROM barang 
        WHERE nama_barang LIKE '%$keyword%' 
        OR kode_barang LIKE '%$keyword%' 
        OR lokasi LIKE '%$keyword%' 
        ORDER BY id DESC");
    echo render_table_barang($result);
}

//cari keloladata
if ($_POST['aksi'] == 'cari') {
    $keyword = $_POST['keyword'];
    $result = $coon->query("SELECT * FROM barang 
        WHERE nama_barang LIKE '%$keyword%' 
        OR kode_barang LIKE '%$keyword%' 
        OR lokasi LIKE '%$keyword%' 
        ORDER BY id DESC");
    echo render_table_barang($result, true);
}

//print satu data
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

//PRINT SEMUA DATA
if ($_POST['aksi'] == 'print_semua') {
    $result = $coon->query("SELECT * FROM barang ORDER BY id DESC");

    echo '<table>';
    echo '<thead><tr><th>No</th><th>Nama</th><th>Kode</th><th>Stok</th><th>Lokasi</th><th>Keadaan</th></tr></thead><tbody>';
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama_barang']}</td>
                <td>{$row['kode_barang']}</td>
                <td>{$row['stok_barang']}</td>
                <td>{$row['lokasi']}</td>
                <td>{$row['keadaan']}</td>
              </tr>";
        $no++;
    }
    echo '</tbody></table>';
}

//pagination dashboard
if ($_POST['aksi'] == 'pagination_dashboard') {
    $limit = intval($_POST['limit']);
    $page = intval($_POST['page']);
    $keyword = $_POST['keyword'] ?? '';

    $offset = ($page - 1) * $limit;

    $where = "";
    if ($keyword != '') {
        $where = "WHERE nama_barang LIKE '%$keyword%' OR kode_barang LIKE '%$keyword%' OR lokasi LIKE '%$keyword%'";
    }

    $total = $coon->query("SELECT COUNT(*) as total FROM barang $where")->fetch_assoc()['total'];
    $result = $coon->query("SELECT * FROM barang $where ORDER BY id DESC LIMIT $limit OFFSET $offset");

    $html = render_table_barang($result, false); // ⬅️ SIMPAN KE VARIABEL, JANGAN LANGSUNG ECHO

    // Buat pagination
    $totalPages = ceil($total / $limit);
    $pagination = '<nav><ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        $pagination .= "<li class='page-item $active'><a href='#' class='page-link' data-page='$i'>$i</a></li>";
    }
    $pagination .= '</ul></nav>';

    echo json_encode([
        'data' => $html,
        'pagination' => $pagination
    ]);
}

//pagination kelola
if ($_POST['aksi'] == 'pagination') {
    $limit = intval($_POST['limit']);
    $page = intval($_POST['page']);
    $keyword = $_POST['keyword'] ?? '';

    $offset = ($page - 1) * $limit;

    $where = "";
    if ($keyword != '') {
        $where = "WHERE nama_barang LIKE '%$keyword%' OR kode_barang LIKE '%$keyword%' OR lokasi LIKE '%$keyword%'";
    }

    $total = $coon->query("SELECT COUNT(*) as total FROM barang $where")->fetch_assoc()['total'];
    $result = $coon->query("SELECT * FROM barang $where ORDER BY id DESC LIMIT $limit OFFSET $offset");

    $html = render_table_barang($result, true);

    // Buat pagination
    $totalPages = ceil($total / $limit);
    $pagination = '<nav><ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        $pagination .= "<li class='page-item $active'><a href='#' class='page-link' data-page='$i'>$i</a></li>";
    }
    $pagination .= '</ul></nav>';

    echo json_encode([
        'data' => $html,
        'pagination' => $pagination
    ]);
}
