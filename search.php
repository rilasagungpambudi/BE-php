<?php
include 'database.php';

// Ambil parameter pencarian dari query string
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$tahun = isset($_GET['tahun']) && is_numeric($_GET['tahun']) ? (int)$_GET['tahun'] : null;

try {
    // Buat query dasar untuk pencarian
    $query = "SELECT * FROM book WHERE (judul_buku LIKE :search OR nama_penulis LIKE :search)";
    
    // Tambahkan filter tahun jika parameter tahun diberikan
    if (!is_null($tahun)) {
        $query .= " AND tahun_terbit = :tahun";
    }

    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);

    if (!is_null($tahun)) {
        $stmt->bindParam(':tahun', $tahun, PDO::PARAM_INT);
    }

    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tambahkan URL lengkap ke cover_buku
    $base_url = "https://yourdomain.com/images/"; // Ganti dengan domain hosting Anda
    foreach ($books as &$book) {
        if (!empty($book['cover_buku'])) {
            $book['cover_buku'] = $base_url . $book['cover_buku'];
        }
    }

    // Kembalikan hasil pencarian dalam bentuk JSON
    echo json_encode($books);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
