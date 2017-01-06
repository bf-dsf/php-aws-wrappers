<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2017-01-06
 * Time: 11:40
 */

namespace Oasis\Mlib\AwsWrappers\DynamoDb;

use Aws\DynamoDb\DynamoDbClient;
use Oasis\Mlib\AwsWrappers\DynamoDbIndex;
use Oasis\Mlib\AwsWrappers\DynamoDbItem;

class ScanAsyncCommandWrapper
{
    /**
     * @param DynamoDbClient $dbClient
     * @param                $tableName
     * @param                $filterExpression
     * @param array          $fieldsMapping
     * @param array          $paramsMapping
     * @param                $indexName
     * @param                $lastKey
     * @param                $evaluationLimit
     * @param                $isConsistentRead
     * @param                $isAscendingOrder
     * @param                $segment
     * @param                $totalSegments
     * @param                $countOnly
     *
     * @return \GuzzleHttp\Promise\Promise
     */
    function __invoke(DynamoDbClient $dbClient,
                      $tableName,
                      $filterExpression,
                      array $fieldsMapping,
                      array $paramsMapping,
                      $indexName,
                      &$lastKey,
                      $evaluationLimit,
                      $isConsistentRead,
                      $isAscendingOrder,
                      $segment,
                      $totalSegments,
                      $countOnly
    )
    {
        $requestArgs = [
            "TableName"        => $tableName,
            'ConsistentRead'   => $isConsistentRead,
            'ScanIndexForward' => $isAscendingOrder,
        ];
        if ($countOnly) {
            $requestArgs['SELECT'] = "COUNT";
        }
        if ($totalSegments > 1) {
            $requestArgs['Segment']       = $segment;
            $requestArgs['TotalSegments'] = $totalSegments;
        }
        if ($filterExpression) {
            $requestArgs['FilterExpression'] = $filterExpression;
        }
        if ($fieldsMapping) {
            $requestArgs['ExpressionAttributeNames'] = $fieldsMapping;
        }
        if ($paramsMapping) {
            $paramsItem                               = DynamoDbItem::createFromArray($paramsMapping);
            $requestArgs['ExpressionAttributeValues'] = $paramsItem->getData();
        }
        if ($indexName !== DynamoDbIndex::PRIMARY_INDEX) {
            $requestArgs['IndexName'] = $indexName;
        }
        if ($lastKey) {
            $requestArgs['ExclusiveStartKey'] = $lastKey;
        }
        if ($evaluationLimit) {
            $requestArgs['Limit'] = $evaluationLimit;
        }
        $promise = $dbClient->scanAsync($requestArgs);
        
        return $promise;
    }
}