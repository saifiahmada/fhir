<?php
require __DIR__.'/config/database.php';
$message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt=db()->prepare("INSERT INTO patients (medical_record_no,nik,name,gender,birth_date,phone) VALUES (?,?,?,?,?,?)");
    $stmt->execute([trim($_POST['mrn']),trim($_POST['nik']) ?: null,trim($_POST['name']),$_POST['gender'],$_POST['birth_date'] ?: null,trim($_POST['phone']) ?: null]);
    $message='Pasien berhasil disimpan.';
}
$rows=db()->query("SELECT * FROM patients ORDER BY id DESC")->fetchAll();
$pageTitle='Registrasi Pasien'; require __DIR__.'/includes/header.php';
?>
<div class="card"><h3>Registrasi pasien</h3><?php if($message): ?><div class="alert"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<form method="post"><div class="grid">
<label>No. Rekam Medis<input name="mrn" required placeholder="PKM-0002"></label>
<label>NIK (data fiktif)<input name="nik" maxlength="16"></label>
<label>Nama lengkap<input name="name" required></label>
<label>Jenis kelamin<select name="gender"><option value="male">Laki-laki</option><option value="female">Perempuan</option><option value="unknown">Tidak diketahui</option></select></label>
<label>Tanggal lahir<input type="date" name="birth_date"></label><label>No. HP<input name="phone"></label>
</div><button>Simpan pasien</button></form></div>
<div class="card"><h3>Daftar pasien</h3><table><tr><th>No. RM</th><th>Nama</th><th>Jenis kelamin</th><th>Tanggal lahir</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=htmlspecialchars($r['medical_record_no'])?></td><td><?=htmlspecialchars($r['name'])?></td><td><?=htmlspecialchars($r['gender'])?></td><td><?=htmlspecialchars($r['birth_date'] ?? '-')?></td></tr><?php endforeach; ?>
</table></div><?php require __DIR__.'/includes/footer.php'; ?>
