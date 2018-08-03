<?php

namespace console\controllers;

use common\helper\EsProductHelper;
use Elasticsearch\Client;
use yii\console\Controller;

class ProductController extends Controller
{
    protected $properties_mapping = [
        'id' => [
            'type' => 'integer',
        ],
        'wholesaler_id' => [
            'type' => 'integer',
        ],
        'first_category_id' => [
            'type' => 'integer',
        ],
        'second_category_id' => [
            'type' => 'integer',
        ],
        'third_category_id' => [
            'type' => 'integer',
        ],
        'min_price' => [
            'type' => 'integer',
        ],
        'status' => [
            'type' => 'integer',
        ],
        'images' => [
            'type' => 'string',
        ],
        'brand' => [
            'type' => 'string',
            "analyzer" => "ik_max_word",
            "search_analyzer" => "ik_smart",
        ],
        'name' => [
            'type' => 'string',
            "analyzer" => "ik_max_word",
            "search_analyzer" => "ik_smart",
        ],
        'create_at' => [
            'type' => 'date',
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis",
        ],
        'update_at' => [
            'type' => 'date',
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis",
        ],
        'del' => [
            'type' => 'integer',
        ],
        'description' => [
            'type' => 'string',
        ],
        'fake_sold_base' => [
            'type' => 'integer',
        ],
        'sold_num' => [
            'type' => 'integer',
        ],
        'sort' => [
            'type' => 'integer',
        ],
        'unit' => [
            'type' => 'string',
        ],
    ];

    public function actionIndexProduct()
    {
        $this->deleteIndex();
        $this->createIndex();
        $this->bulkIndex();
    }

    private function bulkIndex()
    {
        (new EsProductHelper())->index();
    }

    private function createIndex()
    {
        /** @var Client $client */
        $client = \Yii::$app->get('elasticSearch');
        $client->indices()->create(
            [
                'index' => EsProductHelper::INDEX_PINTUAN_PRODUCT,
                'body' => [
                    'settings' => [
                        'number_of_shards' => 3,
                        'number_of_replicas' => 1,
                    ],
                ]
            ]
        );
        $result = $client->indices()->putMapping([
            'index' => EsProductHelper::INDEX_PINTUAN_PRODUCT,
            'type' => EsProductHelper::TYPE,
            'body' => [
                'properties' => $this->properties_mapping,
                '_source' => [
                    'enabled' => true
                ],
            ]
        ]);
        print_r($result);
    }

    private function deleteIndex()
    {
        $client = \Yii::$app->get('elasticSearch');
        if ($client->indices()->exists(['index' => EsProductHelper::INDEX_PINTUAN_PRODUCT])) {
            $client->indices()->delete(['index' => EsProductHelper::INDEX_PINTUAN_PRODUCT]);
        }
    }

    public function actionDelete()
    {
        $client = \Yii::$app->get('elasticSearch');
        if ($client->indices()->exists(['index' => EsProductHelper::INDEX_PINTUAN_PRODUCT])) {
            $client->indices()->delete(['index' => EsProductHelper::INDEX_PINTUAN_PRODUCT]);
        }
    }

}
