<?php
include 'connect.php';

// Assuming you have a function to connect to the database


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query to delete the appointment from the database
    $query = "DELETE FROM appointments WHERE id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Success: Redirect back or show success message
            header('Location: /calendrier/days.html'); // Redirect to a success page
            exit;
        } else {
            // Error: Show error message
            echo "Error deleting appointment!";
        }
    }
}
?>
