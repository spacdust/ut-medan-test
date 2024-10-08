<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sertakan koneksi database
    include '../db/connection.php';

    // Ambil dan sanitasi data dari formulir
    $tanggal = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';
    $sales = isset($_POST['sales']) ? trim($_POST['sales']) : '';
    $nama_lead = isset($_POST['nama_lead']) ? trim($_POST['nama_lead']) : '';
    $produk = isset($_POST['produk']) ? trim($_POST['produk']) : '';
    $no_whatsapp = isset($_POST['no_whatsapp']) ? trim($_POST['no_whatsapp']) : '';
    $kota = isset($_POST['kota']) ? trim($_POST['kota']) : '';

    // Validasi data (pastikan tidak kosong)
    if (empty($tanggal) || empty($sales) || empty($nama_lead) || empty($produk) || empty($no_whatsapp) || empty($kota)) {
        // Jika ada data yang kosong, kembali ke formulir dengan pesan error
        $_SESSION['error'] = "Semua field wajib diisi.";
        header("Location: tambah_leads.php"); // Ganti dengan path ke formulir Anda
        exit();
    }

    // Validasi format tanggal (optional, sesuaikan dengan format yang diinginkan)
    $date = DateTime::createFromFormat('m/d/Y', $tanggal);
    if (!$date || $date->format('m/d/Y') !== $tanggal) {
        $_SESSION['error'] = "Format tanggal tidak valid.";
        header("Location: tambah_leads.php"); // Ganti dengan path ke formulir Anda
        exit();
    }

    // Sanitasi input untuk mencegah SQL Injection
    $tanggal = $conn->real_escape_string($tanggal);
    $sales = $conn->real_escape_string($sales);
    $nama_lead = $conn->real_escape_string($nama_lead);
    $produk = $conn->real_escape_string($produk);
    $no_whatsapp = $conn->real_escape_string($no_whatsapp);
    $kota = $conn->real_escape_string($kota);

    // Ubah format tanggal ke format yang sesuai dengan database (misalnya, YYYY-MM-DD)
    $tanggal_db = $date->format('Y-m-d');

    // SQL untuk memasukkan data ke tabel leads (ganti 'leads' dengan nama tabel Anda)
    $sql_insert = "INSERT INTO leads (tanggal, id_sales, nama_lead, id_produk, no_wa, kota, id_user) 
                   VALUES ('$tanggal_db', '$sales', '$nama_lead', '$produk', '$no_whatsapp', '$kota', 1)";

    if ($conn->query($sql_insert) === TRUE) {
        // Jika berhasil, arahkan ke index.php dengan pesan sukses
        $_SESSION['success'] = "Data lead berhasil ditambahkan.";
        header("Location: ../index.php");
        exit();
    } else {
        // Jika gagal, arahkan kembali ke formulir dengan pesan error
        $_SESSION['error'] = "Error: " . $sql_insert . "<br>" . $conn->error;
        header("Location: tambah_leads.php"); // Ganti dengan path ke formulir Anda
        exit();
    }

    // Tutup koneksi
    $conn->close();
} else {
    // Jika bukan metode POST, arahkan kembali ke formulir
    header("Location: tambah_leads.php"); // Ganti dengan path ke formulir Anda
    exit();
}
