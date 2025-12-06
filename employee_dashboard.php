<?php
require_once 'auth.php';
requireEmployee();

include("connection.php");

$employee_id = getCurrentEmployeeId();

// Get employee data
$result = mysqli_query($conn, "SELECT * FROM employee WHERE employee_id = $employee_id");
$employee = mysqli_fetch_assoc($result);

// Get salary slips
$slips_result = mysqli_query($conn, "SELECT * FROM salary_slips WHERE employee_id = $employee_id ORDER BY year DESC, month DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - Sistem Manajemen Gaji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            color: #ffffff;
        }

        /* Header */
        .header {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-name {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-logout {
            padding: 10px 20px;
            background: rgba(248, 113, 113, 0.2);
            color: #f87171;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(248, 113, 113, 0.3);
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px;
        }

        /* Profile Card */
        .profile-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 32px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 32px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 32px;
            align-items: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }

        .profile-info h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .profile-info p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .profile-stats {
            display: flex;
            gap: 32px;
            margin-top: 20px;
        }

        .profile-stat {
            text-align: center;
        }

        .profile-stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .profile-stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Section */
        .section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.6);
            background: rgba(0, 0, 0, 0.2);
        }

        td {
            font-size: 14px;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-published {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .status-draft {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        /* Salary Card */
        .salary-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 24px;
        }

        .salary-card-header {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
        }

        .salary-card-value {
            font-size: 32px;
            font-weight: 700;
        }

        .salary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 24px;
            color: rgba(255, 255, 255, 0.4);
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-card {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .profile-avatar {
                margin: 0 auto;
            }

            .profile-stats {
                justify-content: center;
            }

            .main-content {
                padding: 16px;
            }

            .header {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-brand">
            <div class="header-logo">
                <i class="fas fa-wallet"></i>
            </div>
            <span class="header-title">Sistem Manajemen Gaji</span>
        </div>
        <div class="header-user">
            <span class="user-name">Halo, <?= htmlspecialchars($employee['name']) ?></span>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars($employee['name']) ?></h1>
                <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($employee['email']) ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($employee['city']) ?>, <?= htmlspecialchars($employee['province']) ?></p>
                <p><i class="fas fa-calendar-alt"></i> Bergabung: <?= $employee['join_date'] ?></p>
                
                <div class="profile-stats">
                    <div class="profile-stat">
                        <div class="profile-stat-value"><?= formatRupiah($employee['annual_basic_pay']) ?></div>
                        <div class="profile-stat-label">Gaji Pokok Tahunan</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-value"><?= $employee['tax'] ?>%</div>
                        <div class="profile-stat-label">Pajak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Summary -->
        <div class="salary-grid">
            <div class="salary-card">
                <div class="salary-card-header">Gaji Pokok Bulanan</div>
                <div class="salary-card-value"><?= formatRupiah($employee['annual_basic_pay'] / 12) ?></div>
            </div>
            <div class="salary-card">
                <div class="salary-card-header">Gaji Bersih Bulanan</div>
                <div class="salary-card-value"><?= formatRupiah($employee['monthly_pay']) ?></div>
            </div>
            <div class="salary-card">
                <div class="salary-card-header">Potongan Pajak</div>
                <div class="salary-card-value"><?= formatRupiah($employee['tax_amount']) ?></div>
            </div>
        </div>

        <!-- Salary Slips History -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-file-invoice-dollar"></i> Riwayat Slip Gaji</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Potongan</th>
                            <th>Pajak</th>
                            <th>Gaji Bersih</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($slips_result) > 0): ?>
                            <?php while ($slip = mysqli_fetch_assoc($slips_result)): ?>
                            <tr>
                                <td><?= getMonthName($slip['month']) ?> <?= $slip['year'] ?></td>
                                <td><?= formatRupiah($slip['basic_pay']) ?></td>
                                <td><?= formatRupiah($slip['allowance']) ?></td>
                                <td><?= formatRupiah($slip['deduction']) ?></td>
                                <td><?= formatRupiah($slip['tax_amount']) ?> (<?= $slip['tax_percent'] ?>%)</td>
                                <td><strong><?= formatRupiah($slip['net_pay']) ?></strong></td>
                                <td>
                                    <span class="status-badge status-<?= $slip['status'] ?>">
                                        <?= $slip['status'] === 'published' ? 'Diterbitkan' : 'Draft' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: rgba(255,255,255,0.5); padding: 40px;">
                                    <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                    Belum ada slip gaji yang tersedia
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            &copy; 2025 Sistem Manajemen Gaji — PT. Pencari Cinta Sejati
        </footer>
    </main>
</body>
</html>
