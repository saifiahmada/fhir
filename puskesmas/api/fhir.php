<?php
require __DIR__.'/../config/database.php'; require __DIR__.'/../config/app.php';
header('Content-Type: application/fhir+json; charset=utf-8');
function failClinic(int $status,string $message): never {
    http_response_code($status);
    echo json_encode(['resourceType'=>'OperationOutcome','issue'=>[['severity'=>'error','code'=>'processing','diagnostics'=>$message]]],JSON_UNESCAPED_SLASHES);
    exit;
}
if ($_SERVER['REQUEST_METHOD']!=='POST') failClinic(405,'Gunakan HTTP POST.');
$key=$_SERVER['HTTP_X_API_KEY'] ?? '';
if (!hash_equals(FHIR_API_KEY,$key)) failClinic(401,'API key tidak valid.');
$raw=file_get_contents('php://input'); $bundle=json_decode($raw,true);
if (!is_array($bundle) || ($bundle['resourceType']??'')!=='Bundle' || ($bundle['type']??'')!=='transaction' || !is_array($bundle['entry']??null)) failClinic(400,'Payload harus berupa FHIR Bundle transaction.');
$allowed=['Task','Communication','ServiceRequest'];
foreach($bundle['entry'] as $entry){$r=$entry['resource']??null;if(!is_array($r)||!in_array($r['resourceType']??'',$allowed,true)||empty($r['id']))failClinic(422,'Resource tidak didukung atau id kosong.');}
try {
 $pdo=db();$pdo->beginTransaction();
 $save=$pdo->prepare("INSERT INTO received_fhir_resources(resource_type,resource_id,resource_json) VALUES(?,?,?) ON DUPLICATE KEY UPDATE resource_json=VALUES(resource_json),received_at=CURRENT_TIMESTAMP");
 foreach($bundle['entry'] as $entry){$r=$entry['resource'];$save->execute([$r['resourceType'],$r['id'],json_encode($r,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)]);}
 $log=$pdo->prepare("INSERT INTO received_fhir_bundles(bundle_identifier,sender,bundle_json) VALUES(?,?,?)");
 $log->execute([$bundle['identifier']['value']??null,'Rumah Sakit',$raw]);$pdo->commit();
 http_response_code(200);$out=['resourceType'=>'Bundle','type'=>'transaction-response','entry'=>[]];
 foreach($bundle['entry'] as $entry){$r=$entry['resource'];$out['entry'][]=['response'=>['status'=>'200 OK','location'=>$r['resourceType'].'/'.$r['id']]];}
 echo json_encode($out,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
} catch(Throwable $e){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();error_log($e->getMessage());failClinic(500,'Gagal menyimpan Bundle.');}
