<?php
require_once 'db_connect/lmi_db.php';

// Function to get statistics
function getStatistics($conn) {
    $stats = [];
    
    // Total entries
    $stmt = $conn->query("SELECT COUNT(*) as total FROM form_data");
    $stats['total_entries'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Employment status distribution
    $stmt = $conn->query("SELECT employmentStatus, COUNT(*) as count 
                         FROM form_data GROUP BY employmentStatus");
    $stats['employment_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Education level distribution
    $stmt = $conn->query("SELECT educational, COUNT(*) as count 
                         FROM form_data GROUP BY educational");
    $stats['education_levels'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $stats;
}

// Get date range if provided
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

$whereClause = "";
if ($startDate && $endDate) {
    $whereClause = "WHERE dateEncoded BETWEEN :startDate AND :endDate";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report - LMS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2>Generate Report</h2>
        
        <!-- Date Range Filter -->
        <form class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo $startDate; ?>">
                </div>
                <div class="col-md-4">
                    <label>End Date:</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo $endDate; ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </div>
            </div>
        </form>

        <!-- Statistics Summary -->
        <?php $stats = getStatistics($conn); ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Entries</h5>
                        <p class="card-text"><?php echo $stats['total_entries']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Employment Status Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Employment Status Distribution</h5>
                        <canvas id="employmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="mt-4">
            <h3>Detailed Report</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Occupation</th>
                            <th>Employment Status</th>
                            <th>Education</th>
                            <th>Date Encoded</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM entries $whereClause ORDER BY dateEncoded DESC";
                        $stmt = $conn->prepare($query);
                        if ($whereClause) {
                            $stmt->bindParam(':startDate', $startDate);
                            $stmt->bindParam(':endDate', $endDate);
                        }
                        $stmt->execute();
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['firstName'] . " " . $row['mi'] . " " . $row['lastName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['occupation']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['employmentStatus']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['educational']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dateEncoded']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize employment status chart
        const employmentData = <?php echo json_encode($stats['employment_status']); ?>;
        const ctx = document.getElementById('employmentChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: employmentData.map(item => item.employmentStatus),
                datasets: [{
                    data: employmentData.map(item => item.count),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });
    </script>
</body>
</html>