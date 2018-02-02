# -phpno1-repository


Installation
Composer
Execute the following command to get the latest version of the package:

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

##Usage

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