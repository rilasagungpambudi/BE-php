
<?php
// delete.php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id_buku = $_GET['id_buku'];
    $stmt = $conn->prepare("DELETE FROM book WHERE id_buku = :id_buku");
    $stmt->bindParam(':id_buku', $id_buku);

    try {
        $stmt->execute();
        echo json_encode(["message" => "Book deleted successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>