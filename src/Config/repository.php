<?php
/*
|--------------------------------------------------------------------------
| Repository Config
|--------------------------------------------------------------------------
|
|
*/
return [
    /**
     * 默认分页
     */
    'pagination' => [
        'limit' => 20
    ],
    /**
     * 数据缓存设置
     */
    'cache'      => [
        'enabled'    => true,
        'minutes'    => 10,
    ],
    /**
     * 排序设置
     */
    'order' => [
        'type' => 'o',
        'field'=> 'orderable',
    ],
    /**
     * 文件自动生成器
     */
    'generator'  => [
        'auto_create'   => [
            'controller' => true,
            'request'    => true,
            'service'    => true,
            'model'      => true,
            'response'   => true,
            'seeder'     => true,
        ],
        'tpl_path'       => 'Generator/Templates',
        'root_namespace' => 'App\\',
        'paths' => [
            'controller'          => 'Backend',
            'repository_eloquent' => 'Repositories\\Eloquent',
            'repository'          => 'Repositories\\Contracts',
            'criteria'            => 'Repositories\\Criterias',
            'provider'            => 'Providers\\RepositoryServiceProvider',
            'service'             => 'Services',
            'model'               => 'Models',
            'response'            => 'Http\\Responses'
        ]
    ]
];
