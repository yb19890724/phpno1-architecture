# phpno1-repository

<a href="https://github.com/yb19890724/phpno1-repository/README.md">English description</a></p>

composer 安装
执行以下命令获取包的最新版本:

composer require phpno1/repository

### Laravel

#### >= laravel5.5

ServiceProvider will be attached automatically

#### Other

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

class Test extends Model
{

}
```

#### create repository
```php

namespace App\Repositories\Eloquent;
use App\Test;

use App\Repositories\AbstractRepository;

class TestRepositoryEloquent extends AbstractRepository
{
    public function entity()
    {
            return Test::class;
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