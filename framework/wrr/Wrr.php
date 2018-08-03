<?php

namespace framework\wrr;

use framework\components\ProxyAbstract;
use framework\components\ToolsAbstract;
use service\message\common\SourceEnum;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-11-1
 * Time: 下午2:11
 * Email: henryzxj1989@gmail.com
 */
class Wrr
{
    const __I__ = 'round-robin-dispatch-i';
    const __CW__ = 'round-robin-dispatch-cw';
    private static $mapping = [
        SourceEnum::CORE => [
            'core' => 3,
            'sales' => 3
        ],
        SourceEnum::CUSTOMER => [
            'customers' => 3,
            'driver' => 3,
            'contractor' => 3,
        ],
        SourceEnum::MERCHANT => [
            'merchant' => 3
        ]
    ];

    /**
     *        $aProvider = [
     *            0 => [
     *                'provider' => 'partner1',
     *                'weight' => 4
     *            ],
     *            1 => [
     *                'provider' => 'partner2',
     *                'weight' => 3
     *            ],
     *            2 => [
     *                'provider' => 'partner3',
     *                'weight' => 2
     *            ]
     *        ];
     * @param $module
     * @return null
     */
    public static function dispatch($module)
    {
        $redis = ToolsAbstract::getRedis();
        $__i__ = self::getI($module);
        $__cw__ = self::getCW($module);
        $providers = $redis->hGetAll(ProxyAbstract::SERVICE_PREFIX . $module);
        /**
         * A list of partners with their associated weights
         */
        $aProvider = [];
        foreach ($providers as $provider => $weight) {
            $aProvider[] = [
                'provider' => $provider,
                'weight' => $weight
            ];
        }

        /**
         * Weighted round robin algorithm
         */
        $n = count($aProvider);
        $i = $redis->exists($__i__) ? $redis->get($__i__) : -1;
        $cw = $redis->exists($__cw__) ? $redis->get($__cw__) : 0;

        /**
         * greatest common divisor of all provider weights;
         */
        $gcd = function () use ($aProvider) {
            $gcd = function ($a, $b) use (&$gcd) {
                return $b ? $gcd($b, $a % $b) : $a;
            };
            return array_reduce(array_column($aProvider, 'weight'), $gcd);
        };

        /**
         * get the max weight across the whole providers
         */
        $max = array_reduce($aProvider, function ($v, $w) {
            return max($v, $w['weight']);
        }, -9999999);

        /**
         * get the weight of a specific provider
         */
        $w = function ($provider) use ($aProvider) {
            return $aProvider[$provider]['weight'];
        };

        while (1) {

            $i = ($i + 1) % $n;
            $redis->set($__i__, $i);
            if ($i == 0) {
                $cw = $cw - $gcd();
                $redis->set($__cw__, $cw);
                if ($cw <= 0) {
                    $cw = $max;
                    $redis->set($__cw__, $cw);
                    if ($cw == 0) {
                        return NULL;
                    }
                }
            }
            if ($w($i) >= $cw) {
                return $aProvider[$i];
            }
        }
    }

    private static function getI($module)
    {
        return self::__I__ . '-' . $module;
    }

    private static function getCW($module)
    {
        return self::__CW__ . '-' . $module;
    }

    public static function getRoute($modelName, $remote = false)
    {
        $module = false;
        foreach (self::$mapping as $source => $value) {
            if (array_key_exists($modelName, $value)) {
                $module = ToolsAbstract::getSourceCode($source);
                break;
            }
        }
        if ($module !== false) {
            $node = Wrr::dispatch($module);//调度，获取调度到的节点。
            ToolsAbstract::log($node, 'wrr.route.log');
            if ($node && isset($node['provider'])) {
                return self::prepareNodeService($node, $modelName, $remote);
            }
        }
        return false;
    }

    /**
     * @param $node
     * @param $modelName
     * @param $remote
     * @return array|bool
     */
    private static function prepareNodeService($node, $modelName, $remote)
    {
        $redis = ToolsAbstract::getRedis();
        $key = 'local_service';
        if ($remote) {
            $key = ProxyAbstract::KEY_REMOTE_SERVICE;
        }
        $key .= '_' . $node['provider'];
        if ($redis->hExists($key, $modelName)) {
            $dsn = $redis->hGet($key, $modelName);
            list($ip, $port) = explode(':', $dsn);
            if (isset($ip, $port)) {
                return [$ip, $port];
            }
        }
        return false;
    }

    public static function getMapping()
    {
        return self::$mapping;
    }
}