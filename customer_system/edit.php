<?php
session_start(); // KANI ANG PINAKA-IMPORTANTE para sa alerts!
require_once "db.php";

// 1. Kuhaon ang ID gikan sa URL
$id = (int)($_GET['id'] ?? 0);

// 2. Query para makuha ang karaan nga detalye sa customer
$res = $conn->query("SELECT * FROM customers WHERE id=$id");
$row = $res->fetch_assoc();

if (!$row) { header("Location: index.php"); exit; }

// 3. Inig click sa Update button
if (isset($_POST['update'])) {
    $fn = trim($_POST['fname']);
    $ln = trim($_POST['lname']);
    $em = trim($_POST['email']);
    $cn = trim($_POST['contact']);
    $ad = trim($_POST['address']);
    
    // Default: Gamiton ang karaan nga photo filename
    $photoName = $row['profile_photo']; 

    // PHOTO UPLOAD LOGIC
    if (!empty($_FILES['photo']['name'])) {
        $newPhotoName = time() . "_" . $_FILES['photo']['name'];
        $target = "uploads/" . $newPhotoName;
        
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            // Papason ang karaan nga picture kung dili default.png
            if (!empty($row['profile_photo']) && $row['profile_photo'] != 'default.png' && file_exists("uploads/" . $row['profile_photo'])) {
                unlink("uploads/" . $row['profile_photo']);
            }
            $photoName = $newPhotoName;
        }
    }

    // UPDATE QUERY
    $stmt = $conn->prepare("UPDATE customers SET profile_photo=?, first_name=?, last_name=?, email=?, contact_no=?, address=? WHERE id=?");
    $stmt->bind_param("ssssssi", $photoName, $fn, $ln, $em, $cn, $ad, $id);
    
    if($stmt->execute()) {
        // KANI ANG SAKTO NGA PAGBUTANG SA ALERT MESSAGE
        $_SESSION['msg'] = "Customer updated successfully!";
        $_SESSION['msg_type'] = "info"; // Blue color para sa updates
        
        header("Location: index.php");
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .profile-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ffc107;
        }
    </style>
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="card mx-auto shadow-sm" style="max-width: 600px; border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-warning py-3 text-center">
            <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Customer Information</h4>
        </div>
        <div class="card-body p-4 text-dark bg-white">
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                
                <div class="mb-4 text-center">
                    <label class="form-label d-block fw-bold mb-3 text-secondary text-uppercase small">Profile Picture</label>
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <?php if(!empty($row['profile_photo']) && file_exists("uploads/".$row['profile_photo'])): ?>
                            <img src="uploads/<?= $row['profile_photo'] ?>" class="profile-preview shadow-sm">
                        <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center profile-preview shadow-sm" style="font-size: 1.5rem;">
                                <?= strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-start">
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                            <div class="form-text mt-1 small">Leave blank if you don't want to change the photo.</div>
                        </div>
                    </div>
                </div>

                <hr class="mb-4 opacity-10">

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">CUSTOMER CODE</label>
                    <input type="text" class="form-control bg-light fw-bold text-primary" value="<?= $row['customer_code'] ?>" readonly>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="fname" class="form-control" value="<?= htmlspecialchars($row['first_name']) ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="lname" class="form-control" value="<?= htmlspecialchars($row['last_name']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact No.</label>
                    <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($row['contact_no']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Home Address</label>
                    <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($row['address']) ?></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="update" class="btn btn-warning fw-bold py-2 shadow-sm">
                        <i class="bi bi-save2 me-2"></i>Update Records
                    </button>
                    <a href="index.php" class="btn btn-light border fw-semibold py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>