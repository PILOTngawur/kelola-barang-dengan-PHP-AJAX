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
                    <a href="dashboard.php" class="list-group-item list-group-item-action">🏠 Dashboard</a>
                    <a href="kelola_barang.php" class="list-group-item list-group-item-action active">📝 Kelola Barang</a>
                    <a href="" class="list-group-item list-group-item-action">---</a>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Tambah Barang</h5>
                        <form id="formBarang" enctype="multipart/form-data">
                            <div class="form-row">

                                <div class="form-group col-md-4">
                                    <input type="text" name="nama_barang" class="form-control" placeholder="Nama Barang" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" name="kode_barang" class="form-control" placeholder="Kode Barang" maxlength="11" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="number" name="stok_barang" class="form-control" placeholder="Stok" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <select name="keadaan" class="form-control" required>
                                        <option value="">Keadaan</option>
                                        <option value="baik">Baik</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" name="lokasi" class="form-control" placeholder="Lokasi" required>
                                </div>
                                <div class="col-mb-2">
                                    <h6>Masukan Gambar Barang</h6>
                                </div>
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="file" name="gambar_barang" id="formFile">
                                </div>
                                <div class="form-group col-md-5">
                                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                                </div>
                            </div>
                            <div class="form-row">
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Data Barang</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <input type="text" id="cariBarang" class="form-control mr-2" placeholder="🔍 Cari barang..." style="max-width: 400px;">
                            <button class="btn btn-info" id="printSemua">🖨️ Print Semua Barang</button>
                        </div>
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


        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form id="formEditBarang" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">✏️ Edit Barang</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_barang" id="edit_id">
                            <div class="form-group">
                                <label>Ganti Gambar Barang</label>
                                <input type="file" name="gambar_baru" id="edit_gambar" class="form-control">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Gambar Sekarang:</label><br>
                                    <img id="previewEditGambar" src="" style="width:100px;height:100px;object-fit:cover;">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Gambar Baru:</label><br>
                                    <img id="previewBaru" src="" style="width:100px;height:100px;object-fit:cover; display: none;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama_barang" id="edit_nama" class="form-control" placeholder="Nama Barang" required>
                            </div>
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" name="kode_barang" id="edit_kode" class="form-control" placeholder="Kode Barang" maxlength="11" required>
                            </div>
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="number" name="stok_barang" id="edit_stok" class="form-control" placeholder="Stok" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi" id="edit_lokasi" class="form-control" placeholder="Lokasi" required>
                            </div>
                            <div class="form-group">
                                <label>Kondisi</label>
                                <select name="keadaan" id="edit_keadaan" class="form-control" required>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">✅ Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">❌ Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                loadBarang();

                // Tambah Barang
                $("#formBarang").on('submit', function(e) {
                    let formData = new FormData(this);
                    formData.append('aksi', 'tambah');
                    $.ajax({
                        url: 'proses_barang.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
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
                function loadBarang(page = 1, jumlah = 10, keyword = '') {
                    $.post('proses_barang.php', {
                        aksi: 'pagination',
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

                // Hapus Barang
                $(document).on('click', '.hapusBarang', function() {
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
                            }, function(res) {
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

                // Popup modal (edit)
                $(document).on('click', '.editBarang', function() {
                    const id = $(this).data('id');

                    $.post('proses_barang.php', {
                        aksi: 'get',
                        id: id
                    }, function(res) {
                        const data = JSON.parse(res);
                        $('#edit_id').val(data.id);
                        $('#edit_nama').val(data.nama_barang);
                        $('#edit_kode').val(data.kode_barang);
                        $('#edit_stok').val(data.stok_barang);
                        $('#edit_lokasi').val(data.lokasi);
                        $('#edit_keadaan').val(data.keadaan);
                        $('#previewBaru').hide().attr('src', '');
                        $('#previewEditGambar').attr('src', data.gambar_barang);
                        $('#modalEdit').modal('show');
                    });
                });

                // Submit form edit
                $('#formEditBarang').submit(function(e) {
                    let editData = new FormData(this);
                    editData.append('aksi', 'update');
                    $.ajax({
                        url: 'proses_barang.php',
                        type: 'POST',
                        data: editData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
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

                // Cari Barang
                $("#cariBarang").on("keyup", function() {
                    const keyword = $(this).val();
                    $.post("proses_barang.php", {
                        aksi: "cari",
                        keyword: keyword
                    }, function(res) {
                        $("#tampilBarang").html(res);
                    });
                });

                // Print Barang
                $(document).on('click', '.printBarang', function() {
                    const id = $(this).data('id');
                    $.post('proses_barang.php', {
                        aksi: 'print',
                        id: id
                    }, function(res) {
                        const win = window.open('', '_blank');
                        win.document.write('<html><head><title>Print Barang</title></head><body>');
                        win.document.write(res);
                        win.document.write('</body></html>');
                        win.document.close();
                        win.print();
                    });
                });

                // Print Semua Barang
                $("#printSemua").on("click", function() {
                    $.post("proses_barang.php", {
                        aksi: "print_semua"
                    }, function(res) {
                        const win = window.open('', '_blank');
                        win.document.write('<html><head><title>Print Semua Barang</title>');
                        win.document.write('<style>table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }</style>');
                        win.document.write('</head><body>');
                        win.document.write('<h2>Daftar Barang</h2>');
                        win.document.write(res);
                        win.document.write('</body></html>');
                        win.document.close();
                        win.print();
                    });
                });

                // Preview gambar baru saat dipilih
                $("#edit_gambar").on("change", function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $("#previewBaru").attr("src", e.target.result).show();
                        }
                        reader.readAsDataURL(file);
                    }
                });
            });
        </script>

</body>

</html>