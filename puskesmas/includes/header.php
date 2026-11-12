<?php
$pageTitle = $pageTitle ?? 'Puskesmas Melati';
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle) ?></title>
<style>
body{font-family:Arial,sans-serif;background:#f4f6f9;margin:0;color:#243247}header{background:#145a8d;color:white;padding:18px 5%}
nav{background:#fff;padding:13px 5%;box-shadow:0 1px 4px #ddd}nav a{margin-right:18px;color:#145a8d;text-decoration:none;font-weight:bold}
main{max-width:1100px;margin:25px auto;padding:0 16px}.card{background:white;padding:20px;border-radius:9px;margin-bottom:18px;box-shadow:0 1px 5px #ddd}
input,select,textarea{padding:10px;border:1px solid #ccd3dc;border-radius:5px;width:100%;box-sizing:border-box;margin:5px 0 13px}
button,.btn{background:#146ca3;color:white;border:0;padding:9px 13px;border-radius:5px;text-decoration:none;cursor:pointer;display:inline-block}
.btn-green{background:#18834b}.btn-gray{background:#667085}.alert{padding:12px;background:#e8f3ff;border-radius:6px;margin:12px 0}
table{border-collapse:collapse;width:100%;background:white}th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top}
small,.muted{color:#667085}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}
pre{white-space:pre-wrap;overflow-wrap:anywhere;background:#101828;color:#d1e7dd;padding:14px;border-radius:7px}
@media(max-width:650px){.grid{grid-template-columns:1fr}nav a{display:inline-block;margin:5px}}
</style></head><body>
<header><h2>🏥 SIM Puskesmas Melati</h2><div>FHIR Health Exchange — aplikasi pengirim data</div></header>
<nav><a href="index.php">Dashboard</a><a href="patients.php">Registrasi Pasien</a><a href="referrals.php">Rujukan</a><a href="responses.php">Balasan RS</a></nav>
<main>
