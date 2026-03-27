<?php
require_once "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        
        $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
        
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header("Location: index.php?status=deleted");
            exit();
        } else {
            header("Location: index.php?status=error");
            exit();
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        header("Location: index.php?status=error&msg=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

$conn->close();
?>