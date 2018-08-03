<?php

namespace framework\components\es;

use Elasticsearch\ClientBuilder;
use framework\components\log\LogAbstract;
use framework\components\ToolsAbstract;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-6
 * Time: 下午12:19
 */
abstract class EsAbstract implements EsInterface
{
    protected $index;
    protected $type;
    protected $number_of_shards = 5;
    protected $number_of_replicas = 1;
    protected $default;
    protected $properties_mapping;
    protected $flag = false;
    protected $dynamic = false;
    const MAX_HANDLES = 5;
    const ES_LEVEL_INFO = LogAbstract::LEVEL_INFO;
    const ES_LEVEL_DEBUG = LogAbstract::LEVEL_DEBUG;
    const ES_LEVEL_NOTICE = LogAbstract::LEVEL_NOTICE;
    const ES_LEVEL_WARNING = LogAbstract::LEVEL_WARNING;
    const ES_LEVEL_ERROR = LogAbstract::LEVEL_ERROR;
    protected $hosts;
    protected $client;

    public function __construct()
    {
        $esClusters = \Yii::$app->params['es_cluster'];
        if (!isset($esClusters['hosts'], $esClusters['size'])) {
            ToolsAbstract::logException(new \Exception('es cluster config not set', 999));
        }
        $this->hosts = $esClusters['hosts'];
    }

    public function deleteIndex($index = null, $hosts = null)
    {
        if (!$hosts) {
            $hosts = $this->hosts;
        }
        if (!$index) {
            $index = $this->getIndex();
        }
        if (!$this->client) {
            $this->client = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        }
        $deleteParams = [
            'index' => $index,
        ];
        print_r($hosts);
        print_r($index);
        print_r($deleteParams);
        $response = $this->client->indices()->delete($deleteParams);
        print_r($response);
    }

    public function createIndex($index = null, $hosts = null)
    {
        if (!$hosts) {
            $hosts = $this->hosts;
        }
        if (!$index) {
            $index = $this->getIndex();
        }

        if (!$this->client) {
            $this->client = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        }
        $existedParams = [
            'index' => $index,
        ];
        if (!$this->client->indices()->exists($existedParams)) {
            $params = [
                'index' => $index,
                'body' => [
                    'settings' => [
                        'number_of_shards' => $this->number_of_shards,
                        'number_of_replicas' => $this->number_of_replicas,
                        'index.mapper.dynamic' => $this->dynamic,
                    ],
                    'mappings' => [
                        $this->getType() => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' => $this->properties_mapping
                        ]
                    ]
                ]
            ];
            $response = $this->client->indices()->create($params);
            print_r($response);
        } else {
            echo "index:$index already existed!" . PHP_EOL;
        }
    }

    public function putMapping($hosts = null)
    {
        if (!$hosts) {
            $hosts = $this->hosts;
        }

        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'body' => [
                $this->getType() => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'dynamic' => $this->dynamic,
                    'properties' => $this->properties_mapping
                ]
            ]
        ];
        $response = $client->indices()->putMapping($params);
        print_r($response);
    }

    public function search($body = null, $hosts = null)
    {
        if (!$hosts) {
            $hosts = $this->hosts;
        }

        if (!$body) {
            $body = [
                'query' => [
                    'match_all' => [
                        "boost" => 1.2
                    ]
                ]
            ];
        }

        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $params = [
            'index' => $this->getIndex(),
            "size" => 10,
            'type' => $this->getType(),
            'body' => $body,
        ];
        $response = $client->search($params);
        print_r($response);
    }

    public function send($body, $id = null, $index = null, $type = null)
    {
        if (!ENV_ES_REPORT_STATUS) {
            return false;
        }
        try {
            $params = [
                'index' => is_null($index) ? $this->getIndex() : $index,
                'type' => is_null($type) ? $this->getType() : $type,
                'body' => $body
            ];
            if ($id) {
                $params['__id__'] = $id;
            }

            if (!$this->flag) {
                if (!file_exists(ToolsAbstract::getESConsolePath())) {
                    mkdir(ToolsAbstract::getESConsolePath(), 0777, true);
                }
                $this->flag = true;
            }
            $file = sprintf('%s%s.bin', ToolsAbstract::getESConsolePath() . DIRECTORY_SEPARATOR, date('YmdHi'));
            file_put_contents($file, json_encode($params) . PHP_EOL, FILE_APPEND);
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        }
    }

    public function getTraceId()
    {
        return str_replace('.', '', uniqid(ToolsAbstract::getSysName() . '_', true));
    }

    public function getIndexSuffix()
    {
        return date('Y-W');
    }
}