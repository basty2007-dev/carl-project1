<?php 
session_start(); 
require_once "db.php"; 
include "includes/header.php"; 

$search = $_GET['q'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$orderBy = "id DESC"; 

if($sort == 'code-asc') $orderBy = "customer_code ASC"; 
if($sort == 'code-desc') $orderBy = "customer_code DESC"; 
if($sort == 'name-asc') $orderBy = "first_name ASC";
if($sort == 'name-desc') $orderBy = "first_name DESC";
if($sort == 'newest') $orderBy = "id DESC"; 

$sql = "SELECT * FROM customers WHERE first_name LIKE ? OR last_name LIKE ? OR customer_code LIKE ? ORDER BY $orderBy";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="system-header">
    <div class="container">
        <h1 style="font-family: 'Space Grotesk'; font-weight: 700;">Customer Information System</h1>
        <p class="opacity-75">Profiles Management Dashboard</p>
    </div>
</div>

<div class="container">
    <div class="glass-panel">
        
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type']; ?> alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 15px;">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['msg']); unset($_SESSION['msg_type']); ?>
        <?php endif; ?>

        <div class="row g-3 align-items-center mb-2">
            <div class="col-auto" id="home-btn-wrapper" style="display: <?= !empty($search) ? 'block' : 'none' ?>;">
                <a href="index.php" class="btn-custom btn-back-home">
                    <i class="bi bi-house-door-fill"></i> Home
                </a>
            </div>

            <div class="col">
                <div class="search-wrapper">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" id="live-search" class="search-input" placeholder="Search name, email, or code..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
                </div>
            </div>

            <div class="col-md-3">
                <select id="sort-select" class="form-select border-0 shadow-sm" style="border-radius: 15px; padding: 12px;">
                    <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Newest Added</option>
                    <option value="code-asc" <?= $sort == 'code-asc' ? 'selected' : '' ?>>Customer Code (Asc)</option>
                    <option value="code-desc" <?= $sort == 'code-desc' ? 'selected' : '' ?>>Customer Code (Desc)</option>
                    <option value="name-asc" <?= $sort == 'name-asc' ? 'selected' : '' ?>>Name (A - Z)</option>
                    <option value="name-desc" <?= $sort == 'name-desc' ? 'selected' : '' ?>>Name (Z - A)</option>
                </select>
            </div>

            <div class="col-auto">
                <a href="add.php" class="btn-custom btn-register-main">
                    <i class="bi bi-person-plus-fill"></i> Add New Customer
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr class="text-muted small fw-bold">
                        <th class="ps-4">PROFILE</th>
                        <th>CODE</th>
                        <th>FULL DETAILS</th>
                        <th>CONTACT INFO</th>
                        <th>STATUS</th>
                        <th class="text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4">
                                <?php if(!empty($row['profile_photo']) && file_exists("uploads/".$row['profile_photo'])): ?>
                                    <img src="uploads/<?= $row['profile_photo'] ?>" class="table-avatar-squircle">
                                <?php else: ?>
                                    <div class="table-avatar-squircle d-flex align-items-center justify-content-center bg-light fw-bold text-primary">
                                        <?= strtoupper(substr($row['first_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><span class="fw-800 text-primary">#<?= $row['customer_code'] ?></span></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($row['email']) ?></div>
                            </td>
                            <td>
                                <div class="small fw-bold"><i class="bi bi-phone me-1"></i> <?= htmlspecialchars($row['contact_no']) ?></div>
                                <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($row['address']) ?></div>
                            </td>
                            <td><span class="status-badge status-<?= $row['status'] ?>">● <?= $row['status'] ?></span></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="view.php?id=<?= $row['id'] ?>" class="action-icon-btn icon-view" title="View"><i class="bi bi-eye-fill"></i></a>
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="action-icon-btn icon-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" class="action-icon-btn icon-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this record?')"><i class="bi bi-trash3-fill"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted small">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>