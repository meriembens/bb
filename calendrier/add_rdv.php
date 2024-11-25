<?php
var_dump($_POST);
// Enable error reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include "connect.php"; // Database connection

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['prenom_patient'], $_POST['nom_patient'], $_POST['phone'], $_POST['nom_docteur'], $_POST['date'], $_POST['time'], $_POST['motif'])) {
        $prenom_patient = $_POST['prenom_patient'];
        $nom_patient = $_POST['nom_patient'];
        $phone = $_POST['phone'];
        $nom_docteur = $_POST['nom_docteur'];
        $motif = $_POST['motif'];
        $date = $_POST['date'];
        $time = $_POST['time'];


        

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO appointments (nom_patient, prenom_patient, phone, nom_docteur, date, heure, motif) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nom_patient, $prenom_patient, $phone, $nom_docteur, $date, $time, $motif);

        if ($stmt->execute()) {
            header("Location: /calendrier/calendar.html"); // Adjust the URL if needed
            exit();
        } else {
            echo "Erreur lors de la réservation : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>