<?php
include('../../include/config.php');

header('Content-Type: application/json');

$term = isset($_GET['term']) ? $_GET['term'] : '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT specilization FROM doctorspecilization 
                          WHERE specilization LIKE :term 
                          LIMIT 5");
    $stmt->bindValue(':term', '%' . $term . '%');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode($results);
} catch(PDOException $e) {
    echo json_encode([]);
}
?>