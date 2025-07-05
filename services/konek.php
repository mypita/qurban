<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "credibug_qurban";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke utf8mb4 untuk mendukung semua karakter termasuk emoji
$conn->set_charset("utf8mb4");

// Fungsi untuk membersihkan input
function clean_input($data) {
    global $conn;
    return htmlspecialchars(stripcslashes(trim($conn->real_escape_string($data))));
}
?>