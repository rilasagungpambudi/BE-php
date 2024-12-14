<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Cek apakah file diunggah
        if (isset($_FILES['cover_buku']) && $_FILES['cover_buku']['error'] === UPLOAD_ERR_OK) {
            // Tentukan folder penyimpanan
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES['cover_buku']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validasi file (opsional: hanya gambar tertentu)
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            // Pindahkan file ke folder target
            if (!move_uploaded_file($_FILES['cover_buku']['tmp_name'], $target_file)) {
                throw new Exception("Failed to upload image.");
            }
        } else {
            $target_file = null; // Tidak ada file yang diunggah
        }

        // Ambil data dari form
        $judul_buku = $_POST['judul_buku'];
        $kode_buku = $_POST['kode_buku'];
        $tahun_terbit = $_POST['tahun_terbit'];
        $nama_penerbit = $_POST['nama_penerbit'];
        $nama_penulis = $_POST['nama_penulis'];
        $sinopsis_buku = $_POST['sinopsis_buku'];
        $cover_buku = $target_file ? basename($target_file) : null;

        // Query untuk insert data
        $stmt = $conn->prepare("INSERT INTO book (judul_buku, kode_buku, tahun_terbit, nama_penerbit, nama_penulis, cover_buku, sinopsis_buku) VALUES (:judul_buku, :kode_buku, :tahun_terbit, :nama_penerbit, :nama_penulis, :cover_buku, :sinopsis_buku)");
        $stmt->bindParam(':judul_buku', $judul_buku);
        $stmt->bindParam(':kode_buku', $kode_buku);
        $stmt->bindParam(':tahun_terbit', $tahun_terbit);
        $stmt->bindParam(':nama_penerbit', $nama_penerbit);
        $stmt->bindParam(':nama_penulis', $nama_penulis);
        $stmt->bindParam(':cover_buku', $cover_buku);
        $stmt->bindParam(':sinopsis_buku', $sinopsis_buku);

        $stmt->execute();
        echo json_encode(["message" => "Book created successfully"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
