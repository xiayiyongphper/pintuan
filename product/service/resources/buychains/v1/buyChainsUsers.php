<?php
namespace service\resources\buychains\v1;

/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/7/27
 * Time: 14:56
 */

use common\models\BuyChainsUser;
use framework\components\ToolsAbstract;
use message\product\BuyChainsUsersReq;
use message\product\BuyChainsUsersRes;
use service\resources\ResourceAbstract;

/**
 * Class buyChainsUsers
 */
class buyChainsUsers extends ResourceAbstract
{
    /** @var BuyChainsUsersReq  */
    protected $request;

    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param string $bytes
     * @return mixed
     */
    public function run($data)
    {
        $this->doInit($data);
        $buychainsId = $this->request->getBuyChainsId();
        $storeId = $this->request->getStoreId();
        $page = $this->request->getPagination()->getPage();
        $page = $page > 0 ? $page : 1;
        $pageSize = $this->request->getPagination()->getPageSize();
        $pageSize = $pageSize > 0 ? $pageSize : 20;
        $offset = ($page - 1) * $pageSize;

        $modelObj = BuyChainsUser::find()
            ->select(['user_id','buy_number','created_at as buy_time'])
            ->where([
            'buy_chains_id' => $buychainsId,
            'store_id' => $storeId
        ]);

        $total = $modelObj->count();
        $users = $modelObj->orderBy(['id' => SORT_DESC])->limit($pageSize)->offset($offset)->asArray()->all();

        $serialNumber = $total - $offset;
        $now = time();
        foreach ($users as &$item){
            $item['serial_number'] = $serialNumber;
            $seconds = $now - strtotime($item['buy_time']);
            $hours = floor($seconds / 3600);
            if($hours > 0){
                $item['buy_time'] = $hours."小时前";
            }else{
                $item['buy_time'] = floor($seconds / 60)."分钟前";
            }

            $serialNumber--;
        }

        $this->result = [
            'list' => $users,
            'pagination' => [
                'total_count' => $total,
                'page' => $page,
                'page_size' => $pageSize,
                'last_page' => ceil($total / $pageSize)
            ]
        ];

        ToolsAbstract::log($this->result,'buy_chains_users.log');
        $this->response->setFrom($this->result);
        return $this->response;
    }

    public static function request()
    {
        return new BuyChainsUsersReq();
    }

    public static function response()
    {
        return new BuyChainsUsersRes();
    }
}