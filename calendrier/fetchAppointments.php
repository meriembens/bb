<?php
include "connect.php";

$sql = "SELECT appointment_id, nom_patient, prenom_patient, phone, nom_docteur, date, heure, motif FROM appointments";
$result = $conn->query($sql);

if ($result === false) {
    die(json_encode(["error" => "Error executing query: " . $conn->error]));
}

$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Combine date and time into ISO format
        $start = $row['date'] . 'T' . $row['heure']; // ISO 8601 format
        
        // Format the time (remove the seconds)
        $formattedHeure = substr($row['heure'], 0, 5); // Get HH:mm from HH:mm:ss

        $appointments[] = [
            'appointment_id' => $row['appointment_id'],  // This line needs to be added to capture the appointment ID
            'heure' => $formattedHeure,
            'start' => $start,
            'nom' => $row['nom_patient'],
            'title' => "{$row['nom_patient']} {$row['prenom_patient']}",
            'doctor' => $row['nom_docteur'],
            'phone' => $row['phone'],
            'motif' => $row['motif'],
            'date' => $row['date'],
            'prenom' => $row['prenom_patient'],
        ];
        
    }
}

header('Content-Type: application/json');
echo json_encode($appointments);

$conn->close();
?>
