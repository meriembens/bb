<?php
// editAppointment.php

include 'connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Fetch appointment data from database using the ID
    $query = "SELECT * FROM appointments WHERE appointment_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $appointment = $result->fetch_assoc();
            // Populate your form with this appointment data
        } else {
            echo "Appointment not found!";
        }
    }
}

// 1. Check if the appointment is being updated via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the appointment data from the form submission
    $appointment_id = $_POST['appointment_id'];  // The appointment ID from the form
    $prenom_patient = $_POST['prenom_patient'];
    $nom_patient = $_POST['nom_patient'];
    $phone = $_POST['phone'];
    $motif = $_POST['motif'];
    $nom_docteur = $_POST['nom_docteur'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Database update query (simplified for example)
    $query = "UPDATE appointments SET prenom_patient = ?, nom_patient = ?,
     phone = ?, motif = ?, nom_docteur = ?, date = ?, heure = ? WHERE appointment_id = ?";

    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssssi", $prenom_patient, $nom_patient, $phone, $motif, $nom_docteur, $date, $time, $appointment_id);

        // Execute the query
        if ($stmt->execute()) {
            // 2. After the update is successful, redirect to the same day
            // Extract the day, month, and year from the URL parameters
            $day = isset($_GET['day']) ? $_GET['day'] : date('d');
            $month = isset($_GET['month']) ? $_GET['month'] : date('F'); // Default to current month if not set
            $year = isset($_GET['year']) ? $_GET['year'] : date('Y'); // Default to current year if not set

    
        } else {
            echo "Error updating appointment!";
        }
    }
}
?>
