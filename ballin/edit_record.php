<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and collect form inputs
    $id = $_POST['id'];
    $registryDate = $_POST['registryDate'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $appointmentDate = $_POST['appointmentDate'];
    $addInfo = $_POST['addInfo'];

    // Update the patient_registry table
    $update_query = "UPDATE patient_registry SET registry_date = ?, last_name = ?, first_name = ?, middle_name = ?, dob = ?, age = ?, email = ?, address = ?, mobile = ?, appointment_date = ?, add_info = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssisssssi", $registryDate, $lastName, $firstName, $middleName, $dob, $age, $email, $address, $mobile, $appointmentDate, $addInfo, $id);

    if ($stmt->execute()) {
        // Clear existing services for the patient
        $delete_services_query = "DELETE FROM patient_services WHERE patient_id = ?";
        $delete_stmt = $conn->prepare($delete_services_query);
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();

        // Insert updated services
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $serviceId => $options) {
                foreach ($options as $optionId) {
                    $insert_service_query = "INSERT INTO patient_services (patient_id, service_id, option_id) VALUES (?, ?, ?)";
                    $insert_service_stmt = $conn->prepare($insert_service_query);
                    $insert_service_stmt->bind_param("iii", $id, $serviceId, $optionId);
                    $insert_service_stmt->execute();
                }
            }
        }

        // Show success alert
        echo "<script>alert('Patient updated successfully!');</script>";

        // Optional: Redirect back after the alert
        echo "<script>window.location.href = 'patient_records.php';</script>";
        exit;
    } else {
        echo "Error updating patient: " . $conn->error;
    }
}
?>
s