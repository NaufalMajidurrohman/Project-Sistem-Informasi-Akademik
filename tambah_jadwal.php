<?php
require_once "koneksi.php";

$pesan = "";
$tipePesan = ""; // "sukses" atau "error"

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hari       = trim($_POST["hari"] ?? "");
    $waktu      = trim($_POST["waktu"] ?? "");
    $matakuliah = trim($_POST["matakuliah"] ?? "");
    $sks        = trim($_POST["sks"] ?? "");
    $smt        = trim($_POST["smt"] ?? "");
    $kelas      = trim($_POST["kelas"] ?? "");
    $dosen      = trim($_POST["dosen"] ?? "");
    $ruang      = trim($_POST["ruang"] ?? "");

    if (
        $hari === "" || $waktu === "" || $matakuliah === "" ||
        $sks === "" || $smt === "" || $kelas === "" ||
        $dosen === "" || $ruang === ""
    ) {
        $pesan = "Semua field wajib diisi!";
        $tipePesan = "error";
    } else {
        try {
            // Cek apakah jadwal bentrok pada Hari, Waktu, dan Ruang yang sama
            $sqlCek = "SELECT COUNT(*) FROM jadwal_kuliah 
                       WHERE Hari = :hari AND Waktu = :waktu AND Ruang = :ruang";
            $stmtCek = $pdo->prepare($sqlCek);
            $stmtCek->execute([
                ":hari"  => $hari,
                ":waktu" => $waktu,
                ":ruang" => $ruang,
            ]);

            if ((int) $stmtCek->fetchColumn() > 0) {
                $pesan = "Gagal: Jadwal bentrok pada hari, waktu, dan ruang yang sama.";
                $tipePesan = "error";
            } else {
                $sqlInsert = "INSERT INTO jadwal_kuliah 
                              (Hari, Waktu, Matakuliah, SKS, SMT, Kelas, Dosen, Ruang) 
                              VALUES (:hari, :waktu, :matakuliah, :sks, :smt, :kelas, :dosen, :ruang)";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->execute([
                    ":hari"       => $hari,
                    ":waktu"      => $waktu,
                    ":matakuliah" => $matakuliah,
                    ":sks"        => $sks,
                    ":smt"        => $smt,
                    ":kelas"      => $kelas,
                    ":dosen"      => $dosen,
                    ":ruang"      => $ruang,
                ]);

                $pesan = "Jadwal kuliah berhasil ditambahkan!";
                $tipePesan = "sukses";
            }
        } catch (PDOException $e) {
            $pesan = "Terjadi kesalahan database: " . $e->getMessage();
            $tipePesan = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jadwal Kuliah</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 520px; margin: 40px auto; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 4px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 16px; cursor: pointer; }
        .sukses { color: #155724; background: #d4edda; padding: 10px; margin-bottom: 15px; }
        .error { color: #721c24; background: #f8d7da; padding: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h2>Tambah Jadwal Kuliah</h2>

    <?php if ($pesan !== ""): ?>
        <div class="<?= htmlspecialchars($tipePesan) ?>">
            <?= htmlspecialchars($pesan) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="hari">Hari</label>
            <select id="hari" name="hari" required>
                <option value="">-- Pilih Hari --</option>
                <?php
                $daftarHari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                foreach ($daftarHari as $h) {
                    $selected = (($_POST["hari"] ?? "") === $h) ? "selected" : "";
                    echo "<option value=\"" . htmlspecialchars($h) . "\" $selected>" . htmlspecialchars($h) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="waktu">Waktu</label>
            <input type="text" id="waktu" name="waktu" placeholder="Contoh: 08:00 - 10:30"
                   value="<?= htmlspecialchars($_POST["waktu"] ?? "") ?>" required>
        </div>

        <div class="form-group">
            <label for="matakuliah">Matakuliah</label>
            <input type="text" id="matakuliah" name="matakuliah" maxlength="70"
                   value="<?= htmlspecialchars($_POST["matakuliah"] ?? "") ?>" required>
        </div>

        <div class="form-group">
            <label for="sks">SKS</label>
            <input type="text" id="sks" name="sks" maxlength="10" placeholder="Contoh: 3"
                   value="<?= htmlspecialchars($_POST["sks"] ?? "") ?>" required>
        </div>

        <div class="form-group">
            <label for="smt">SMT (Semester)</label>
            <input type="text" id="smt" name="smt" maxlength="10" placeholder="Contoh: 3"
                   value="<?= htmlspecialchars($_POST["smt"] ?? "") ?>" required>
        </div>

        <div class="form-group">
            <label for="kelas">Kelas</label>
            <input type="text" id="kelas" name="kelas" maxlength="10" placeholder="Contoh: TI-3A"
                   value="<?= htmlspecialchars($_POST["kelas"] ?? "") ?>" required>
        </div>

        <div class="form-group">
            <label for="dosen">Dosen</label>
            <input type="text" id="dosen" name="dosen" maxlength="50"
                   value="<?= htmlspecialchars($_POST["dosen"] ?? "") ?>" required>
        </div>

        <div class="form-group">
            <label for="ruang">Ruang</label>
            <input type="text" id="ruang" name="ruang" maxlength="20" placeholder="Contoh: Lab Kom 1"
                   value="<?= htmlspecialchars($_POST["ruang"] ?? "") ?>" required>
        </div>

        <button type="submit">Simpan Jadwal</button>
    </form>

</body>
</html>