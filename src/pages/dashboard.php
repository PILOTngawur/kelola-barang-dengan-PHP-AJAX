<?php
session_start();
if (!$_SESSION['id_user']) {
  header("location: login.php");
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <title>APP-KELOLA-BARANG</title>
</head>

<body>
  <nav class="navbar bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand text-white">Selamat Datang <h5 class=""><?php echo $_SESSION['nama_lengkap'] ?></h5></a>
      <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>
  </nav>

  <div class="container-fluid" style="margin-top: 30px;">
    <div class="row">
      <div class="col-md-3 mb-3">
        <div class="list-group">
          <a href="dashboard.php" class="list-group-item list-group-item-action active">🏠 Dashboard</a>
          <a href="kelola_barang.php" class="list-group-item list-group-item-action">📝 Kelola Barang</a>
          <a href="" class="list-group-item list-group-item-action">----</a>
        </div>
      </div>
      <div class="col-md-9">

        <div class="card mb-3">
          <div class="card-body">
            <h5>Data Barang</h5>
            <input type="text" id="cariBarang" class="form-control mb-3" placeholder="🔍 Cari barang...">
            <div id="tampilBarang">Loading...</div>

            <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <label>Tampilkan
                                    <select id="jumlahData" class="custom-select custom-select-sm w-auto d-inline mx-1">
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                    </select>
                                    data per halaman</label>
                            </div>
                            <div id="pagination"></div>
          </div>
        </div>
      </div> 
    </div>
  </div> 



<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    loadBarang();


   // Load data barang
   function loadBarang(page = 1, jumlah = 10, keyword = '') {
                    $.post('proses_barang.php', {
                        aksi: 'pagination_dashboard',
                        page: page,
                        limit: jumlah,
                        keyword: keyword
                    }, function(res) {
                        const hasil = JSON.parse(res);
                        $("#tampilBarang").html(hasil.data);
                        $("#pagination").html(hasil.pagination);
                    });
                }
                // Saat dropdown jumlah data berubah
                $("#jumlahData").change(function() {
                    const jumlah = $(this).val();
                    loadBarang(1, jumlah);
                });
                // Saat search
                $("#cariBarang").on("keyup", function() {
                    const keyword = $(this).val();
                    const jumlah = $("#jumlahData").val();
                    loadBarang(1, jumlah, keyword);
                });
                // Saat klik pagination
                $(document).on("click", ".page-link", function(e) {
                    e.preventDefault();
                    const page = $(this).data("page");
                    const jumlah = $("#jumlahData").val();
                    const keyword = $("#cariBarang").val();
                    loadBarang(page, jumlah, keyword);
                });

    $("#cariBarang").on("keyup", function() {
                const keyword = $(this).val();
                $.post("proses_barang.php", {
                    aksi: "cari_dashboard",
                    keyword: keyword
                }, function(res) {
                    $("#tampilBarang").html(res);
                });
            });

  });
</script>

</body>

</html>