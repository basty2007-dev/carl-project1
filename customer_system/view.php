<?php 
require_once "db.php"; 

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) { header("Location: index.php"); exit; }

include "includes/header.php"; 
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

<style>
    :root {
        --deep-slate: #0f172a;
        --electric-indigo: #6366f1;
        --glass-white: rgba(255, 255, 255, 0.95);
    }

    body {
        background: #fdfeff !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        overflow-x: hidden;
    }

    /* Asymmetric Dynamic Background */
    .bg-accent-shape {
        position: absolute;
        top: -10%;
        right: -5%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
        z-index: -1;
    }

    .main-nav-bg {
        background: var(--deep-slate);
        height: 120px;
        width: 100%;
        clip-path: polygon(0 0, 100% 0, 100% 80%, 0% 100%);
        margin-bottom: -60px;
    }

    /* Neumorphic Glass Card */
    .master-card {
        background: var(--glass-white);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 40px;
        box-shadow: 20px 20px 60px #d1d9e6, -20px -20px 60px #ffffff;
        padding: 50px;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .master-card:hover {
        transform: translateY(-10px);
    }

    /* Asymmetric Profile Section */
    .profile-flex {
        display: flex;
        align-items: center;
        gap: 40px;
        margin-bottom: 50px;
    }

    .frame-hex {
        width: 180px;
        height: 180px;
        background: white;
        padding: 8px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-radius: 50px; /* Modern Squircle */
        transform: rotate(-3deg);
        transition: 0.3s;
    }

    .frame-hex:hover {
        transform: rotate(0deg) scale(1.05);
    }

    .profile-img-final {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 42px;
    }

    .initials-large {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--deep-slate), #1e293b);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: 800;
        border-radius: 42px;
    }

    .hero-text h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: var(--deep-slate);
        margin-bottom: 5px;
    }

    /* Status Glow */
    .status-badge-glow {
        padding: 6px 20px;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .status-Active { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .status-Inactive { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* New Angles Info Boxes */
    .info-grid-modern {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .info-tile {
        background: #f8fafc;
        padding: 25px;
        border-radius: 24px;
        border: 1px solid #edf2f7;
        position: relative;
        overflow: hidden;
    }

    .info-tile::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        background: var(--electric-indigo);
        opacity: 0;
        transition: 0.3s;
    }

    .info-tile:hover::before { opacity: 1; }

    .label-caps {
        font-size: 0.7rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
        display: block;
    }

    .value-bold {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--deep-slate);
    }

    /* Action Deck */
    .action-deck {
        margin-top: 40px;
        display: flex;
        gap: 15px;
    }

    .btn-perfect {
        padding: 16px 35px;
        border-radius: 18px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }

    .btn-main {
        background: var(--deep-slate);
        color: white;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2);
    }

    .btn-main:hover {
        background: var(--electric-indigo);
        transform: translateY(-5px);
        color: white;
    }

    .btn-ghost {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-ghost:hover {
        background: #f1f5f9;
        color: var(--deep-slate);
    }

    @media (max-width: 768px) {
        .profile-flex { flex-direction: column; text-align: center; }
        .info-grid-modern { grid-template-columns: 1fr; }
    }
</style>

<div class="bg-accent-shape"></div>
<div class="main-nav-bg"></div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="master-card">
                
                <div class="profile-flex">
                    <div class="frame-hex">
                        <?php if(!empty($row['profile_photo']) && file_exists("uploads/".$row['profile_photo'])): ?>
                            <img src="uploads/<?= $row['profile_photo'] ?>" class="profile-img-final">
                        <?php else: ?>
                            <div class="initials-large">
                                <?= strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="hero-text">
                        <div class="status-badge-glow status-<?= $row['status'] ?> mb-3">
                            <i class="bi bi-patch-check-fill"></i> Verified <?= $row['status'] ?>
                        </div>
                        <h1><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h1>
                        <p class="text-muted fw-bold"><i class="bi bi-geo-alt-fill me-1 text-primary"></i> <?= htmlspecialchars($row['address']) ?></p>
                    </div>
                </div>

                <div class="info-grid-modern">
                    <div class="info-tile">
                        <span class="label-caps">Customer Code</span>
                        <div class="value-bold" style="color: var(--electric-indigo);">#<?= $row['customer_code'] ?></div>
                    </div>
                    
                    <div class="info-tile">
                        <span class="label-caps">Digital Address</span>
                        <div class="value-bold"><?= htmlspecialchars($row['email']) ?></div>
                    </div>

                    <div class="info-tile">
                        <span class="label-caps">Phone Line</span>
                        <div class="value-bold"><?= htmlspecialchars($row['contact_no']) ?></div>
                    </div>

                    <div class="info-tile">
                        <span class="label-caps">Account Status</span>
                        <div class="value-bold"><?= $row['status'] ?> Member</div>
                    </div>
                </div>

                <div class="action-deck">
                    <a href="index.php" class="btn-perfect btn-ghost">
                        <i class="bi bi-arrow-left-short fs-4"></i> Back to Fleet
                    </a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn-perfect btn-main">
                        <i class="bi bi-shield-lock-fill"></i> Modify Profile
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>