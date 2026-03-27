<?php
session_start(); // Kinahanglan ni para sa Alerts
require_once "db.php";

// 1. Auto-generate Customer Code (Bonus Requirement)
$res = $conn->query("SELECT id FROM customers ORDER BY id DESC LIMIT 1");
$lastId = ($res->num_rows > 0) ? $res->fetch_assoc()['id'] + 1 : 1;
$custCode = "CUST-" . str_pad($lastId, 4, "0", STR_PAD_LEFT);

if (isset($_POST['save'])) {
    $fname   = trim($_POST['fname']);
    $lname   = trim($_POST['lname']);
    $email   = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $status  = $_POST['status']; 

    // 2. PHOTO UPLOAD LOGIC
    $photoName = "default.png"; // Default placeholder
    if (!empty($_FILES['photo']['name'])) {
        $photoName = time() . "_" . $_FILES['photo']['name'];
        $target = "uploads/" . $photoName;
        
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
    }

    // Validation (Required fields)
    if (!empty($fname) && !empty($lname) && !empty($email)) {
        // 3. INSERT Query
        $stmt = $conn->prepare("INSERT INTO customers (profile_photo, customer_code, first_name, last_name, email, contact_no, address, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $photoName, $custCode, $fname, $lname, $email, $contact, $address, $status);
        
        if ($stmt->execute()) {
            // KANI ANG DINUGANG PARA SA SUCCESS ALERT
            $_SESSION['msg'] = "Customer registered successfully!";
            $_SESSION['msg_type'] = "success";
            header("Location: index.php"); 
            exit;
        } else {
            $error = "Error saving record: " . $conn->error;
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; }
        .card { border-radius: 15px; border: none; }
        .form-control:focus { box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1); border-color: #198754; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger shadow-sm border-0 mb-3"><?= $error ?></div>
            <?php endif; ?>

            <div class="card shadow-lg">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold text-center"><i class="bi bi-person-plus-fill me-2"></i>Registration Form</h5>
                </div>
                <div class="card-body p-4">
                    <form action="add.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Customer Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control bg-light fw-bold" value="<?= $custCode ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Profile Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            <div class="form-text small">Default photo will be used if left blank.</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">First Name</label>
                                <input type="text" name="fname" class="form-control" required placeholder="Juan">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Last Name</label>
                                <input type="text" name="lname" class="form-control" required placeholder="Dela Cruz">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="juan@email.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Contact Number</label>
                            <input type="text" name="contact" class="form-control" required placeholder="09xxxxxxxxx">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Home Address</label>
                            <textarea name="address" class="form-control" rows="2" required placeholder="Street, City, Province"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Account Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="save" class="btn btn-success py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Save Customer Information
                            </button>
                            <a href="index.php" class="btn btn-light border py-2 text-secondary fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Cancel and Go Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>