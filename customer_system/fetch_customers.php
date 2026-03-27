<?php
require_once "db.php";

$search = $_POST['q'] ?? '';
$sort = $_POST['sort'] ?? 'newest';

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

if($result->num_rows > 0): 
    while($row = $result->fetch_assoc()): ?>
    <tr>
        <td class="ps-4">
            <?php if(!empty($row['profile_photo']) && file_exists("uploads/".$row['profile_photo'])): ?>
                <img src="uploads/<?= $row['profile_photo'] ?>" class="table-avatar-squircle" style="width:55px; height:55px; border-radius:18px; object-fit:cover;">
            <?php else: ?>
                <div class="table-avatar-squircle d-flex align-items-center justify-content-center bg-light fw-bold text-primary" style="width:55px; height:55px; border-radius:18px;">
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
        <td>
            <span class="status-badge status-<?= $row['status'] ?>" style="padding: 6px 16px; border-radius: 10px; font-size: 0.7rem; font-weight: 800;">
                ● <?= $row['status'] ?>
            </span>
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <a href="view.php?id=<?= $row['id'] ?>" class="action-icon-btn"><i class="bi bi-eye-fill"></i></a>
                <a href="edit.php?id=<?= $row['id'] ?>" class="action-icon-btn"><i class="bi bi-pencil-square"></i></a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="action-icon-btn text-danger" onclick="return confirm('Are you sure you want to delete this record?')"><i class="bi bi-trash3-fill"></i></a>
            </div>
        </td>
    </tr>
    <?php endwhile; 
else: ?>
    <tr><td colspan="6" class="text-center py-5 text-muted">Alaw lagi, basin ila gong2 aans</td></tr>
<?php endif; ?>