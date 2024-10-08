<?php
session_start();

include 'db/connection.php';

// data produk
$sql_produk = "SELECT id_produk, nama_produk FROM produk";
$result_produk = $conn->query($sql_produk);
$produk_list = [];
if ($result_produk->num_rows > 0) {
    while ($row = $result_produk->fetch_assoc()) {
        $produk_list[] = $row;
    }
}

// data sales
$sql_sales = "SELECT id_sales, nama_sales FROM sales";
$result_sales = $conn->query($sql_sales);
$sales_list = [];
if ($result_sales->num_rows > 0) {
    while ($row = $result_sales->fetch_assoc()) {
        $sales_list[] = $row;
    }
}

$selected_produk = isset($_GET['produk']) ? $_GET['produk'] : '';
$selected_sales = isset($_GET['sales']) ? $_GET['sales'] : '';
$selected_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

$conditions = [];
$params = [];
$types = "";

// filter produk
if (!empty($selected_produk)) {
    $conditions[] = "leads.id_produk = ?";
    $params[] = $selected_produk;
    $types .= "i";
}

// filter sales
if (!empty($selected_sales)) {
    $conditions[] = "leads.id_sales = ?";
    $params[] = $selected_sales;
    $types .= "i";
}

// filter bulan
if (!empty($selected_bulan)) {
    $conditions[] = "MONTH(leads.tanggal) = ?";
    $params[] = $selected_bulan;
    $types .= "i";
}

// membuat kondisi where
$where = "";
if (count($conditions) > 0) {
    $where = "WHERE " . implode(" AND ", $conditions);
}

// query
$sql_leads = "SELECT leads.id_leads, leads.tanggal, sales.nama_sales, produk.nama_produk, leads.nama_lead, leads.no_wa, leads.kota
             FROM leads
             JOIN sales ON leads.id_sales = sales.id_sales
             JOIN produk ON leads.id_produk = produk.id_produk
             $where
             ORDER BY leads.id_leads DESC"; // Anda dapat menyesuaikan urutan

$stmt = $conn->prepare($sql_leads);

// jika ada filter, bind parameter
if (count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>List Data Leads</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    </head>

<body>
    <div class="container mt-5">
        <!-- Menampilkan Pesan Sukses atau Error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row d-flex align-items-center">
            <div class="col">
                <h1>Data Leads</h1>
            </div>
            <div class="col d-flex justify-content-end">
                <a href="pages/add_leads.php" class="btn btn-success btn-sm">Tambah Lead</a>
            </div>
        </div>


        <!-- Formulir Pencarian -->
        <form class="row g-3 mb-4" method="GET" action="index.php">
            <!-- Pencarian Produk -->
            <div class="col-md-3">
                <label for="produk" class="form-label">Pilih Produk</label>
                <select class="form-select" id="produk" name="produk">
                    <option value="">-- Semua Produk --</option>
                    <?php foreach ($produk_list as $produk): ?>
                        <option value="<?php echo $produk['id_produk']; ?>" <?php echo ($selected_produk == $produk['id_produk']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($produk['nama_produk']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pencarian Sales -->
            <div class="col-md-3">
                <label for="sales" class="form-label">Pilih Sales</label>
                <select class="form-select" id="sales" name="sales">
                    <option value="">-- Semua Sales --</option>
                    <?php foreach ($sales_list as $sales): ?>
                        <option value="<?php echo $sales['id_sales']; ?>" <?php echo ($selected_sales == $sales['id_sales']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sales['nama_sales']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pencarian Bulan -->
            <div class="col-md-3">
                <label for="bulan" class="form-label">Pilih Bulan</label>
                <select class="form-select" id="bulan" name="bulan">
                    <option value="">-- Semua Bulan --</option>
                    <?php
                    // Array nama bulan
                    $nama_bulan = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember'
                    ];
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<option value="' . $i . '" ' . ($selected_bulan == $i ? 'selected' : '') . '>' . $nama_bulan[$i] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Tombol Cari dan Reset -->
            <div class="col-md-3 d-flex align-items-end">
                <div class="row w-100">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                    <div class="col-6">
                        <a href="index.php" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Tabel Data Leads -->
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">ID Input</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Sales</th>
                    <th scope="col">Produk</th>
                    <th scope="col">Nama Leads</th>
                    <th scope="col">No Wa</th>
                    <th scope="col">Kota</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Format ID Input (contoh: 001, 002)
                        $id_input = sprintf('00%d', $row['id_leads']);

                        // Memformat tanggal menjadi dd-mm-yyyy
                        $formatted_date = date("d-m-Y", strtotime($row['tanggal']));

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_leads']) . "</td>";
                        echo "<td>" . htmlspecialchars($id_input) . "</td>";
                        echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_sales']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_lead']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['no_wa']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kota']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Tidak ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>

<?php
$conn->close();
?>