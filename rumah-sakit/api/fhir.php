<?php
require __DIR__.'/../config/database.php'; require __DIR__.'/../config/app.php';
header('Content-Type: application/fhir+json; charset=utf-8');
function failFhir(int $status,string $message): never {
    http_response_code($status);
    echo json_encode(['resourceType'=>'OperationOutcome','issue'=>[['severity'=>'error','code'=>'processing','diagnostics'=>$message]]],JSON_UNESCAPED_SLASHES);
    exit;
}
if ($_SERVER['REQUEST_METHOD']!=='POST') failFhir(405,'Gunakan HTTP POST untuk mengirim FHIR Bundle.');
$key=$_SERVER['HTTP_X_API_KEY'] ?? '';
if (!hash_equals(FHIR_API_KEY,$key)) failFhir(401,'API key tidak valid.');
$raw=file_get_contents('php://input');
$bundle=json_decode($raw,true);
if (!is_array($bundle) || ($bundle['resourceType']??'')!=='Bundle' || ($bundle['type']??'')!=='transaction' || !isset($bundle['entry']) || !is_array($bundle['entry'])) failFhir(400,'Payload harus berupa FHIR Bundle bertipe transaction.');
$allowed=['Patient','Encounter','Observation','ServiceRequest','Task'];
foreach($bundle['entry'] as $entry) {
    $r=$entry['resource']??null;
    if (!is_array($r) || !in_array($r['resourceType']??'', $allowed,true) || empty($r['id'])) failFhir(422,'Setiap entry harus berisi resource yang didukung dan memiliki id.');
}
try {
    $pdo=db(); $pdo->beginTransaction();
    $save=$pdo->prepare("INSERT INTO fhir_resources(resource_type,resource_id,resource_json) VALUES(?,?,?) ON DUPLICATE KEY UPDATE resource_json=VALUES(resource_json), received_at=CURRENT_TIMESTAMP");
    foreach($bundle['entry'] as $entry) {
        $r=$entry['resource'];
        $save->execute([$r['resourceType'],$r['id'],json_encode($r,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)]);
    }
    $log=$pdo->prepare("INSERT INTO received_bundles(bundle_identifier,sender,bundle_json) VALUES(?,?,?)");
    $log->execute([$bundle['identifier']['value']??null,'Puskesmas / Klinik',$raw]);
    $pdo->prepare("INSERT INTO api_logs(outcome,message) VALUES('success',?)")->execute(['Menerima '.count($bundle['entry']).' resource FHIR.']);
    $pdo->commit();
    http_response_code(200);
    $response=['resourceType'=>'Bundle','type'=>'transaction-response','entry'=>[]];
    foreach($bundle['entry'] as $entry) {
        $r=$entry['resource'];
        $response['entry'][]=['response'=>['status'=>'200 OK','location'=>$r['resourceType'].'/'.$r['id']]];
    }
    echo json_encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
} catch(Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    failFhir(500,'Terjadi kesalahan saat menyimpan Bundle.');
}
