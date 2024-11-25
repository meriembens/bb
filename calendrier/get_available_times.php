<?php
include 'connect.php';

if (isset($_GET['doctor_id']) && isset($_GET['date'])) {
    $doctor_id = $_GET['doctor_id'];
    $date = $_GET['date'];

    // Get available times for the selected doctor and date
    $query = "SELECT heure FROM appointments WHERE nom_docteur = ? AND date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $doctor_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_times = [];
    while ($row = $result->fetch_assoc()) {
        $booked_times[] = $row['heure'];
    }

    // Assume you have a predefined set of available times (e.g., 9:00, 10:00, etc.)
    $available_times = ['09:00', '10:00', '11:00', '14:00', '15:00'];  // Example times

    // Filter out the booked times
    $filtered_times = array_diff($available_times, $booked_times);

    echo json_encode(array_values($filtered_times)); // Return available times
}
?>
