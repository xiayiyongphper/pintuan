<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;

use common\models\Topic;
use framework\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class getTopicInfo
 * @package service\resources\product\v1
 */
class getTopicInfo extends ResourceAbstract
{
    /** @var  \message\common\Topic */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);

        $topic = Topic::find()->select('id, title, type, products')
            ->where(['id' => $this->request->getId(), 'status' => 1])
            ->asArray()->one();

        if (!$topic) {
            Exception::systemNotFound();
        }

        $this->response->setFrom(Tools::pb_array_filter($topic));

        return $this->response;
    }

    public static function request()
    {
        return new \message\common\Topic();
    }

    public static function response()
    {
        return new \message\common\Topic();
    }
}