<?php
$host = "localhost";      // biasanya localhost
$user = "root";           // default user XAMPP
$password = "";           // kosongkan jika tidak ada password
$database = "db_todolist"; // sesuaikan dengan nama database kamu

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
