<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient_registry.css">
    <title>Patient Registry</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Patient Registry</h1>
            <div class="bruh-button">
                <a href="patient_records.php">Go to Records</a>
            </div>
        </div>
        <form action="patient_registry.php" method="POST">
            <!-- Registry Date -->
            <div class="form-group">
                <label for="registryDate">Date of Registry:</label>
                <input type="date" id="registryDate" name="registryDate" required>
            </div>

            <div class="name-section">
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>

                <!-- Middle Name -->
                <div class="form-group">
                    <label for="middleName">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName">
                </div>
            </div>

            <!-- Date of Birth -->
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email">
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>

            <!-- Mobile Number -->
            <div class="form-group">
                <label for="mobile">Mobile Number:</label>
                <input type="tel" id="mobile" name="mobile" required>
            </div>

            <!-- Appointment Schedule -->
            <div class="form-group">
                <label for="appointmentDate">Appointment Schedule:</label>
                <input type="date" id="appointmentDate" name="appointmentDate" required>
            </div>

            <h2>Services:</h2>
            <div class="services-section">
                <?php
                include('db_connection.php');
                $services_query = "SELECT * FROM services";
                $services_result = $conn->query($services_query);

                while ($service = $services_result->fetch_assoc()) {
                    echo "<div class='service-group'>";
                    echo "<h3>{$service['service_name']}</h3>";

                    // Fetching the service options
                    $options_query = "SELECT * FROM service_options WHERE service_id = {$service['service_id']}";
                    $options_result = $conn->query($options_query);
                    echo "<ul>";
                    while ($option = $options_result->fetch_assoc()) {
                        echo "<li><label><input type='checkbox' name='services[{$service['service_id']}][]' value='{$option['option_id']}'> {$option['option_name']}</label></li>";
                    }
                    echo "</ul>";

                    echo "</div>"; // Closing service-group
                }
                ?>
            </div>


            <!-- Additional Info -->
            <div class="form-group">
                <label for="addInfo">Additional Info:</label>
                <textarea id="addInfo" name="addInfo"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="button-container">
                <button type="submit">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
