<?php
include 'database.php';

// Ambil parameter `page` dan `limit` dari query string (default: page 1, limit 10)
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;

// Hitung offset berdasarkan halaman
$offset = ($page - 1) * $limit;

try {
    // Query untuk mendapatkan total jumlah data
    $countQuery = $conn->query("SELECT COUNT(*) AS total FROM book");
    $total = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

    // Query untuk mendapatkan data dengan limit dan offset
    $stmt = $conn->prepare("SELECT * FROM book LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tambahkan URL lengkap ke cover_buku
    $base_url = "colection-test.great-site.net/images/"; // Ganti dengan domain hosting Anda
    foreach ($books as &$book) {
        if (!empty($book['cover_buku'])) {
            $book['cover_buku'] = $base_url . $book['cover_buku'];
        }
    }

    // Buat respon dengan data, halaman saat ini, dan total halaman
    $response = [
        "page" => $page,
        "limit" => $limit,
        "total" => $total,
        "total_pages" => ceil($total / $limit),
        "data" => $books
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
