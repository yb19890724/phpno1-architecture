<h2>laravel框架开发项目架构扩展包</h2>


<p align="center">

[![Latest Stable Version](https://poser.pugx.org/phpno1/architecture/v/stable)](https://packagist.org/packages/phpno1/architecture)
[![Total Downloads](https://poser.pugx.org/phpno1/architecture/downloads)](https://packagist.org/packages/phpno1/architecture)
[![Build Status](https://travis-ci.org/yb19890724/phpno1-architecture.svg?branch=master)](https://travis-ci.org/yb19890724/phpno1-architecture)
[![License](https://poser.pugx.org/phpno1/architecture/license)](https://packagist.org/packages/phpno1/architecture)

</p>

## 官方扩展qq群
    qq:680531281

## 功能
<p>项目架构分层，代码生成器，快速进入开发阶段，拆分职责降低耦合。</p>

## 目录

- <a href="#安装">安装</a>
    - <a href="#使用要求">使用要求</a>
    - <a href="#composer">composer</a>
    - <a href="#laravel">laravel</a>
- <a href="#配置">配置</a>    
- <a href="#命令">命令</a>
- <a href="#使用">使用</a>
    - <a href="#快速使用">快速使用</a>
    - <a href="#方法介绍">方法介绍</a>
    - <a href="#初始化加载">初始化加载</a>
    - <a href="#限制条件">限制条件</a>
    - <a href="#排序字段">排序字段</a>
    - <a href="#过滤条件">过滤条件</a>
    - <a href="#scope">scope</a>
    - <a href="#自定义">自定义</a>
- <a href="#缓存">缓存</a>
- <a href="#注意事项">注意事项</a>
    
## 安装

### 使用要求

- laravel >= 5.5    
- php     >= 7.1

### composer
执行以下命令获取包的最新版本:

```php
    composer require phpno1/architecture
```

### laravel

#### 生成配置文件
```php
    php artisan vendor:publish --provider "Phpno1\Architecture\Providers\ArchitectureServiceProvider"
```

#### 注册到服务容器

说明：用命令生成仓储文件时(phpno1:entity || phpno1:repository)，会自动生成ArchitectureServiceProvider文件。

```php
    # 在config/app.php中
    'providers' => [
        // ......
        App\Providers\ArchitectureServiceProvider::class,
    ];
```

## 配置 architecture.php
```php
    'pagination' => [//默认分页数量
        'limit' => 20
    ],

    'cache'      => [//缓存时间
        'enabled'    => true,
        'minutes'    => 10,
    ],

    'order' => [//排序字段
        'o',
    ],
  
    'generator'  => [//代码生成器命名空间
        'root_namespace' => 'App\\',
        'namespace' => [
            'controller'          => 'Backend',
            'repository_eloquent' => 'Repository\\Eloquent',
            'repository'          => 'Repository\\Contracts',
            'criteria'            => 'Architecture\\Criterias',
            'provider'            => 'Providers\\ArchitectureServiceProvider',
            'service'             => 'Services',
            'model'               => 'Models',
            'response'            => 'Http\\Responses',
            'filter'              => 'Repository\\Filters'
        ]
    ]
```


## 命令
说明：使用命令创建仓储文件时(phpno1:entity和phpno1:repository)，会自动绑定接口与实现类关系。

###  生成组合配置
```php
    //@params   {name}        生成文件名称
    //@params   {--resource}  生成资源方法(参照laravel控制器 资源控制器)
    php artisan phpno1:entity {name} {--resource}
```

###  生成控制器
```php
    //@params  {name}          控制器名称
    //@params  {--resource}    生成资源方法(参照laravel控制器 资源控制器)
    php artisan phpno1:controller {name} {--resource}
```

### 生成业务处理类
```php
    @params    {name}          文件名称 
    @params    {--resource}    生成资源方法 
    php artisan phpno1:service {name} {--resource}
```

###  生成扩展全局限制类
```php
    @params    {name}          限制类名称
    php artisan phpno1:criteria {name}
```

### 生成过滤类
```php
    @params    {name}          过滤类名称
    @params    {--prefix=}     命名空间
    @params    {--sort}        排序方法
    php artisan phpno1:filter {name} {--prefix=} {--sort}
```

### 生成模型类
```php
    @params    {name}          模型名称
    php artisan phpno1:model {name}
```

### 生成服务器提供者,用于接口绑定实体类
```php
    php artisan phpno1:provider
```

### 生成仓库类
```php
    @params    {name}          仓库名称    
    php artisan phpno1:repository {name}
```

### 生成校验类
```php
    @params    {name}          文件名称 
    @params    {--dir=}        生成目录
    php artisan phpno1:request {name} {--dir=}
```

### 生成响应类
```php
    @params    {name}          文件名称 
    @params    {--dir=}        生成目录
    php artisan phpno1:response {name} {--dir=}
```

### 生成种子文件
```php
    @params    {name}          文件名称 
    php artisan phpno1:seeder {name}
```

## 快速使用

### 1.执行以下命令:

```php
    php artisan phpno1:entity User --resource
```

<p>生成文件 (注:以下命令生成文件路径可以通过配置修改architecture.php)</p>

- app\Http\Controllers\Backend\UserController
- app\Services\UserService
- app\Repository\Eloquent\UserRepositoryEloquent
- app\Repository\Contracts\UserRepository
- app\Http\Requests\User\StoreRequest
                        \UpdateRequest
- app\Http\Responses\User\IndexResponse
                         \ShowResponse
- app\Models\UserModel
- database\factories\UserFactory
- database\migrations\UserTable
- database\seeds\UserSeeder

### 2.修改代码

#### 1).控制器

```php
<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Responses\User\IndexResponse;

class UserController extends Controller
{

    private $user;

    public function __construct(UserService $userService)
    {
        $this->user=$userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $result=$this->user->getUsers();//这里调用是业务层资源方法
        return new IndexResponse($result);
    }
```

#### 2).响应(数据映射)
```php

<?php

namespace App\Http\Responses\User;

use Illuminate\Contracts\Support\Responsable;
use App\Traits\ResponseTrait;

class UserIndexResponse implements Responsable
{
    use ResponseTrait;

    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        $data = $this->transform();

        return $data;
    }

    protected function transform()
    {
        //分页数据例子
        $this->result->getCollection()->transform(function ($user) {
            return [
                'id'           => $user->id,
                'name'         => $user->name,
            ];
        });
        
        //集合数据例子   
        $this->result->transform(function ($user) {
             return [
                 'id'           => $user->id,
                 'name'         => $user->name,
             ];
        });
        return $this->result;
    }
}
```


## 方法介绍

+ Contracts  : 仓储业务抽象接口。
+ Criterias  : 全局通用业务抽取以及准对某一类业务的Scope。
+ Eloquent   : 仓储业务具体实现。
+ Exceptions : 仓储异常处理。
+ Filters    : 根据参数自动过滤和排序。
+ Traits     : Trait封装

### Phpno1\Repository\Contracts\IRepository;

- function entity();
- function all();
- function find(int $id);
- function first();
- function count();
- function findWhere(...$condition);
- function findWhereFirst(...$condition);
- function findWhereCount(...$condition);
- function paginate(int $perPage = 10);
- function create(array $properties);
- function update(int $id, array $properties);
- function delete(int $id);
- function withCriteria(...$criteria);
- function toEntity();
- function toRepository(Builder $entity);

### Phpno1\Repository\Eloquent;


  ```php
    // 获取所有记录
    $this->repository->all();
  ```

  ```php
    // 根据id查询单条记录
    $this->repository->find(int $id);
  ```

  ```php
    // 获取第一条记录
    $this->repository->first();
  ```

  ```php
    // 获取总记录数
    $this->repository->count();
  ```

  ```php
    // 根据一个或多个 AND WHERE 条件查询。得到一个结果集
    // 单个条件写法：->findWhere('name', 'tome');
    // 多个条件写法：->findWhere(['name', 'tome'], ['age', '>', 20]);
    $this->repository->findWhere(...$condition);
  ```

  ```php
    // 根据一个或多个 AND WHERE 条件查询。得到一条记录。
    $this->repository->findWhereFirst(...$condition);
  ```

  ```php
    // 根据一个或多个 AND WHERE 条件获取记录数
    $this->repository->findWhereCount(...$condition);
  ```

  ```php
    // 获取分页数据
    $this->repository->paginate(int $perPage = 10);
  ```

  ```php
    // 插入记录
    $this->repository->create(array $properties);
  ```

  ```php
    // 修改记录
    $this->repository->update(int $id, array $properties);
  ```

  ```php
    // 根据id删除
    $this->repository->delete(int $id);
  ```

  ```php
    // 用于加载预设的自定义标准操作 (Criterias目录中的类)，后面会详细介绍
    $this->repository->withCriteria(...$criteria);
  ```

  ```php
    // 把Repository转换成Model或者Build对象，后续可以使用框架提供的ORM操作
    $this->repository->toEntity();
  ```

  ```php
    // 把Build对象转回Repository对象
    $this->repository->toRepository(Builder $entity);
  ```
## 初始化加载
```php
    //请重写boot方法 不要重写构造方法
    function boot()
    {
    }
```

## 限制条件
 
### 编写Criteria类
```php
namespace App\Repositories\Criterias;

class ByCreateTime implements ICriteria
{
    public function apply($entity)
    {
        return $entity->latest();   
    }
}
```

### 使用Criteria类
```php

public function getUserListByCreateTime()
{
    return $this->repository
        ->withCriteria(
            new ByCreateTime()
            // more...
        )->paginate();
}

```

## 过滤条件

### 在Repository中配置要过滤和排序的映射字段
```php
class UserRepositoryEloquent extends AbstractRepository implements UserRepository
{
    // 设置过滤映射
    protected $filters = [
        'email'    => EmailFilter::class,
        'name'     => NameFilter::class,
    ];

    public function entity()
    {
        return User::class;
    }
    
    // 过滤和排序在withCriteria中添加过滤和排序操作类FilterRequest
    public function findUserListByPage()
    {
        return $this->withCriteria(
            new FilterRequest($this->filters)
        )->paginate();
    }
    
    // something...
}
```

### 编写要过滤的业务

```php
namespace App\Repositories\Filters\User;

use App\Repositories\Filters\AbstractFilter;

class NameFilter extends AbstractFilter
{
    // 过滤操作
    public function filter($entity, $value)
    {
        return $entity->where('name', $value);
    }
}
```

### 编写要排序的业务

注意:如果你需要字段排序,首先需要在生成的配置文件architecture.php中定义你需要的排序接收参数
<p>在过滤类中<font color="red">必须实现接口 "IOrder"</font>！！！</p>

```php
namespace App\Repositories\Filters\Admin;

use App\Repositories\Filters\{
    AbstractFilter,
    IOrder
};

// 需要排序，必须实现排序接口
class NameFilter extends AbstractFilter implements IOrder
{
    // 过滤操作
    public function filter($entity, $value)
    {
        return $entity->where('name', $value);
    }
    
    // 排序操作
    public function order($entity, $direction)
    {
        return $entity->orderBy('name', $this->resolveOrderDirection($direction));
    }
}
```

### 传递参数进行过滤操作

```php
 // 过滤name和email
 http://www.phpno1.com/user?name=Anthony&email=king19800105@163.com
 
 // 过滤和排序组合。参数"o"表示排序方式。参数"orderable"表示要排序的字段
 http://www.phpno1.com/user?name=Anthony&email=king19800105@163.com&o=desc&orderable=name
```

## scope

### 这里仓库层中的scope方法是兼容laravel模型的scope方法调用

<p><font color="red">注意如果调用scope方法请注意，必须先调用再执行withCriteria进行过滤</font></p>

```php

//模型中定义

class User extends Model
{
    public function scopeUserFields()
   {
       return $this->select(['id','name','email']);
   }
}

//仓库中调用
class UserRepositoryEloquent extends AbstractRepository implements UserRepository
{
    public function getUsers(int $perPage=0)
    {
            return $this->userFields()->withCriteria(
                new FilterRequest($this->filters)
            )->paginate($perPage);
    }
}
```

## 自定义 
```php

//当Repository提供的method无法满足业务
public function do()
{
    // 使用toEntity() 转换回Model或Build对象
    return $this->repository
        ->toEntity()
        ->where(...)
        ->orWhere(...)
        ->when(...)
        ->get(...);
}
```

## 缓存

使用CacheGenerate的trait特性
用途：当使用redis或memcahce做缓存时，方便做数据缓存操作。当然，您也可以使用Laravel框架提供的Cache。

```php
    public function getUserList()
    {
        // 参数1：缓存的key值
        // 缓存中有数据则从缓存中取，没有数据则从数据库取一次放入缓存。
        $this->getOrCache('getUserList', function () {
            return $this->paginate();
        });
    }
```


## 注意事项

+ 建议文件生成都使用命令来操作。
+ 过滤必须要继承AbstractFilter (有特殊需求的可以实现IFilter接口) ，过滤必须实现IOrder接口。
+ 过滤和排序都是可选的。
+ 通过重写过滤中的mappings()方法来改变数据库字段和过滤使用的参数映射关系。
+ 通过重写AbstractFilter中的resolveOrderDirection()方法来改变排序方式的映射关系。
