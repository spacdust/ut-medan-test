<?php

session_start();

include '../db/connection.php';

$sql_produk = "SELECT * FROM produk";
$result_produk = $conn->query($sql_produk);
$produk_list = $result_produk->fetch_all(MYSQLI_ASSOC);  // Mengambil semua produk sebagai array

$sql_sales = "SELECT * FROM sales";
$result_sales = $conn->query($sql_sales);
$sales_list = $result_sales->fetch_all(MYSQLI_ASSOC);  // Mengambil semua sales sebagai array


$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="container d-flex justify-content-center align-items-center p-5">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Selamat datang di tambah leads</h6>
                <div class="card">
                    <div class="row mx-1 mt-3">
                        <div class="col">
                            <button class="btn btn-success btn-sm" type="submit" onclick="window.history.back();">Kembali</button>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <form class="row g-3" method="POST" action="process_add_leads.php">
                            <!-- Tanggal -->
                            <div class="col-md-4">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input id="datepicker" name="tanggal" class="form-control" placeholder="mm/dd/yyyy" required />
                                <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
                                <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
                                <script>
                                    $('#datepicker').datepicker({
                                        uiLibrary: 'bootstrap5',
                                        format: 'mm/dd/yyyy'
                                    });
                                </script>
                            </div>

                            <!-- Dropdown Sales -->
                            <div class="col-md-4">
                                <label for="sales" class="form-label">Sales</label>
                                <select class="form-select" id="sales" name="sales" required>
                                    <option selected disabled value="">--Pilih Sales--</option>
                                    <?php foreach ($sales_list as $sales): ?>
                                        <option value="<?php echo htmlspecialchars($sales['id_sales']); ?>">
                                            <?php echo htmlspecialchars($sales['nama_sales']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Nama Lead -->
                            <div class="col-md-4">
                                <label for="nama_lead" class="form-label">Nama Lead</label>
                                <input type="text" class="form-control" id="nama_lead" name="nama_lead" placeholder="Nama Lead" required>
                            </div>

                            <!-- Dropdown Produk -->
                            <div class="col-md-4">
                                <label for="produk" class="form-label">Produk</label>
                                <select class="form-select" id="produk" name="produk" required>
                                    <option selected disabled value="">--Pilih Produk--</option>
                                    <?php foreach ($produk_list as $produk): ?>
                                        <option value="<?php echo htmlspecialchars($produk['id_produk']); ?>">
                                            <?php echo htmlspecialchars($produk['nama_produk']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- No. Whatsapp -->
                            <div class="col-md-4">
                                <label for="no_whatsapp" class="form-label">No. Whatsapp</label>
                                <input type="text" class="form-control" id="no_whatsapp" name="no_whatsapp" placeholder="No. Whatsapp" required>
                            </div>

                            <!-- Kota -->
                            <div class="col-md-4">
                                <label for="kota" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="kota" name="kota" placeholder="Kota" required>
                            </div>

                            <!-- Tombol Submit dan Cancel -->
                            <div class="col-6 d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-secondary" type="button" onclick="window.history.back();">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>