<?php
require __DIR__.'/config/database.php';
$rows=db()->query("SELECT * FROM received_fhir_resources ORDER BY received_at DESC,id DESC")->fetchAll();
$pageTitle='Balasan Rumah Sakit'; require __DIR__.'/includes/header.php';
?>
<div class="card"><h3>Informasi yang dikirim kembali oleh Rumah Sakit</h3><p>Contoh ini memperlihatkan komunikasi dua arah. Rumah Sakit mengirim resource <code>Task</code> sebagai konfirmasi penerimaan rujukan.</p>
<table><tr><th>Resource</th><th>ID</th><th>Diterima</th><th>Isi</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=htmlspecialchars($r['resource_type'])?></td><td><?=htmlspecialchars($r['resource_id'])?></td><td><?=$r['received_at']?></td><td><details><summary>Lihat JSON</summary><pre><?=htmlspecialchars(json_encode(json_decode($r['resource_json'],true),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE))?></pre></details></td></tr><?php endforeach; ?></table>
<?php if(!$rows): ?><p>Belum ada balasan dari Rumah Sakit.</p><?php endif; ?></div>
<?php require __DIR__.'/includes/footer.php'; ?>
