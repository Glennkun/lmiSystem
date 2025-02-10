<?php
require_once 'db_connect/lmi_db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'delete') {
            try {
                $stmt = $conn->prepare("DELETE FROM form_data WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                echo json_encode(['status' => 'success']);
                exit;
            } catch(PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        } elseif ($_POST['action'] == 'edit') {
            try {
                $stmt = $conn->prepare("UPDATE form_data SET 
                    firstName = :firstName,
                    mi = :mi,
                    lastName = :lastName,
                    suffix = :suffix,
                    occupation = :occupation,
                    birthdate = :birthdate,
                    age = :age,
                    sex = :sex,
                    civilStatus = :civilStatus,
                    religion = :religion,
                    educational = :educational,
                    course = :course,
                    vocational = :vocational,
                    workExperience = :workExperience,
                    employmentStatus = :employmentStatus,
                    purok = :purok,
                    sitio = :sitio,
                    barangay = :barangay,
                    municipality = :municipality,
                    province = :province,
                    contact = :contact,
                    email = :email,
                    local_overseas = :local_overseas,
                    remarks = :remarks,
                    ojt_name = :ojt_name
                    WHERE id = :id");

                $stmt->execute([
                    ':id' => $_POST['id'],
                    ':firstName' => $_POST['firstName'],
                    ':mi' => $_POST['mi'],
                    ':lastName' => $_POST['lastName'],
                    ':suffix' => $_POST['suffix'],
                    ':occupation' => $_POST['occupation'],
                    ':birthdate' => $_POST['birthdate'],
                    ':age' => $_POST['age'],
                    ':sex' => $_POST['sex'],
                    ':civilStatus' => $_POST['civilStatus'],
                    ':religion' => $_POST['religion'],
                    ':educational' => $_POST['educational'],
                    ':course' => $_POST['course'],
                    ':vocational' => $_POST['vocational'],
                    ':workExperience' => $_POST['workExperience'],
                    ':employmentStatus' => $_POST['employmentStatus'],
                    ':purok' => $_POST['purok'],
                    ':sitio' => $_POST['sitio'],
                    ':barangay' => $_POST['barangay'],
                    ':municipality' => $_POST['municipality'],
                    ':province' => $_POST['province'],
                    ':contact' => $_POST['contact'],
                    ':email' => $_POST['email'],
                    ':local_overseas' => $_POST['local_overseas'],
                    ':remarks' => $_POST['remarks'],
                    ':ojt_name' => $_POST['ojt_name']
                ]);

                echo json_encode(['status' => 'success']);
                exit;
            } catch(PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        }
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO form_data (
                firstName, mi, lastName, suffix, occupation, 
                birthdate, age, sex, civilStatus, religion, 
                educational, course, vocational, workExperience, 
                employmentStatus, purok, sitio, barangay, 
                municipality, province, contact, email, 
                local_overseas, remarks, ojt_name, dateEncoded
            ) VALUES (
                :firstName, :mi, :lastName, :suffix, :occupation,
                :birthdate, :age, :sex, :civilStatus, :religion,
                :educational, :course, :vocational, :workExperience,
                :employmentStatus, :purok, :sitio, :barangay,
                :municipality, :province, :contact, :email,
                :local_overseas, :remarks, :ojt_name, :dateEncoded
            )");
            
            $stmt->execute([
                ':firstName' => $_POST['firstName'],
                ':mi' => $_POST['mi'],
                ':lastName' => $_POST['lastName'],
                ':suffix' => $_POST['suffix'] ?? 'N/A',
                ':occupation' => $_POST['occupation'] ?? 'N/A',
                ':birthdate' => $_POST['birthdate'],
                ':age' => $_POST['age'],
                ':sex' => $_POST['sex'],
                ':civilStatus' => $_POST['civilStatus'],
                ':religion' => $_POST['religion'] ?? 'N/A',
                ':educational' => $_POST['educational'] ?? 'N/A',
                ':course' => $_POST['course'] ?? 'N/A',
                ':vocational' => $_POST['vocational'] ?? 'N/A',
                ':workExperience' => $_POST['workExperience'] ?? 'N/A',
                ':employmentStatus' => $_POST['employmentStatus'],
                ':purok' => $_POST['purok'] ?? 'N/A',
                ':sitio' => $_POST['sitio'],
                ':barangay' => $_POST['barangay'] ?? 'N/A',
                ':municipality' => $_POST['municipality'],
                ':province' => $_POST['province'],
                ':contact' => $_POST['contact'] ?? 'N/A',
                ':email' => $_POST['email'] ?? 'N/A',
                ':local_overseas' => $_POST['local_overseas'],
                ':remarks' => $_POST['remarks'] ?? '',
                ':ojt_name' => $_POST['ojt_name'],
                ':dateEncoded' => $_POST['dateEncoded']
            ]);
            
            echo json_encode(['status' => 'success']);
            exit;
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }
}

// Handle GET requests for data
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'getLatest') {
        try {
            $stmt = $conn->query("SELECT * FROM form_data ORDER BY id DESC LIMIT 1");
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $data]);
            exit;
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    } elseif ($_GET['action'] == 'getByDate') {
        try {
            $stmt = $conn->prepare("SELECT * FROM form_data WHERE DATE(dateEncoded) = ? ORDER BY id DESC");
            $stmt->execute([$_GET['date']]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $data]);
            exit;
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    } elseif ($_GET['action'] == 'getById') {
        try {
            $stmt = $conn->prepare("SELECT * FROM form_data WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $data]);
            exit;
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Entry Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest Font Awesome (Free) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="css/form.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container mt-4"> 
        <form id="dataForm" class="border rounded p-3 needs-validation" novalidate>
            <div class="row g-2">
                <div class="col-md-3 form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstName" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter first name</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">MI</label>
                    <input type="text" name="mi" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter middle initial</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastName" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter last name</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Suffix</label>
                    <input type="text" name="suffix" class="form-control text-uppercase" value="N/A" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter suffix</div>
                </div>

                <div class="col-md-3 form-group">
                    <label class="form-label">Occupation</label>
                    <input type="text" name="occupation" class="form-control text-uppercase"  value="N/A" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter occupation</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Birthdate</label>
                    <input type="date" name="birthdate" class="form-control" required>
                    <div class="invalid-feedback">Please select birthdate</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Sex</label>
                    <select name="sex" class="form-control text-uppercase" required>
                        <option value="MALE">MALE</option>
                        <option value="FEMALE">FEMALE</option>
                    </select>
                    <div class="invalid-feedback">Please select sex</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Civil Status</label>
                    <select name="civilStatus" class="form-control text-uppercase" required>
                        <option value="SINGLE">SINGLE</option>
                        <option value="MARRIED">MARRIED</option>
                        <option value="WIDOWED">WIDOWED</option>
                    </select>
                    <div class="invalid-feedback">Please select civil status</div>
                </div>
                
                <div class="col-md-3 form-group">
                    <label class="form-label">Religion</label>
                    <input type="text" name="religion" class="form-control text-uppercase" value="N/A" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter religion</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Educational</label>
                    <input type="text" name="educational" class="form-control text-uppercase" value="N/A" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter educational attainment</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control text-uppercase" value="N/A" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter course</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Vocational</label>
                    <input type="text" name="vocational" class="form-control text-uppercase" value="N/A" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter vocational training</div>
                </div>
                
                <div class="col-md-3 form-group">
                    <label class="form-label">Work Experience</label>
                    <input type="text" name="workExperience" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase()">
                    <div class="invalid-feedback">Please enter work experience</div>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Employment Status</label>
                    <select name="employmentStatus" class="form-control text-uppercase" required>
                        <option value="EMPLOYED">EMPLOYED</option>
                        <option value="UNEMPLOYED">UNEMPLOYED</option>
                        <option value="SELF-EMPLOYED">SELF-EMPLOYED</option>
                    </select>
                    <div class="invalid-feedback">Please select employment status</div>
                </div>
                <div class="col-md-3 form-group">
    <label class="form-label">Purok</label>
    <input type="text" name="purok" class="form-control text-uppercase" value="N/A" required oninput="this.value = this.value.toUpperCase()">
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Sitio</label>
    <input type="text" name="sitio" class="form-control text-uppercase" oninput="this.value = this.value.toUpperCase()">
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Barangay</label>
    <input type="text" name="barangay" class="form-control text-uppercase" value="N/A" required oninput="this.value = this.value.toUpperCase()">
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Municipality</label>
    <input type="text" name="municipality" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase()">
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Province</label>
    <input type="text" name="province" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase()">
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Contact</label>
    <input type="text" name="contact" class="form-control" value="N/A" required>
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Email</label>
    <input type="text" name="email" class="form-control" value="N/A" required pattern="^([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}|N/A)$">
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Local/Overseas</label>
    <select name="local_overseas" class="form-control text-uppercase" required>
        <option value="LOCAL">LOCAL</option>
        <option value="OVERSEAS">OVERSEAS</option>
    </select>
</div>
<div class="col-md-3 form-group">
    <label class="form-label">Remarks</label>
    <textarea name="remarks" class="form-control text-uppercase" oninput="this.value = this.value.toUpperCase()"></textarea>
</div>
<input type="hidden" name="dateEncoded" value="">
<div class="col-md-3 form-group">
    <label class="form-label">OJT Name</label>
    <input type="text" name="ojt_name" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase()">
</div>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Entry
                </button>
            </div>
        </form>
     <!-- Table Section -->
     <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <input type="date" id="dateFilter" class="form-control form-control-sm me-2" value="<?php echo date('Y-m-d'); ?>">
                    <button id="exportBtn" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                </div>
                <button id="showTableBtn" class="btn btn-info btn-sm">
                    <i class="fas fa-table"></i> Show Today's Entries
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                <thead>
    <tr>
        <th>NO</th>
        <th>First Name</th>
        <th>Middle Name/Initial</th>
        <th>Last Name</th>
        <th>Suffix</th>
        <th>Occupation/Skill</th>
        <th>Birth Date</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Civil</th>
        <th>Religion</th>
        <th>Educational</th>
        <th>Course</th>
        <th>Vocational Course/Training</th>
        <th>Yrs. of Work Experience</th>
        <th>Employment Status</th>
        <th>Purok</th>
        <th>Sitio</th>
        <th>Barangay</th>
        <th>Municipality</th>
        <th>Province</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Local/Overseas</th>
        <th>Remarks</th>
        <th>Date Encoded</th>
        <th>OJT Name</th>
        <th>Actions</th>
    </tr>
</thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="js/form.js"></script>
</body>
</html>