<?php
require_once('koneksi.php');

// Periksa apakah ada data yang dikirim dari AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah data title dan description tersedia dalam permintaan POST
    if (isset($_POST['title']) && isset($_POST['description'])) {
        // Mendapatkan data dari permintaan POST
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Menyiapkan pernyataan INSERT
        $sql = 'INSERT INTO progress (title, description) VALUES (?, ?)';
        $stmt = mysqli_prepare($conn, $sql);

        // Periksa apakah persiapan pernyataan berhasil
        if ($stmt) {
            // Mengikat parameter
            mysqli_stmt_bind_param($stmt, 'ss', $title, $description);

            // Eksekusi pernyataan INSERT
            if (mysqli_stmt_execute($stmt)) {
                echo 'Data berhasil disimpan';

                // Menutup pernyataan
                mysqli_stmt_close($stmt);
            } else {
                echo 'Terjadi kesalahan saat menyimpan data: ' . mysqli_stmt_error($stmt);

                // Menutup pernyataan
                mysqli_stmt_close($stmt);
                // Menutup koneksi
                mysqli_close($conn);
                exit(); // Menghentikan eksekusi script
            }
        } else {
            echo 'Terjadi kesalahan saat menyiapkan pernyataan: ' . mysqli_error($conn);

            // Menutup koneksi
            mysqli_close($conn);
            exit(); // Menghentikan eksekusi script
        }
    } else {
        echo 'Data tidak lengkap';
        exit(); // Menghentikan eksekusi script
    }
} else {
    // Jika permintaan bukan dari metode POST, menghentikan eksekusi script
    exit();
}

// Fungsi untuk mendengarkan perubahan data secara realtime
function listenForChanges() {
    global $conn;

    // Membaca perubahan data secara berkala
    while (true) {
        // Melakukan query untuk mendapatkan data terbaru dari tabel progress
        $query = "SELECT * FROM progress";
        $result = mysqli_query($conn, $query);

        // Memeriksa apakah query berhasil dieksekusi
        if ($result) {
            $data = array();

            // Mengambil data dari hasil query
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            // Mengirimkan data ke sisi client menggunakan metode AJAX
            echo json_encode($data);

            // Membebaskan memori hasil query
            mysqli_free_result($result);
        } else {
            echo 'Terjadi kesalahan saat membaca data: ' . mysqli_error($conn);
            break;
        }

        // Memberi jeda waktu sebelum membaca perubahan data berikutnya
        sleep(1);
    }

    // Menutup koneksi
    mysqli_close($conn);
}

// Memanggil fungsi untuk mendengarkan perubahan data secara realtime
listenForChanges();
?>
