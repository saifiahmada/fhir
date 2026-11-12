<?php
require __DIR__.'/config/database.php';
$count=(int)db()->query("SELECT COUNT(*) FROM fhir_resources")->fetchColumn();
$patients=(int)db()->query("SELECT COUNT(*) FROM fhir_resources WHERE resource_type='Patient'")->fetchColumn();
$requests=(int)db()->query("SELECT COUNT(*) FROM fhir_resources WHERE resource_type='ServiceRequest'")->fetchColumn();
$bundles=(int)db()->query("SELECT COUNT(*) FROM received_bundles")->fetchColumn();
$pageTitle='Dashboard Rumah Sakit'; require __DIR__.'/includes/header.php';
?>
<div class="grid">
<div class="card"><small>Total resource FHIR</small><h1><?=$count?></h1></div>
<div class="card"><small>Resource Patient</small><h1><?=$patients?></h1></div>
<div class="card"><small>Permintaan rujukan</small><h1><?=$requests?></h1></div>
<div class="card"><small>Bundle diterima</small><h1><?=$bundles?></h1></div>
</div>
<div class="card"><h3>Data rujukan yang diterima</h3><p>Data dari Puskesmas disimpan sebagai resource FHIR terpisah. Buka menu FHIR Resources untuk melihat detail JSON.</p><a href="resources.php" style="color:#176b45">Lihat resource FHIR →</a></div>
<?php require __DIR__.'/includes/footer.php'; ?>
