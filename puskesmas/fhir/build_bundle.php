<?php
function buildReferralBundle(array $patient, array $referral): array {
    $patientUrl='urn:uuid:'.sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,65535),random_int(0,65535));
    $encounterUrl='urn:uuid:'.bin2hex(random_bytes(16));
    $obsUrl='urn:uuid:'.bin2hex(random_bytes(16));
    $requestUrl='urn:uuid:'.bin2hex(random_bytes(16));
    $patientId='p-'.$patient['id'];
    $encounterId='e-'.$referral['id'];
    $obsId='o-'.$referral['id'];
    $serviceId='sr-'.$referral['id'];
    $patientResource=[
      'resourceType'=>'Patient','id'=>$patientId,
      'identifier'=>[['system'=>'https://example.org/puskesmas/medical-record','value'=>$patient['medical_record_no']]],
      'name'=>[['use'=>'official','text'=>$patient['name']]],
      'gender'=>$patient['gender'],
      'telecom'=>!empty($patient['phone'])?[['system'=>'phone','value'=>$patient['phone']]]:[],
    ];
    if (!empty($patient['birth_date'])) $patientResource['birthDate']=$patient['birth_date'];
    if (!empty($patient['nik'])) $patientResource['identifier'][]=['system'=>'https://fhir.kemkes.go.id/id/nik','value'=>$patient['nik']];
    $encounter=[
      'resourceType'=>'Encounter','id'=>$encounterId,'status'=>'finished',
      'class'=>['system'=>'http://terminology.hl7.org/CodeSystem/v3-ActCode','code'=>'AMB','display'=>'ambulatory'],
      'subject'=>['reference'=>$patientUrl],
      'period'=>['start'=>date('c',strtotime($referral['encounter_date']))],
      'reasonCode'=>[['text'=>$referral['complaint']]]
    ];
    $observation=[
      'resourceType'=>'Observation','id'=>$obsId,'status'=>'final',
      'category'=>[['coding'=>[['system'=>'http://terminology.hl7.org/CodeSystem/observation-category','code'=>'vital-signs','display'=>'Vital Signs']]]],
      'code'=>['coding'=>[['system'=>'http://loinc.org','code'=>'85354-9','display'=>'Blood pressure panel with all children optional']],'text'=>'Tekanan darah'],
      'subject'=>['reference'=>$patientUrl],
      'encounter'=>['reference'=>$encounterUrl],
      'effectiveDateTime'=>date('c',strtotime($referral['encounter_date'])),
      'component'=>[
        ['code'=>['coding'=>[['system'=>'http://loinc.org','code'=>'8480-6','display'=>'Systolic blood pressure']]],'valueQuantity'=>['value'=>(int)$referral['systolic'],'unit'=>'mmHg','system'=>'http://unitsofmeasure.org','code'=>'mm[Hg]']],
        ['code'=>['coding'=>[['system'=>'http://loinc.org','code'=>'8462-4','display'=>'Diastolic blood pressure']]],'valueQuantity'=>['value'=>(int)$referral['diastolic'],'unit'=>'mmHg','system'=>'http://unitsofmeasure.org','code'=>'mm[Hg]']]
      ]
    ];
    $service=[
      'resourceType'=>'ServiceRequest','id'=>$serviceId,'status'=>'active','intent'=>'order',
      'subject'=>['reference'=>$patientUrl],
      'encounter'=>['reference'=>$encounterUrl],
      'code'=>['text'=>'Konsultasi '.$referral['destination']],
      'reasonCode'=>[['text'=>$referral['complaint']]],
      'note'=>[['text'=>'Rujukan dari '.FHIR_SENDER_NAME.' nomor '.$referral['referral_no']]]
    ];
    $resources=[
      [$patientUrl,$patientResource,'Patient'],
      [$encounterUrl,$encounter,'Encounter'],
      [$obsUrl,$observation,'Observation'],
      [$requestUrl,$service,'ServiceRequest']
    ];
    $entries=[];
    foreach($resources as [$fullUrl,$resource,$type]) {
      $entries[]=['fullUrl'=>$fullUrl,'resource'=>$resource,'request'=>['method'=>'PUT','url'=>$type.'/'.$resource['id']]];
    }
    return ['resourceType'=>'Bundle','id'=>'bundle-'.$referral['id'],'type'=>'transaction','timestamp'=>date('c'),'identifier'=>['system'=>'https://example.org/referral','value'=>$referral['referral_no']],'entry'=>$entries];
}
