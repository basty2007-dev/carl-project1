<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Information System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --deep-slate: #0f172a;
            --electric-indigo: #6366f1;
            --glass-white: rgba(255, 255, 255, 0.95);
        }
        body { background: #fdfeff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .system-header {
            background: var(--deep-slate); padding: 60px 0 100px; text-align: center; color: white;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%); margin-bottom: -60px;
        }
        .glass-panel {
            background: var(--glass-white); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 35px; box-shadow: 20px 20px 60px #d1d9e6, -20px -20px 60px #ffffff; padding: 40px; margin-bottom: 50px;
        }
        .search-wrapper {
            background: white; border-radius: 20px; padding: 8px 15px; display: flex; align-items: center;
            box-shadow: inset 2px 2px 5px #d1d9e6, inset -2px -2px 5px #ffffff; border: 1px solid #e2e8f0;
        }
        .search-input { border: none; outline: none; width: 100%; padding: 10px; font-weight: 600; color: var(--deep-slate); }
        .btn-custom { padding: 12px 22px; border-radius: 16px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: 0.3s; }
        .btn-back-home { background: #f1f5f9; color: var(--deep-slate); border: 1px solid #e2e8f0; }
        .btn-back-home:hover { background: #e2e8f0; transform: translateX(-5px); }
        .btn-register-main { background: var(--electric-indigo); color: white; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); }
        .btn-register-main:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3); color: white; }
        .custom-table { margin-top: 30px; width: 100%; border-collapse: separate; border-spacing: 0 15px; }
        .custom-table tbody tr { background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.02); transition: 0.3s; }
        .custom-table tbody tr:hover { transform: scale(1.01); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .custom-table td { padding: 20px; vertical-align: middle; }
        .custom-table td:first-child { border-radius: 20px 0 0 20px; }
        .custom-table td:last-child { border-radius: 0 20px 20px 0; }
        .table-avatar-squircle { width: 55px; height: 55px; border-radius: 18px; object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .status-badge { padding: 6px 16px; border-radius: 10px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .status-Active { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .status-Inactive { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .action-icon-btn { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; background: #f8fafc; color: #64748b; transition: 0.2s; text-decoration: none; border: 1px solid #e2e8f0; }
        .icon-view:hover { background: #6366f1; color: white; }
        .icon-edit:hover { background: #f59e0b; color: white; }
        .icon-delete:hover { background: #ef4444; color: white; }
    </style>
</head>
<body>