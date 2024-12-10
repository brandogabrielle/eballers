<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient_records.css">
    <title>Patient Records</title>
</head>
<body>

<div class="container">
    <div class="header-and-form">
        <h3>Patient Records</h3>
        <form method="GET">
            <input type="text" name="search" placeholder="Enter search term" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" required>
            <select name="search_category">
                <option value="name" <?php echo (isset($_GET['search_category']) && $_GET['search_category'] == 'name') ? 'selected' : ''; ?>>Search in Name</option>
                <option value="services" <?php echo (isset($_GET['search_category']) && $_GET['search_category'] == 'services') ? 'selected' : ''; ?>>Search in Services</option>
                <option value="address" <?php echo (isset($_GET['search_category']) && $_GET['search_category'] == 'address') ? 'selected' : ''; ?>>Search in Address</option>
            </select>
            <button type="submit">Search</button>
            <a href="patient_records.php">Reset</a>
        </form>
    </div>

    <div class="half">
        <?php
        include('db_connection.php'); 

        // Pagination settings
        $records_per_page = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $records_per_page;

        // Fetch search term and category
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $searchCategory = isset($_GET['search_category']) ? $_GET['search_category'] : 'name';

        // Base SQL query
        $sql = "SELECT p.*, sp.full_name, sp.address, sp.services, sp.created_at 
                FROM searchable_patients sp
                JOIN patient_registry p ON sp.patient_id = p.id";

        // Apply filters
        if (!empty($searchTerm)) {
            $searchTerm = "%" . $searchTerm . "%";
            if ($searchCategory == 'name') {
                $sql .= " WHERE sp.full_name LIKE ?";
            } elseif ($searchCategory == 'services') {
                $sql .= " JOIN patient_services ps ON sp.patient_id = ps.patient_id
                          JOIN services s ON ps.service_id = s.service_id
                          LEFT JOIN service_options so ON ps.option_id = so.option_id
                          WHERE s.service_name LIKE ? OR so.option_name LIKE ?";
            } elseif ($searchCategory == 'address') {
                $sql .= " WHERE sp.address LIKE ?";
            }
        }

        // Apply pagination
        $sql .= " LIMIT ?, ?";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        if (!empty($searchTerm)) {
            if ($searchCategory == 'services') {
                $stmt->bind_param("ssii", $searchTerm, $searchTerm, $offset, $records_per_page);
            } else {
                $stmt->bind_param("ssi", $searchTerm, $offset, $records_per_page);
            }
        } else {
            $stmt->bind_param("ii", $offset, $records_per_page);
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='record'>";
                echo "<div><label>Registered Date:</label> " . $row["created_at"] . "</div>";
                echo "<div><label>Name:</label> " . $row["full_name"] . "</div>";
                echo "<div><label>Birthday:</label> " . $row["dob"] . "</div>";
                echo "<div><label>Age:</label> " . $row["age"] . "</div>";
                echo "<div><label>Address:</label> " . $row["address"] . "</div>";
                echo "<div><label>Email:</label> " . $row["email"] . "</div>";
                echo "<div><label>Contact Number:</label> " . $row["mobile"] . "</div>";
                echo "<div><label>Additional Info:</label> " . $row["add_info"] . "</div>";

                // Fetch services
                $patient_id = $row['id'];
                $services_query = "SELECT services.service_name, service_options.option_name
                                    FROM patient_services
                                    JOIN services ON patient_services.service_id = services.service_id
                                    LEFT JOIN service_options ON patient_services.option_id = service_options.option_id
                                    WHERE patient_services.patient_id = ?";
                $services_stmt = $conn->prepare($services_query);
                $services_stmt->bind_param("i", $patient_id);
                $services_stmt->execute();
                $services_result = $services_stmt->get_result();

                echo "<div><label>Service/s:</label>";
                $displayed_services = [];

                while ($service_row = $services_result->fetch_assoc()) {
                    $service_name = $service_row['service_name'];
                    $option_name = $service_row['option_name'];

                    if (!empty($option_name)) {
                        if (!isset($displayed_services[$service_name])) {
                            $displayed_services[$service_name] = [];
                        }
                        $displayed_services[$service_name][] = $option_name;
                    }
                }

                foreach ($displayed_services as $service => $options) {
                    echo "<div>" . $service . ": " . implode(", ", $options) . "</div>";
                }

                echo "</div>"; 


                echo "<div style='display: flex; gap: 10px; margin-top: 10px;'>";
                echo "<button class=\"edit-button\" onclick=\"window.location.href='records_edit.php?id=" . $row["id"] . "'\">Edit</button>";
                echo "<form action=\"record_delete.php\" method=\"POST\" onsubmit=\"return confirm('Are you sure you want to delete this record?');\" style='margin: 0;'>";
                echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["id"] . "\">";
                echo "<button type=\"submit\" class=\"delete-button\">Delete</button>";
                echo "</form>";
                echo "<button class=\"print-button\" onclick=\"promptPrint('" . $row["id"] . "')\">Print</button>";
                echo "</div>"; 

                echo "</div>"; 
            }
        } else {
            echo "<p>No records found.</p>";
        }

        // Pagination links
        $total_records_sql = "SELECT COUNT(*) as total FROM searchable_patients";
        $stmt = $conn->prepare($total_records_sql);
        $stmt->execute();
        $total_result = $stmt->get_result();
        $total_row = $total_result->fetch_assoc();
        $total_records = $total_row['total'];
        $total_pages = ceil($total_records / $records_per_page);

        ?>
    </div>

    <!-- Pagination -->
    <div class="page-selector">
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            $activeClass = ($i == $page) ? "active-page" : "";
            echo "<a href='?page=$i&search=" . htmlspecialchars($searchTerm) . "&search_category=" . htmlspecialchars($searchCategory) . "' class='$activeClass'>$i</a> ";
        }
        ?>
    </div>

    <!-- Registry Button -->
    <div class="bruh-button">
        <a href="patient_registry_form.php">Go to Registry</a>
    </div>

</div>
<script>
function promptPrint(recordId) {
    const choice = confirm("Do you want to print the full page? Click 'Cancel' to print only the record.");
    if (choice) {
        // Full page print logic (Redirect to a full-page print version if needed)
        window.location.href = 'print_all_records.php'; // Adjust this to your full-page print handler
    } else {
        // Redirect to the single record's print page
        window.location.href = 'print_record.php?id=' + recordId;
    }
}
</script>
</body>
</html>
