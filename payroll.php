<?php
require_once 'auth.php';
requireAdmin();

include("connection.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Manajemen Gaji</title>
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

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 24px;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-header {
            text-align: center;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 24px;
        }

        .sidebar-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .sidebar-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .sidebar-role {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
        }

        .nav-menu {
            list-style: none;
            flex: 1;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(102, 126, 234, 0.2);
            color: #ffffff;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 16px;
        }

        .nav-logout {
            margin-top: auto;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-logout .nav-link {
            color: #f87171;
        }

        .nav-logout .nav-link:hover {
            background: rgba(248, 113, 113, 0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 32px;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        /* Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-card-icon.blue { background: rgba(102, 126, 234, 0.2); color: #667eea; }
        .stat-card-icon.green { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
        .stat-card-icon.purple { background: rgba(168, 85, 247, 0.2); color: #a855f7; }
        .stat-card-icon.orange { background: rgba(251, 146, 60, 0.2); color: #fb923c; }

        .stat-card-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-card-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
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

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-sm {
            padding: 8px 14px;
            font-size: 13px;
        }

        .btn-edit {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .btn-edit:hover {
            background: rgba(59, 130, 246, 0.3);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .btn-export {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .btn-export:hover {
            background: rgba(34, 197, 94, 0.3);
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

        .actions {
            display: flex;
            gap: 8px;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
            border-radius: 20px;
            padding: 48px;
            margin-bottom: 32px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            margin-bottom: 24px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
                padding: 16px;
            }

            .sidebar-header {
                display: none;
            }

            .nav-link span {
                display: none;
            }

            .nav-link i {
                margin-right: 0;
            }

            .main-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="sidebar-name"><?= htmlspecialchars(getCurrentUserName()) ?></div>
            <div class="sidebar-role">Administrator</div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#top" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="insert.php" class="nav-link">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#employees" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Daftar Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#payslips" class="nav-link">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Slip Gaji</span>
                </a>
            </li>
        </ul>

        <div class="nav-logout">
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section" id="top">
            <h1 class="hero-title">Selamat Datang di Sistem Manajemen Gaji</h1>
            <p class="hero-subtitle">PT. Pencari Cinta Sejati — 2025</p>
            <a href="insert.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Karyawan Baru
            </a>
        </section>

        <!-- Stats Cards -->
        <?php
        $stats_employees = mysqli_query($conn, "SELECT COUNT(*) as total FROM employee");
        $total_employees = mysqli_fetch_assoc($stats_employees)['total'];
        
        $stats_salary = mysqli_query($conn, "SELECT SUM(annual_basic_pay) as total FROM employee");
        $total_salary = mysqli_fetch_assoc($stats_salary)['total'];
        
        $stats_slips = mysqli_query($conn, "SELECT COUNT(*) as total FROM salary_slips WHERE status='published'");
        $total_slips = mysqli_fetch_assoc($stats_slips)['total'];
        ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= $total_employees ?></div>
                <div class="stat-card-label">Total Karyawan</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon green">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= formatRupiah($total_salary) ?></div>
                <div class="stat-card-label">Total Gaji Pokok Tahunan</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon purple">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= $total_slips ?></div>
                <div class="stat-card-label">Slip Gaji Diterbitkan</div>
            </div>
        </div>

        <!-- Employee List -->
        <section class="section" id="employees">
            <div class="section-header">
                <h2 class="section-title">Daftar Karyawan</h2>
                <a href="insert.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Kota</th>
                            <th>Tanggal Masuk</th>
                            <th>Gaji Pokok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM employee ORDER BY employee_id ASC");
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?= $row['employee_id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['city']) ?></td>
                            <td><?= $row['join_date'] ?></td>
                            <td><?= formatRupiah($row['annual_basic_pay']) ?></td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?employee_id=<?= $row['employee_id'] ?>" class="btn btn-sm btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?employee_id=<?= $row['employee_id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus karyawan ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Payslips -->
        <section class="section" id="payslips">
            <div class="section-header">
                <h2 class="section-title">Slip Gaji Karyawan</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Gaji Pokok</th>
                            <th>Gaji Bersih</th>
                            <th>Pajak</th>
                            <th>Export</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM employee ORDER BY employee_id ASC");
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?= $row['employee_id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= formatRupiah($row['annual_basic_pay']) ?></td>
                            <td><?= formatRupiah($row['monthly_pay']) ?></td>
                            <td><?= $row['tax'] ?>%</td>
                            <td>
                                <a href="export1.php?employee_id=<?= $row['employee_id'] ?>" class="btn btn-sm btn-export" title="Export PDF">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Footer -->
        <footer style="text-align: center; padding: 24px; color: rgba(255,255,255,0.5); font-size: 13px;">
            &copy; 2025 Sistem Manajemen Gaji — PT. Pencari Cinta Sejati
        </footer>
    </main>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
                
                // Update active state
                document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>