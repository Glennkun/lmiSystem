<?php
require_once 'db_connect/lmi_db.php';

// Get quick statistics for dashboard
$stats = [];

try {
    // Total entries
    $stmt = $conn->query("SELECT COUNT(*) as total FROM entries");
    $stats['total_entries'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Entries added today
    $stmt = $conn->query("SELECT COUNT(*) as today FROM entries WHERE DATE(dateEncoded) = CURDATE()");
    $stats['today_entries'] = $stmt->fetch(PDO::FETCH_ASSOC)['today'];

    // Employment status breakdown
    $stmt = $conn->query("SELECT employmentStatus, COUNT(*) as count FROM entries GROUP BY employmentStatus");
    $stats['employment_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Recent entries
    $stmt = $conn->query("SELECT * FROM entries ORDER BY dateEncoded DESC LIMIT 5");
    $stats['recent_entries'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS System - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { padding-top: 60px; }
        .dashboard-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #5f9ea0;
        }
        .quick-actions .btn {
            margin: 5px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Welcome to LMS System</h1>
                <p class="lead">Manage and track labor market information efficiently.</p>
            </div>
            <div class="col-md-4">
                <div class="quick-actions text-end">
                    <a href="form.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> New Entry
                    </a>
                    <a href="report.php" class="btn btn-secondary">
                        <i class="fas fa-chart-bar"></i> View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users card-icon"></i>
                        <h5 class="card-title">Total Entries</h5>
                        <h2 class="card-text"><?php echo $stats['total_entries'] ?? 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus card-icon"></i>
                        <h5 class="card-title">New Today</h5>
                        <h2 class="card-text"><?php echo $stats['today_entries'] ?? 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-pie card-icon"></i>
                        <h5 class="card-title">Employment Rate</h5>
                        <?php
                        $employed = 0;
                        $total = 0;
                        foreach ($stats['employment_status'] ?? [] as $status) {
                            if ($status['employmentStatus'] == 'Employed') {
                                $employed = $status['count'];
                            }
                            $total += $status['count'];
                        }
                        $rate = $total > 0 ? round(($employed / $total) * 100, 1) : 0;
                        ?>
                        <h2 class="card-text"><?php echo $rate; ?>%</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Entries Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Entries</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Occupation</th>
                                        <th>Employment Status</th>
                                        <th>Date Encoded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($stats['recent_entries'])): ?>
                                        <?php foreach ($stats['recent_entries'] as $entry): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($entry['firstName'] . ' ' . $entry['mi'] . ' ' . $entry['lastName']); ?></td>
                                                <td><?php echo htmlspecialchars($entry['occupation']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $entry['employmentStatus'] == 'Employed' ? 'bg-success' : 'bg-secondary'; ?>">
                                                        <?php echo htmlspecialchars($entry['employmentStatus']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($entry['dateEncoded'])); ?></td>
                                                <td>
                                                    <a href="form.php?edit=<?php echo $entry['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteEntry(<?php echo $entry['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No entries found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
        function deleteEntry(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_entry.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire(
                                'Deleted!',
                                'Entry has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to delete entry.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>