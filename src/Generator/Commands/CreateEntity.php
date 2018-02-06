<?php

namespace Phpno1\Console\Commands;

use Phpno1\Repositories\Exceptions\RepositoryConfigFailException;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use League\Flysystem\FileExistsException;

/**
 * 仓储自动创建实体文件
 *
 * Class CreateEntity
 * @package App\Console\Commands
 */
class CreateEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:entity {entityName} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Entity';

    /**
     * 创建的实体名称
     *
     * @var string
     */
    protected $entityName;

    /**
     * 是否添加了资源参数
     *
     * @var boolean
     */
    protected $isResource;

    /**
     * 仓储的配置文件读取
     *
     * @var array
     */
    protected $configInfo;

    /**
     * 根据对话框的选择，标记要创建的文件列表
     *
     * @var array|mixed
     */
    protected $createLists;

    /**
     * 当前正在创建的文件类型
     *
     * @var string
     */
    protected $currentKey;

    /**
     * @var Filesystem
     */
    protected $filesystem;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->configInfo  = $this->initCreateInfo();
        $this->createLists = $this->configInfo['auto_create'] ?? [];
        $this->filesystem = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        throw_unless(
            $this->checkInfoComplete(),
            new RepositoryConfigFailException()
        );

        $this->isResource = $this->option('resource');
        $this->entityName = ucfirst($this->argument('entityName'));
        $this->showConfirm();
        $this->createFiles();
    }

    /**
     * 根据createLists属性的标记，来创建文件
     */
    protected function createFiles()
    {
        $this->createRepository();
        $this->binding();
        // todo... 修改成排列的
        foreach ($this->createLists as $key => $item) {
            if ($item) {
                $methodName = 'create' . ucfirst($key);

                if (method_exists($this, $methodName)) {
                    $this->currentKey = $key;
                    $this->{$methodName}();
                }
            }
        }
    }

    /**
     * 创建仓储
     */
    protected function createRepository()
    {
        $tplList = [
            'upper_name' => $this->entityName,
            'namespace'  => $this->getNamespaceByType('repository'),
        ];
        $tpl = $this->setTpl('repository', $tplList, 'resource_method_interface', 'find');
        $this->writeFileByType($tpl, $this->entityName, 'repository');

        $tplList = [
            'upper_name'          => $this->entityName,
            'namespace'           => $this->getNamespaceByType('repository_eloquent'),
            'model_namespace'     => $this->getNamespaceByType('model'),
            'interface_namespace' => $this->getNamespaceByType('repository'),
        ];
        $tpl = $this->setTpl('repository_eloquent', $tplList, 'resource_method', 'find');
        $this->writeFileByType($tpl, $this->entityName, 'repository_eloquent');
    }

    /**
     * 服务绑定
     */
    protected function binding()
    {
        $namespace = $this->configInfo['paths']['provider'];
        $path = app_path(str_replace('\\', '/', $namespace) . '.php');
        $provider = $this->filesystem->get($path);
        $interfaceInfo  = $this->getNamespaceByType('repository') . '\\' . $this->entityName . 'Repository';
        $repositoryInfo = $this->getNamespaceByType('repository_eloquent') . '\\' . $this->entityName . 'RepositoryEloquent';
        $tplList = [
            'interface'  => $interfaceInfo,
            'repository' => $repositoryInfo,
        ];
        $tpl = $this->getTplByType('binding');
        $tpl = $this->filesystem->get($tpl);
        $tpl = $this->replaceTplByList($tpl, $tplList);
        $newTpl = str_replace('//end-binding', $tpl, $provider);
        $this->filesystem->put($path, $newTpl);

    }

    /**
     * 创建控制器
     */
    protected function createController()
    {
        $baseNamespace = $this->configInfo['paths'][$this->currentKey] ?? '';
        $commendArr = ['name' => $baseNamespace . '/' . $this->entityName . ucfirst($this->currentKey)];

        if ($this->isResource) {
            $commendArr['--resource'] = true;
        }

        $this->call('make:controller', $commendArr);
    }

    /**
     * 创建request验证文件
     */
    protected function createRequest()
    {
        $types = ['create', 'update'];
        $suffix = ucfirst($this->currentKey);

        foreach ($types as $item) {
            $commendArr = ['name' => $this->entityName . '/' . $this->entityName . ucfirst($item) . $suffix];
            $this->call('make:request', $commendArr);
        }
    }

    /**
     * 创建service文件
     */
    protected function createService()
    {
        $tplList = [
            'name'                 => lcfirst($this->entityName),
            'upper_name'           => $this->entityName,
            'namespace'            => $this->getNamespaceByType($this->currentKey),
            'repository_namespace' => $this->getNamespaceByType('repository') . '\\' . $this->entityName . 'Repository',
        ];
        $tpl = $this->setTpl($this->currentKey, $tplList);
        $this->writeFileByType($tpl, $this->entityName);
    }

    /**
     * 创建model
     * 同时创建factory填充工厂，以及表结构文件
     */
    protected function createModel()
    {
        $commendArr = ['name' => str_plural(ucfirst($this->currentKey)) . '/' . $this->entityName];
        $commendArr['--migration'] = true;
        $commendArr['--factory']   = true;

        $this->call('make:model', $commendArr);
    }

    /**
     * 创建自定义response响应文件
     */
    protected function createResponse()
    {
        $responseTplPath = $this->getTplByType($this->currentKey);
        $responseType = ['index', 'show'];
        $tplList = [
            'name'       => lcfirst($this->entityName),
            'upper_name' => $this->entityName,
            'namespace'  => $this->getNamespaceByType($this->currentKey) . '\\' . $this->entityName,
        ];

        if (!$this->isResource) {
            array_pop($responseType);
        }

        foreach ($responseType as $item) {
            $responseTpl = $this->filesystem->get($responseTplPath);
            $tplList['response_type'] = ucfirst($item);
            $responseTpl = $this->replaceTplByList($responseTpl, $tplList);
            $fileName = $this->entityName . ucfirst($item);
            $this->writeFileByType($responseTpl, $fileName);
        }
    }

    /**
     * 创建seeder填充文件
     */
    protected function createSeeder()
    {
        $commendArr = ['name' => $this->entityName . ucfirst($this->currentKey)];
        $this->call('make:seeder', $commendArr);
    }

    /**
     * 获取配置文件
     *
     * @return array
     */
    protected function initCreateInfo()
    {
        return config('repository.generator', []);
    }

    /**
     * 提示框操作
     */
    protected function showConfirm()
    {
        $ret = $this->createLists;

        foreach ($this->createLists as $key => $item) {
            if ($this->confirm('Do you want to create ' . $key . '? [y|n]')) {
                $ret[$key] = true;
                $this->info('continue');
            } else {
                $ret[$key] = false;
                $this->warn('skip');
            }
        }

        $this->createLists = $ret;
    }

    /**
     * 验证信息参数
     *
     * @return bool
     */
    protected function checkInfoComplete()
    {
        if (empty($this->configInfo) || empty($this->createLists)) {
            return false;
        }

        return true;
    }

    /**
     * 传入类型，获取配置文件中的命名空间
     *
     * @param $type
     * @return string
     */
    protected function getNamespaceByType($type)
    {
        $base = $this->configInfo['root_namespace'] ?? '';

        return $base . $this->configInfo['paths'][$type];
    }

    /**
     * 模板替换操作
     *
     * @param $serviceTpl
     * @param $tplList
     * @return mixed
     */
    protected function replaceTplByList($serviceTpl, $tplList)
    {
        foreach ($tplList as $key => $item) {
            $tpl = '{' . $key . '}';
            $serviceTpl = str_replace($tpl, $item, $serviceTpl);
        }

        return $serviceTpl;
    }

    /**
     * 设置资源方法模板
     *
     * @param $baseTpl
     * @param $action
     * @return mixed
     */
    protected function setResourceMethodTpl($baseTpl, $action, $tplName)
    {
        $methodTplPath = $this->configInfo['tpl_path'] . '/' . $tplName . '.tpl';
        $methodTpl = $this->filesystem->get($methodTplPath);
        $methodTpl = $this->replaceTplByList($methodTpl, ['action' => $action, 'upper_name' => $this->entityName]);

        return $this->replaceTplByList($baseTpl, [$tplName => $methodTpl]);
    }

    /**
     * 把资源方法标签替换为空
     *
     * @param $baseTpl
     * @return mixed
     */
    protected function setResourceMethodTplToEmpty($baseTpl, $tplName)
    {
        return str_replace('{' . $tplName . '}', '', $baseTpl);
    }

    /**
     * 写入文件操作
     *
     * @param $content
     * @param $fileName
     */
    protected function writeFileByType($content, $fileName, $key = '')
    {
        $key = empty($key) ? $this->currentKey : $key;
        $upperKey = ucfirst($key);
        $basePath = str_replace('\\', '/', $this->configInfo['paths'][$key]);
        $file = $basePath . '/' . $fileName . ucfirst(camel_case($upperKey)) . '.php';
        $file = app_path($file);

        throw_if(
            $this->filesystem->exists($file),
            new FileExistsException($file)
        );

        $dir = dirname($file);

        if (!$this->filesystem->isDirectory($dir)) {
            $this->filesystem->makeDirectory($dir, 0777, true, true);
        }

        $this->filesystem->put($file, $content);
        $this->info($upperKey . ' created successfully.');
    }

    /**
     * 根据类型获取模板路径
     *
     * @param $type
     * @return string
     */
    protected function getTplByType($type)
    {
        return $this->configInfo['tpl_path'] . '/' . $type . '.tpl';
    }


    /**
     * 模板设置
     *
     * @param $type
     * @param $replaceData
     * @param string $resourceTplName
     * @param string $action
     * @return mixed
     */
    protected function setTpl($type, $replaceData, $resourceTplName = 'resource_method', $action = 'get')
    {
        $tplPath = $this->getTplByType($type);
        $tpl = $this->filesystem->get($tplPath);
        $tpl = $this->replaceTplByList($tpl, $replaceData);

        return $this->isResource
            ? $this->setResourceMethodTpl($tpl, $action, $resourceTplName)
            : $this->setResourceMethodTplToEmpty($tpl, $resourceTplName);
    }
}
