<?php
include 'connect.php';

if (isset($_GET['doctor_id'])) {
    $doctor_id = $_GET['doctor_id'];

    // Get available dates for the selected doctor
    $query = "SELECT DISTINCT date FROM appointments WHERE nom_docteur = ? AND date >= CURDATE()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $available_dates = [];
    while ($row = $result->fetch_assoc()) {
        $available_dates[] = $row['date'];
    }
    
    echo json_encode($available_dates);
}
?>
