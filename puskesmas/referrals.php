<?php
require __DIR__.'/config/database.php'; require __DIR__.'/config/app.php';
$message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $no='RJK-'.date('YmdHis').'-'.random_int(10,99);
    $stmt=db()->prepare("INSERT INTO referrals (referral_no,patient_id,complaint,destination,systolic,diastolic,encounter_date) VALUES (?,?,?,?,?,?,NOW())");
    $stmt->execute([$no,(int)$_POST['patient_id'],trim($_POST['complaint']),trim($_POST['destination']),(int)$_POST['systolic'],(int)$_POST['diastolic']]);
    $message='Rujukan berhasil dibuat dengan nomor '.$no;
}
$patients=db()->query("SELECT id,medical_record_no,name FROM patients ORDER BY name")->fetchAll();
$rows=db()->query("SELECT r.*,p.name,p.medical_record_no FROM referrals r JOIN patients p ON p.id=r.patient_id ORDER BY r.id DESC")->fetchAll();
$pageTitle='Rujukan Pasien'; require __DIR__.'/includes/header.php';
?>
<div class="card"><h3>Buat rujukan</h3><?php if($message): ?><div class="alert"><?=htmlspecialchars($message)?></div><?php endif;?>
<form method="post"><label>Pasien<select name="patient_id" required><?php foreach($patients as $p): ?><option value="<?=$p['id']?>"><?=htmlspecialchars($p['medical_record_no'].' — '.$p['name'])?></option><?php endforeach;?></select></label>
<div class="grid"><label>Tujuan poli<input name="destination" required value="Poli Penyakit Dalam"></label><label>Keluhan / alasan rujukan<input name="complaint" required value="Pusing dan nyeri kepala"></label>
<label>Tekanan sistolik (mmHg)<input type="number" name="systolic" min="40" max="300" value="150" required></label><label>Tekanan diastolik (mmHg)<input type="number" name="diastolic" min="20" max="200" value="95" required></label></div><button>Simpan rujukan</button></form></div>
<div class="card"><h3>Daftar rujukan</h3><table><tr><th>No. rujukan</th><th>Pasien</th><th>Tujuan</th><th>Tekanan darah</th><th>Status</th><th>Aksi</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=htmlspecialchars($r['referral_no'])?></td><td><?=htmlspecialchars($r['name'])?></td><td><?=htmlspecialchars($r['destination'])?></td><td><?=$r['systolic']?>/<?=$r['diastolic']?> mmHg</td><td><?=$r['status']?></td><td><form method="post" action="send.php"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn-green">Kirim ke Rumah Sakit</button></form></td></tr><?php endforeach;?>
</table></div><?php require __DIR__.'/includes/footer.php'; ?>
