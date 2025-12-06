<?php
require_once 'auth.php';
requireAdmin();

$gender = "";
$name = "";
$email = "";
$birth_date = "";
$address = "";
$city = "";
$province = "";
$website = "";
$join_date = "";
$basic_pay = "";
$employee_id = "";
$zip_code = "";

$genderErr = "";
$nameErr = "";
$emailErr = "";
$birth_dateErr = "";
$addressErr = "";
$cityErr = "";
$provinceErr = "";
$websiteErr = "";
$join_dateErr = "";
$basic_payErr = "";
$employee_idErr = "";
$zip_codeErr = "";

$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hasError = false;
    
    if (empty($_POST["name"])) {
        $nameErr = "Nama harus diisi";
        $hasError = true;
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Hanya huruf dan spasi yang diperbolehkan";
            $hasError = true;
        }
    }

    if (empty($_POST["gender"])) {
        $genderErr = "Jenis kelamin harus dipilih";
        $hasError = true;
    } else {
        $gender = test_input($_POST["gender"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email harus diisi";
        $hasError = true;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Format email tidak valid";
            $hasError = true;
        }
    }

    if (empty($_POST["address"])) {
        $addressErr = "Alamat harus diisi";
        $hasError = true;
    } else {
        $address = test_input($_POST["address"]);
    }

    if (empty($_POST["city"])) {
        $cityErr = "Kota harus diisi";
        $hasError = true;
    } else {
        $city = test_input($_POST["city"]);
    }

    if (empty($_POST["province"])) {
        $provinceErr = "Provinsi harus dipilih";
        $hasError = true;
    } else {
        $province = test_input($_POST["province"]);
    }

    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
    }

    if (empty($_POST["basic_pay"])) {
        $basic_payErr = "Gaji pokok harus diisi";
        $hasError = true;
    } else {
        $basic_pay = test_input($_POST["basic_pay"]);
    }

    if (empty($_POST["employee_id"])) {
        $employee_idErr = "ID karyawan harus diisi";
        $hasError = true;
    } else {
        $employee_id = test_input($_POST["employee_id"]);
    }

    if (empty($_POST["zip"])) {
        $zip_codeErr = "Kode pos harus diisi";
        $hasError = true;
    } else {
        $zip_code = test_input($_POST["zip"]);
    }

    if (empty($_POST["birth_date"])) {
        $birth_dateErr = "Tanggal lahir harus diisi";
        $hasError = true;
    } else {
        $birth_date = test_input($_POST["birth_date"]);
    }

    if (empty($_POST["join_date"])) {
        $join_dateErr = "Tanggal masuk harus diisi";
        $hasError = true;
    } else {
        $join_date = test_input($_POST["join_date"]);
    }

    if (!$hasError) {
        include("connection.php");
        
        $name = $conn->real_escape_string($_POST["name"]);
        $gender = $conn->real_escape_string($_POST["gender"]);
        $join_date = $conn->real_escape_string($_POST["join_date"]);
        $birth_date = $conn->real_escape_string($_POST["birth_date"]);
        $zip_code = $conn->real_escape_string($_POST["zip"]);
        $employee_id = $conn->real_escape_string($_POST["employee_id"]);
        $basic_pay = $conn->real_escape_string($_POST["basic_pay"]);
        $website = $conn->real_escape_string($_POST["website"]);
        $province = $conn->real_escape_string($_POST["province"]);
        $city = $conn->real_escape_string($_POST["city"]);
        $address = $conn->real_escape_string($_POST["address"]);
        $email = $conn->real_escape_string($_POST["email"]);

        $sql = "INSERT INTO `employee` (`employee_id`,`name`, `gender`, `birth_date`, `address`, `city`, `province`, `postal_code`, `email`, `website`, `join_date`, `annual_basic_pay`)
                VALUES('$employee_id','$name','$gender','$birth_date','$address','$city','$province','$zip_code','$email','$website','$join_date','$basic_pay')";
        $result = $conn->query($sql);

        // Update tax calculations
        $conn->query("UPDATE employee SET tax = 5 WHERE employee_id = '$employee_id'");
        $conn->query("UPDATE employee SET tax_amount = (annual_basic_pay/12) * (tax/100) WHERE employee_id = '$employee_id'");
        $conn->query("UPDATE employee SET monthly_pay = (annual_basic_pay/12) - tax_amount WHERE employee_id = '$employee_id'");

        // Create user account for employee
        $password_hash = password_hash('employee123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (username, password, role, employee_id) VALUES ('$employee_id', '$password_hash', 'employee', '$employee_id')");

        if ($result) {
            header("Location: payroll.php#employees");
            exit();
        } else {
            die("Error: " . $conn->error);
        }
        
        $conn->close();
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan - Sistem Manajemen Gaji</title>
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
            <h1 class="page-title">Tambah Karyawan Baru</h1>
        </div>

        <div class="form-card">
            <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">ID Karyawan</label>
                        <input type="text" name="employee_id" class="form-input" placeholder="Contoh: 109" value="<?= $employee_id; ?>">
                        <?php if ($employee_idErr): ?><span class="form-error"><?= $employee_idErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" placeholder="Nama lengkap karyawan" value="<?= $name; ?>">
                        <?php if ($nameErr): ?><span class="form-error"><?= $nameErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-input" value="<?= $birth_date; ?>">
                        <?php if ($birth_dateErr): ?><span class="form-error"><?= $birth_dateErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Male" <?= $gender == 'Male' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Female" <?= $gender == 'Female' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                        <?php if ($genderErr): ?><span class="form-error"><?= $genderErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="email@contoh.com" value="<?= $email; ?>">
                        <?php if ($emailErr): ?><span class="form-error"><?= $emailErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Website (Opsional)</label>
                        <input type="text" name="website" class="form-input" placeholder="www.linkedin.com/username" value="<?= $website; ?>">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-input" placeholder="Alamat lengkap" value="<?= $address; ?>">
                        <?php if ($addressErr): ?><span class="form-error"><?= $addressErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kota</label>
                        <input type="text" name="city" class="form-input" placeholder="Nama kota" value="<?= $city; ?>">
                        <?php if ($cityErr): ?><span class="form-error"><?= $cityErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Provinsi</label>
                        <select name="province" class="form-select">
                            <option value="">Pilih provinsi</option>
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
                        <?php if ($provinceErr): ?><span class="form-error"><?= $provinceErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="zip" class="form-input" placeholder="Kode pos" value="<?= $zip_code; ?>">
                        <?php if ($zip_codeErr): ?><span class="form-error"><?= $zip_codeErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="join_date" class="form-input" value="<?= $join_date; ?>">
                        <?php if ($join_dateErr): ?><span class="form-error"><?= $join_dateErr; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Gaji Pokok Tahunan (Rp)</label>
                        <input type="number" name="basic_pay" class="form-input" placeholder="Contoh: 48000000" value="<?= $basic_pay; ?>">
                        <?php if ($basic_payErr): ?><span class="form-error"><?= $basic_payErr; ?></span><?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="payroll.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>