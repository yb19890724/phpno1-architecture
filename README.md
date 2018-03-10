<h2>laravel framework project architecture</h2>
<p align="center">

[![Latest Stable Version](https://poser.pugx.org/phpno1/architecture/v/stable)](https://packagist.org/packages/phpno1/architecture)
[![Total Downloads](https://poser.pugx.org/phpno1/architecture/downloads)](https://packagist.org/packages/phpno1/architecture)
[![Build Status](https://travis-ci.org/yb19890724/phpno1-architecture.svg?branch=master)](https://travis-ci.org/yb19890724/phpno1-architecture)
[![License](https://poser.pugx.org/phpno1/architecture/license)](https://packagist.org/packages/phpno1/architecture)

</p>

<a href="https://github.com/yb19890724/phpno1-architecture/blob/master/README_CN.md">中文说明</a></p>
Installation
Composer
Execute the following command to get the latest version of the package:

composer require phpno1/architecture

### Laravel

#### >= laravel5.5

ServiceProvider will be attached automatically

#### Other

In your `config/app.php` add `Phpno1\architecture\providers\ArchitectureServiceProvider::class` to the end of the `providers` array:

```php
'providers' => [
    ...
    Phpno1\Repository\providers\ArchitectureServiceProvider::class,
],
```

## Usage

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

}
```

#### create repository
```php
<?php

namespace App\Repositories\Eloquent;
use App\Test;

use App\Repositories\AbstractRepository;

class TestRepositoryEloquent extends AbstractRepository implements ITestRepository
{
    public function entity()
    {
            return Test::class;
    }
}
```

## Methods

### Phpno1\Architecture\Contracts\IRepository;

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

### Phpno1\Architecture\Eloquent;


  ```php
    $this->repository->all();
  ```

  ```php
    $this->repository->find(int $id);
  ```

  ```php
    $this->repository->first();
  ```

  ```php
    $this->repository->count();
  ```

  ```php
    $this->repository->findWhere(...$condition);
  ```

  ```php
    $this->repository->findWhereFirst(...$condition);
  ```

  ```php
    $this->repository->findWhereCount(...$condition);
  ```

  ```php
    $this->repository->paginate(int $perPage = 10);
  ```

  ```php
    $this->repository->create(array $properties);
  ```

  ```php
    $this->repository->update(int $id, array $properties);
  ```

  ```php
    $this->repository->delete(int $id);
  ```

  ```php
    $this->repository->withCriteria(...$criteria);
  ```

  ```php
    $this->repository->toEntity();
  ```

  ```php
    $this->repository->toRepository(Builder $entity);
  ```

## Use methods

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\TestRepository;

class TestController extends Controller
{

    /**
     * @var TestRepository
     */
    protected $repository;

    public function __construct(TestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function tests()
    {
        $this->repository->all();
    }
}

```
