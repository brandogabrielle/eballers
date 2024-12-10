<?php
// Include the database connection
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and collect the form inputs
    $registryDate = $_POST['registryDate'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $appointmentDate = $_POST['appointmentDate'];
    $addInfo = htmlspecialchars($_POST['addInfo'], ENT_QUOTES, 'UTF-8');

    // Concatenate full name for easier searching
    $fullName = $firstName . " " . $middleName . " " . $lastName;

    // Create a search term by concatenating name, address, and any relevant fields for better search performance
    $searchTerm = strtolower($fullName . " " . $address . " " . $email);

    // Insert into patient_registry table
    $query = "INSERT INTO patient_registry (registry_date, first_name, middle_name, last_name, dob, age, email, address, mobile, appointment_date, add_info) 
              VALUES ('$registryDate', '$firstName', '$middleName', '$lastName', '$dob', '$age', '$email', '$address', '$mobile', '$appointmentDate', '$addInfo')";

    if ($conn->query($query) === TRUE) {
        // Get the patient id of the newly inserted patient
        $patientId = $conn->insert_id;  // This gets the ID of the last inserted row
        
        // Insert into searchable_patients table
        $insertSearchableQuery = "INSERT INTO searchable_patients (patient_id, full_name, address, services, search_term) 
                                  VALUES ('$patientId', '$fullName', '$address', '', '$searchTerm')";
        $conn->query($insertSearchableQuery);

        // Insert selected services and options (if applicable)
        $servicesList = [];
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $serviceId => $options) {
                foreach ($options as $optionId) {
                    // Insert the service selection into patient_services table
                    $insertServiceQuery = "INSERT INTO patient_services (patient_id, service_id, option_id) 
                                           VALUES ('$patientId', '$serviceId', '$optionId')";
                    $conn->query($insertServiceQuery);
                }

                // Fetch the service name for each service ID
                $serviceQuery = "SELECT service_name FROM services WHERE service_id = '$serviceId'";
                $serviceResult = $conn->query($serviceQuery);
                if ($serviceResult->num_rows > 0) {
                    $serviceRow = $serviceResult->fetch_assoc();
                    $servicesList[] = $serviceRow['service_name'];
                }
            }
        }

        // Concatenate the services into a comma-separated list
        $servicesString = implode(', ', $servicesList);

        // Update the searchable_patients table with the services
        $updateSearchableQuery = "UPDATE searchable_patients SET services = '$servicesString' WHERE patient_id = '$patientId'";
        $conn->query($updateSearchableQuery);

        // Redirect after successful registration
        echo "<script>alert('Patient Registered Successfully!'); window.location.href = 'patient_registry_form.php';</script>";
        exit;
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>
