<?php
require __DIR__.'/config/database.php'; require __DIR__.'/config/app.php'; require __DIR__.'/fhir/build_bundle.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit('Method not allowed'); }
$stmt=db()->prepare("SELECT r.*,p.medical_record_no,p.nik,p.name,p.gender,p.birth_date,p.phone FROM referrals r JOIN patients p ON p.id=r.patient_id WHERE r.id=?");
$stmt->execute([(int)$_POST['id']]); $row=$stmt->fetch();
if (!$row) { http_response_code(404); exit('Rujukan tidak ditemukan'); }
$bundle=buildReferralBundle($row,$row);
$ch=curl_init(FHIR_RECEIVER_URL);
curl_setopt_array($ch,[
 CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,
 CURLOPT_HTTPHEADER=>['Content-Type: application/fhir+json','Accept: application/fhir+json','X-API-Key: '.FHIR_API_KEY],
 CURLOPT_POSTFIELDS=>json_encode($bundle,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),
 CURLOPT_TIMEOUT=>20
]);
$response=curl_exec($ch); $error=curl_error($ch); $status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
if ($response===false) $response=json_encode(['error'=>$error]);
$ok=$status>=200 && $status<300;

// $update=db()->prepare("UPDATE referrals SET status=?,sent_at=IF(?,NOW(),sent_at),last_response=? WHERE id=?");
// $update->execute([$ok?'sent':'draft',$ok,$response,$row['id']]);


if ($ok) {
    $update = db()->prepare("
        UPDATE referrals
        SET status = 'sent',
            sent_at = NOW(),
            last_response = ?
        WHERE id = ?
    ");

    $update->execute([
        $response,
        $row['id']
    ]);
} else {
    $update = db()->prepare("
        UPDATE referrals
        SET status = 'draft',
            last_response = ?
        WHERE id = ?
    ");

    $update->execute([
        $response,
        $row['id']
    ]);
}



$pageTitle='Hasil Pengiriman FHIR'; require __DIR__.'/includes/header.php';
?>
<div class="card"><h3><?= $ok?'Pengiriman berhasil':'Pengiriman gagal' ?></h3><p>HTTP status: <strong><?=$status?></strong></p><p><?= $ok?'Rumah Sakit telah memberikan respons. Periksa dashboard penerima.':'Periksa URL API, API key, Apache, dan koneksi jaringan.' ?></p><h4>FHIR Bundle yang dikirim</h4><pre><?=htmlspecialchars(json_encode($bundle,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE))?></pre><h4>Respons API</h4><pre><?=htmlspecialchars($response)?></pre><a class="btn" href="referrals.php">Kembali</a></div>
<?php require __DIR__.'/includes/footer.php'; ?>
