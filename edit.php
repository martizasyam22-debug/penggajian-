<?php
require_once 'auth.php';
requireAdmin();

include_once("connection.php");

if (isset($_POST['update'])) {
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $join_date = mysqli_real_escape_string($conn, $_POST['join_date']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $basic_pay = mysqli_real_escape_string($conn, $_POST['basic_pay']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if (empty($name) || empty($gender) || empty($email) || empty($city) || empty($birth_date) || empty($join_date) || empty($province) || empty($basic_pay) || empty($zip_code) || empty($address) || empty($employee_id)) {
        $error = "Semua field harus diisi";
    } else {
        $result = mysqli_query($conn, "UPDATE employee SET name='$name', gender='$gender', email='$email', birth_date='$birth_date', website='$website', province='$province', postal_code='$zip_code', city='$city', address='$address', join_date='$join_date', annual_basic_pay='$basic_pay' WHERE employee_id=$employee_id");
        
        // Update tax calculations
        mysqli_query($conn, "UPDATE employee SET tax = 5 WHERE employee_id = '$employee_id'");
        mysqli_query($conn, "UPDATE employee SET tax_amount = (annual_basic_pay/12) * (tax/100) WHERE employee_id = '$employee_id'");
        mysqli_query($conn, "UPDATE employee SET monthly_pay = (annual_basic_pay/12) - tax_amount WHERE employee_id = '$employee_id'");
        
        header("Location: payroll.php#employees");
        exit();
    }
}

// Get employee data
$employee_id = $_GET['employee_id'];
$result = mysqli_query($conn, "SELECT * FROM employee WHERE employee_id=$employee_id");
$row = mysqli_fetch_assoc($result);

$name = $row['name'];
$gender = $row['gender'];
$email = $row['email'];
$birth_date = $row['birth_date'];
$website = $row['website'];
$address = $row['address'];
$province = $row['province'];
$city = $row['city'];
$zip_code = $row['postal_code'];
$join_date = $row['join_date'];
$basic_pay = $row['annual_basic_pay'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan - Sistem Manajemen Gaji</title>
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
            padding: 32px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }

        .btn-back {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 32px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input, .form-select {
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #ffffff;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        }

        .form-input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-select {
            cursor: pointer;
        }

        .form-select option {
            background: #1a1a2e;
            color: #ffffff;
        }

        .form-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 6px;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 32px;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
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

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <a href="payroll.php" class="btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Edit Karyawan</h1>
        </div>

        <div class="form-card">
            <?php if (isset($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="edit.php">
                <input type="hidden" name="employee_id" value="<?= $employee_id ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ID Karyawan</label>
                        <input type="text" class="form-input" value="<?= $employee_id; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-input" value="<?= $birth_date; ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" <?= $gender == 'Male' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Female" <?= $gender == 'Female' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Website (Opsional)</label>
                        <input type="text" name="website" class="form-input" value="<?= htmlspecialchars($website); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-input" value="<?= htmlspecialchars($address); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kota</label>
                        <input type="text" name="city" class="form-input" value="<?= htmlspecialchars($city); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Provinsi</label>
                        <select name="province" class="form-select" required>
                            <option value="DKI Jakarta" <?= $province == 'DKI Jakarta' ? 'selected' : ''; ?>>DKI Jakarta</option>
                            <option value="Jawa Barat" <?= $province == 'Jawa Barat' ? 'selected' : ''; ?>>Jawa Barat</option>
                            <option value="Jawa Tengah" <?= $province == 'Jawa Tengah' ? 'selected' : ''; ?>>Jawa Tengah</option>
                            <option value="Jawa Timur" <?= $province == 'Jawa Timur' ? 'selected' : ''; ?>>Jawa Timur</option>
                            <option value="Yogyakarta" <?= $province == 'Yogyakarta' ? 'selected' : ''; ?>>Yogyakarta</option>
                            <option value="Bali" <?= $province == 'Bali' ? 'selected' : ''; ?>>Bali</option>
                            <option value="Sumatera Utara" <?= $province == 'Sumatera Utara' ? 'selected' : ''; ?>>Sumatera Utara</option>
                            <option value="Sumatera Selatan" <?= $province == 'Sumatera Selatan' ? 'selected' : ''; ?>>Sumatera Selatan</option>
                            <option value="Kalimantan Selatan" <?= $province == 'Kalimantan Selatan' ? 'selected' : ''; ?>>Kalimantan Selatan</option>
                            <option value="Sulawesi Utara" <?= $province == 'Sulawesi Utara' ? 'selected' : ''; ?>>Sulawesi Utara</option>
                            <option value="Aceh" <?= $province == 'Aceh' ? 'selected' : ''; ?>>Aceh</option>
                            <option value="Riau" <?= $province == 'Riau' ? 'selected' : ''; ?>>Riau</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="zip" class="form-input" value="<?= htmlspecialchars($zip_code); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="join_date" class="form-input" value="<?= $join_date; ?>" required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Gaji Pokok Tahunan (Rp)</label>
                        <input type="number" name="basic_pay" class="form-input" value="<?= $basic_pay; ?>" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="payroll.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
