<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // Get the patient ID from the form

    // Delete the patient record from the patient_registry table
    $delete_query = "DELETE FROM patient_registry WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Optionally, delete related records in patient_services
        $delete_services_query = "DELETE FROM patient_services WHERE patient_id = ?";
        $services_stmt = $conn->prepare($delete_services_query);
        $services_stmt->bind_param("i", $id);
        $services_stmt->execute();

        // Redirect with a success message
        echo "<script>alert('Patient record deleted successfully!');</script>";
        echo "<script>window.location.href = 'patient_records.php';</script>";
    } else {
        // Handle errors
        echo "Error deleting patient record: " . $conn->error;
    }
} else {
    // Redirect to patient records if accessed directly
    header("Location: patient_records.php");
    exit;
}
?>
