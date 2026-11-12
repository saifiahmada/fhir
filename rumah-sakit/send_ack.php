<?php
require __DIR__.'/config/database.php'; require __DIR__.'/config/app.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method not allowed');}
$id=preg_replace('/[^A-Za-z0-9._-]/','',(string)($_POST['id']??''));
$stmt=db()->prepare("SELECT resource_json FROM fhir_resources WHERE resource_type='ServiceRequest' AND resource_id=?");$stmt->execute([$id]);$service=$stmt->fetchColumn();
if(!$service){http_response_code(404);exit('ServiceRequest tidak ditemukan');}
$service=json_decode($service,true);
$task=['resourceType'=>'Task','id'=>'ack-'.$id,'status'=>'accepted','intent'=>'order','code'=>['text'=>'Konfirmasi penerimaan rujukan'],'focus'=>['reference'=>'ServiceRequest/'.$id],'authoredOn'=>date('c'),'description'=>'Rumah Sakit mengonfirmasi bahwa rujukan telah diterima.'];
$bundle=['resourceType'=>'Bundle','type'=>'transaction','timestamp'=>date('c'),'identifier'=>['system'=>'https://example.org/acknowledgement','value'=>'ACK-'.$id], 'entry'=>[['fullUrl'=>'urn:uuid:'.bin2hex(random_bytes(16)),'resource'=>$task,'request'=>['method'=>'PUT','url'=>'Task/'.$task['id']]]]];
$clinicUrl='http://localhost/fhir/puskesmas/api/fhir.php';
$ch=curl_init($clinicUrl);curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/fhir+json','Accept: application/fhir+json','X-API-Key: '.FHIR_API_KEY],CURLOPT_POSTFIELDS=>json_encode($bundle,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),CURLOPT_TIMEOUT=>20]);
$response=curl_exec($ch);$error=curl_error($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
if($response===false)$response=json_encode(['error'=>$error]);
$pageTitle='Konfirmasi Rujukan';require __DIR__.'/includes/header.php';
?>
<div class="card"><h3><?=($status>=200&&$status<300)?'Konfirmasi berhasil dikirim':'Konfirmasi gagal dikirim'?></h3><p>HTTP status: <?=$status?></p><p>Resource yang dikirim: <strong>Task/<?=htmlspecialchars($task['id'])?></strong></p><h4>FHIR Bundle</h4><pre><?=htmlspecialchars(json_encode($bundle,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE))?></pre><h4>Respons Puskesmas</h4><pre><?=htmlspecialchars($response)?></pre><a href="resources.php">Kembali</a></div>
<?php require __DIR__.'/includes/footer.php'; ?>
