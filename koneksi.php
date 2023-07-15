<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mapstest_db";

// Membuat koneksi ke basis data MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke basis data gagal: " . $conn->connect_error);
}
?>
