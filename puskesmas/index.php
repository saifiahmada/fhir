<?php
require __DIR__.'/config/database.php';
require __DIR__.'/config/app.php';
$pageTitle='Dashboard Puskesmas';
$patients=(int)db()->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$referrals=(int)db()->query("SELECT COUNT(*) FROM referrals")->fetchColumn();
$sent=(int)db()->query("SELECT COUNT(*) FROM referrals WHERE status='sent'")->fetchColumn();
require __DIR__.'/includes/header.php';
?>
<div class="grid">
<div class="card"><small>Total pasien</small><h1><?= $patients ?></h1></div>
<div class="card"><small>Total rujukan</small><h1><?= $referrals ?></h1></div>
<div class="card"><small>Berhasil dikirim</small><h1><?= $sent ?></h1></div>
<div class="card"><small>FHIR endpoint tujuan</small><p><?= htmlspecialchars(FHIR_RECEIVER_URL ?? 'Atur di config/app.php') ?></p></div>
</div>
<div class="card"><h3>Alur praktikum</h3><ol><li>Registrasikan pasien.</li><li>Buat rujukan beserta hasil tekanan darah.</li><li>Kirim data sebagai FHIR Bundle transaction.</li><li>Periksa data yang diterima pada aplikasi Rumah Sakit.</li></ol></div>
<?php require __DIR__.'/includes/footer.php'; ?>
