<h1 align="center">phpno1-repository</h1>


<p align="center">

[![Latest Stable Version](https://poser.pugx.org/phpno1/repository/v/stable)](https://packagist.org/packages/phpno1/repository)
[![Total Downloads](https://poser.pugx.org/phpno1/repository/downloads)](https://packagist.org/packages/phpno1/repository)
[![License](https://poser.pugx.org/phpno1/repository/license?format=flat)](https://packagist.org/packages/phpno1/repository)

</p>

composer 安装
执行以下命令获取包的最新版本:

composer require phpno1/repository

### Directory Structure

+ Contracts  : 仓储业务抽象接口。
+ Criterias  : 全局通用业务抽取以及准对某一类业务的Scope。
+ Eloquent   : 仓储业务具体实现。
+ Exceptions : 仓储异常处理。
+ Filters    : 根据参数自动过滤和排序。
+ Traits     : Trait封装

### Laravel

#### >= laravel5.5

ServiceProvider will be attached automatically

#### Other

先在RepositoryServiceProvider.php中的register方法中，绑定仓储接口和实现类映射关系：

```php
public function register()
{
    $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\Eloquent\UserRepositoryEloquent::class);
}
```

In your `config/app.php` add `Phpno1\Repository\providers\RepositoryServiceProvider::class` to the end of the `providers` array:

```php
'providers' => [
    ...
    Phpno1\Repository\providers\RepositoryServiceProvider::class,
],
```

## Usage

```php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // something...
}
```

#### create repository
```php

namespace App\Repositories\Eloquent;

use App\User;

class UserRepositoryEloquent extends AbstractRepository implements UserRepository
{
    // 绑定User模型
    public function entity()
    {
        return User::class;
    }
    
    // 其他仓储方法...
}
```

#### use repository

+ 注入到controller中，或者serveric层中使用。
```php
class UserController extends Controller
{
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}

```

## Methods

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
  
## Use methods

#### 基本使用

```php

public function getUserList()
{
    return $this->repository->paginate();
}

```

#### 当Repository提供的method无法满足业务
```php
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
## Use Criteria

#### 编写Criteria类

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

#### 使用Criteria类
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
## Use Filter And Sort

#### 在Repository中配置要过滤和排序的映射字段
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
        return Admin::class;
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

#### 编写要过滤的业务

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

#### 编写要排序的业务
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

#### 传递参数进行过滤操作

```php
 // 过滤name和email
 http://www.phpno1.com/user/list?name=Anthony&email=king19800105@163.com
 
 // 过滤和排序组合。参数"o"表示排序方式。参数"orderable"表示要排序的字段
 http://www.phpno1.com/user/list?name=Anthony&email=king19800105@163.com&o=desc&orderable=name
```
#### 注意事项

+ 过滤必须要继承AbstractFilter (有特殊需求的可以实现IFilter接口) ，过滤必须实现IOrder接口。
+ 过滤和排序都是可选的。
+ 通过重写过滤中的mappings()方法来改变数据库字段和过滤使用的参数映射关系。
+ 通过重写AbstractFilter中的resolveOrderDirection()方法来改变排序方式的映射关系。





