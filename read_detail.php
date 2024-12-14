
<?php
// read_detail.php
include 'database.php';

if (isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];

    try {
        $stmt = $conn->prepare("SELECT * FROM book WHERE id_buku = :id_buku");
        $stmt->bindParam(':id_buku', $id_buku, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(["message" => "Book not found"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Missing 'id_buku' parameter"]);
}
?>