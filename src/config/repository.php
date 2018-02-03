<?php

return [
    /**
     * 每页显示记录数
     */
    'pagination' => [
        'limit' => 30
    ],
    /**
     * order by 排序设置
     * type表示排序方式
     * field表示排序的字段
     */
    'order' => [
        'type' => 'o',
        'field'=> 'orderable',
    ],
    /**
     * 仓储层文件自动生成对应位置
     */
    'generator'  => [
        'base_path'      => app_path(),
        'root_namespace' => 'App\\',
        'paths'         => [
            'repositories' => 'Repositories\\Eloquent',
            'interfaces'   => 'Repositories\\Contracts',
            'criterias'    => 'Repositories\\Criterias',
            'filters'      => 'Repositories\\Filters',
            'provider'     => 'RepositoryServiceProvider',
            'override_path' => app_path()
        ]
    ]
];