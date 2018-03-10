<?php

namespace Phpno1\Architecture\Traits;


trait CacheGenerate
{
    protected $cacheKeyPrefix;

    protected $defaultCacheSettings = [
        'enabled' => true,
        'minutes'  => 5,
    ];

    /**
     * 使用缓存来记录数据
     *
     * @param $key
     * @param callable $callableOnMiss
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function getOrCache($key, callable $callableOnMiss)
    {
        $key = $this->cacheKeyPrefix ?? class_basename($this) . ':' . $key;
        $cacheConfig = config('architecture.cache') ?? $this->defaultCacheSettings;

        if ($cacheConfig['enabled']) {
            $cache = cache();

            if ($cache->has($key)) {
                return $cache->get($key);
            }

            $data =  call_user_func($callableOnMiss);
            $cache->put($key, $data, $cacheConfig['minutes']);

            return $data;
        }

        return call_user_func($callableOnMiss);
    }
}