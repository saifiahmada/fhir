<?php
require __DIR__.'/config/database.php';
$type=$_GET['type']??'all';
$sql="SELECT * FROM fhir_resources"; $params=[];
if(in_array($type,['Patient','Encounter','Observation','ServiceRequest'],true)){ $sql.=" WHERE resource_type=?";$params[]=$type; }
$sql.=" ORDER BY received_at DESC,id DESC";
$stmt=db()->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll();
$pageTitle='FHIR Resources';require __DIR__.'/includes/header.php';
?>
<div class="card"><h3>Resource FHIR yang diterima</h3>
<p>Filter:
<a href="resources.php">Semua</a> · <a href="?type=Patient">Patient</a> · <a href="?type=Encounter">Encounter</a> · <a href="?type=Observation">Observation</a> · <a href="?type=ServiceRequest">ServiceRequest</a></p>
<table><tr><th>Resource</th><th>ID</th><th>Diterima</th><th>JSON / Aksi</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=htmlspecialchars($r['resource_type'])?></td><td><?=htmlspecialchars($r['resource_id'])?></td><td><?=$r['received_at']?></td><td><details><summary>Lihat JSON</summary><pre><?=htmlspecialchars(json_encode(json_decode($r['resource_json'],true),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE))?></pre></details><?php if($r['resource_type']==='ServiceRequest'): ?><form method="post" action="send_ack.php"><input type="hidden" name="id" value="<?=htmlspecialchars($r['resource_id'])?>"><button type="submit">Kirim konfirmasi ke Puskesmas</button></form><?php endif; ?></td></tr><?php endforeach;?>
</table><?php if(!$rows): ?><p>Belum ada resource. Kirim rujukan dari aplikasi Puskesmas.</p><?php endif;?></div>
<?php require __DIR__.'/includes/footer.php'; ?>
