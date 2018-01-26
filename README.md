# -phpno1-repository


Installation
Composer
Execute the following command to get the latest version of the package:

composer require phpno1/repository

### Laravel

#### >= laravel5.5

ServiceProvider will be attached automatically

#### Other

增加配置 `config/app.php` add `Phpno1\Repository\providers\RepositoryServiceProvider::class` to the end of the `providers` array:

```php
'providers' => [
    ...
    Phpno1\Repository\providers\RepositoryServiceProvider::class,
],
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