#! /usr/local/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-12-04
 * Time: 17:16
 */

use Oasis\Mlib\AwsWrappers\DynamoDbTable;

require_once __DIR__ . "/vendor/autoload.php";
$aws = [
    'profile' => 'oasis-minhao',
    'region'  => 'ap-northeast-1',
];

$table = new DynamoDbTable($aws, "watch-test-values");
//$old   = $table->get(["appHash" => "abc", "timestamp" => 123]);
////var_dump($old);
//$ret = $table->set(
//    [
//        "appHash"   => "abc",
//        "timestamp" => 124,
//        "age"       => 12,
//        "gender"    => "f",
//        //"code"      => null,
//    ],
//    ["age" => null]
//);
//
//var_dump($ret);

//$client  = $table->getDbClient();
$ts1     = microtime(true);
$updates = [];
for ($i = 0; $i < 30; ++$i) {
    $updates[] = [
        "appHash"   => "b",
        "timestamp" => $i,
        "age"       => "age = $i",
    ];
}
$table->batchPut($updates);
$ts2 = $ts3 = microtime(true);

mdebug("Time = %0.3f, %0.3f", ($ts2 - $ts1), ($ts3 - $ts2));

