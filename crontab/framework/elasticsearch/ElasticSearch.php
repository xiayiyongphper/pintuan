<?php

namespace framework\elasticsearch;

use Elasticsearch\ClientBuilder;
use framework\components\ToolsAbstract;
use yii\base\Component;

class ElasticSearch extends Component
{

    public $host;

    public $client = null;

    public function init()
    {
        if (!$this->host) {
            throw new \Exception("set elasticsearch host please!");
        }
    }

    public function __construct($config = [])
    {
        parent::__construct($config);

        if (!$this->client) {
            try {
                $this->client = ClientBuilder::create()->setHosts($this->host)->build();
            } catch (\Exception $e) {
                ToolsAbstract::logException($e);
                $this->client = null;
            }
        }
        return $this->client;
    }

    public function __call($funcName, $arguments)
    {

        $result = false;
        try {
            if ($this->client) {
                $result = call_user_func_array(array($this->client, $funcName), $arguments);
            }
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        }
        return $result;
    }

}
