<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 2018/9/30
 * Time: 10:19
 */
include_once './aliyun-php-sdk-core/Config.php';
include_once './helper.php';
include_once './config.php';

use Alidns\Request\V20150109 as Alidns;

echo "======================= BEGIN =============================\n";
$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", ACCESS_KEYID, ACCESS_SECRET);
$client = new DefaultAcsClient($iClientProfile);
$update_records = get_records_list($client);
$public_ip = http(IP_URL, []);
update_records($client, $update_records, $public_ip);
echo "======================== END ==============================\n";
/**
 * get_records_list 获取dns列表
 * 2018/9/30 10:50:04
 * @author 田继业 <tjy_we@163.com>
 * @return array
 */
function get_records_list($client)
{
    $request = new Alidns\DescribeDomainRecordsRequest();
    $request->setMethod("GET");
    $request->setActionName('DescribeDomainRecords');
    $request->setDomainName(DOMAIN);
    $response = $client->getAcsResponse($request);
    $records = $response->DomainRecords->Record;
    $update_records = [];
    $will_update_records = explode(',', DOMAIN_RECORDS);
    foreach ($records as $record) {
        if (in_array($record->RR, $will_update_records)) {
            $update_records[] = $record;
        };
    }
    return $update_records;
}

/**
 * update_records 更新解析记录
 * @param $client
 * @param $update_records
 * @param $public_ip
 * 2018/9/30 12:35:42
 * @author 田继业 <tjy_we@163.com>
 */
function update_records($client, $update_records, $public_ip)
{
    foreach ($update_records as $record) {
        if ($public_ip == $record->Value) {
            echo date('Y-m-d H:i:s') . "\t[INFO]	Skipped as no changes for DomainRecord[" . $record->RR . "." . DOMAIN . "]\n";
            continue;
        }
        $request = new Alidns\UpdateDomainRecordRequest();
        $request->setActionName('UpdateDomainRecord');
        $request->setMethod('POST');
        $request->setRR($record->RR);
        $request->setValue($public_ip);
        $request->setRecordId($record->RecordId);
        $request->setType($record->Type);
        $response = $client->getAcsResponse($request);
        echo date('Y-m-d H:i:s') . "\t[INFO]  Successfully updated DomainRecord[" . $record->RR . "." . DOMAIN . "]\n";

    }
}