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
  <title>Dashboard</title>
</head>

<body>
  <nav class="navbar bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand text-white">Selamat Datang <?php echo $_SESSION['nama_lengkap'] ?></a>
      <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>
  </nav>

  <div class="container-fluid" style="margin-top: 30px;">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 mb-3">
        <div class="list-group">
          <a href="index.php" class="list-group-item list-group-item-action active">Dashboard</a>
          <a href="profile.php" class="list-group-item list-group-item-action">Profile</a>
          <a href="home.php" class="list-group-item list-group-item-action">Home</a>
        </div>
      </div>

      <!-- Konten utama -->
      <div class="col-md-9">
        <!-- Tambah Barang -->
        <div class="card mb-3">
          <div class="card-body">
            <h5>Tambah Barang</h5>
            <form id="formBarang">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <input type="text" name="nama_barang" class="form-control" placeholder="Nama Barang" required>
                </div>
                <div class="form-group col-md-4">
                  <input type="text" name="kode_barang" class="form-control" placeholder="Kode Barang" maxlength="11" required>
                </div>
                <div class="form-group col-md-2">
                  <input type="number" name="stok_barang" class="form-control" placeholder="Stok" required>
                </div>
                <div class="form-group col-md-2">
                  <select name="keadaan" class="form-control" required>
                    <option value=""></option>
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="hilang">Hilang</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <input type="text" name="lokasi" class="form-control" placeholder="Lokasi" required>
                </div>
                <div class="form-group col-md-6">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Daftar Barang -->
        <div class="card mb-3">
          <div class="card-body">
            <h5>Data Barang</h5>
            <div id="tampilBarang">Loading...</div>
          </div>
        </div>
      </div> <!-- akhir col-md-9 -->
    </div> <!-- akhir row -->
  </div> <!-- akhir container-fluid -->


  <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form id="formEditBarang">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Barang</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_barang" id="edit_id">
            <div class="form-group">
              <input type="text" name="nama_barang" id="edit_nama" class="form-control" placeholder="Nama Barang" required>
            </div>
            <div class="form-group">
              <input type="text" name="kode_barang" id="edit_kode" class="form-control" placeholder="Kode Barang" maxlength="11" required>
            </div>
            <div class="form-group">
              <input type="number" name="stok_barang" id="edit_stok" class="form-control" placeholder="Stok" required>
            </div>
            <div class="form-group">
              <input type="text" name="lokasi" id="edit_lokasi" class="form-control" placeholder="Lokasi" required>
            </div>
            <div class="form-group">
              <select name="keadaan" id="edit_keadaan" class="form-control" required>
                <option value="baik">Baik</option>
                <option value="rusak">Rusak</option>
                <option value="hilang">Hilang</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Script AJAX langsung di file ini -->
  <!-- JANGAN ubah urutan -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    loadBarang();

    // Tambah Barang
    $("#formBarang").on('submit', function (e) {
      e.preventDefault();
      $.ajax({
        url: 'proses_barang.php',
        type: 'POST',
        data: $(this).serialize() + '&aksi=tambah',
        success: function (response) {
          $("#formBarang")[0].reset();
          loadBarang();
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data barang berhasil ditambahkan'
          });
        }
      });
    });

    // Load data barang
    function loadBarang() {
      $.ajax({
        url: 'proses_barang.php',
        type: 'POST',
        data: { aksi: 'tampil' },
        success: function (data) {
          $("#tampilBarang").html(data);
        }
      });
    }

    // Hapus Barang dengan Swal confirm
    $(document).on('click', '.hapusBarang', function () {
      const id = $(this).data('id');

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.post('proses_barang.php', {
            aksi: 'hapus',
            id: id
          }, function (res) {
            loadBarang();
            Swal.fire(
              'Terhapus!',
              'Data barang berhasil dihapus.',
              'success'
            );
          });
        }
      });
    });

    // Buka modal edit
    $(document).on('click', '.editBarang', function () {
      const id = $(this).data('id');
      $.post('proses_barang.php', {
        aksi: 'get',
        id: id
      }, function (res) {
        const data = JSON.parse(res);
        $('#edit_id').val(data.id);
        $('#edit_nama').val(data.nama_barang);
        $('#edit_kode').val(data.kode_barang);
        $('#edit_stok').val(data.stok_barang);
        $('#edit_lokasi').val(data.lokasi);
        $('#edit_keadaan').val(data.keadaan);
        $('#modalEdit').modal('show');
      });
    });

    // Submit form edit
    $('#formEditBarang').submit(function (e) {
      e.preventDefault();
      $.ajax({
        url: 'proses_barang.php',
        type: 'POST',
        data: $(this).serialize() + '&aksi=update',
        success: function (res) {
          $('#modalEdit').modal('hide');
          loadBarang();
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data barang berhasil diperbarui'
          });
        }
      });
    });
  });
</script>

</body>

</html>