<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 2018/2/6
 * Time: 下午9:40
 */

namespace Phpno1\Repository\Generator;


use Illuminate\Support\Facades\File;
use League\Flysystem\FileExistsException;

trait GeneratorHelp
{
    protected $generatorConfig;

    protected $except = ['provider'];

    protected $addSuffix = ['controller', 'request', 'response', 'seeder', 'factory', 'service', 'repository', 'repository_eloquent'];

    protected $generatorTplPathInfo = [
        'base'                 =>  __DIR__."/Templates/",
        'method'               => 'service_method.tpl',
        'sort_method'          => 'sort_method.tpl',
        'binding'              => 'binding.tpl',
        'criteria'             => 'criteria.tpl',
        'filter'               => 'filter.tpl',
        'repository'           => 'repository.tpl',
        'repository_eloquent'  => 'repository_eloquent.tpl',
        'response'             => 'response.tpl',
        'service'              => 'service.tpl',
        'provider'             => 'provider.tpl',
    ];

    protected function generatorInit()
    {
        $this->generatorConfig = config('repository.generator');
    }

    protected function getNamespaceByType($type)
    {
        return $this->generatorConfig['namespace'][$type] ?? '';
    }

    protected function getFullNamespaceByType($type)
    {
        $base = $this->generatorConfig['root_namespace'];

        return $base . $this->getNamespaceByType($type);
    }

    protected function replaceTplVars($tplContent, array $varList)
    {
        foreach ($varList as $key => $item) {
            $tpl = '{' . $key . '}';
            $tplContent = str_replace($tpl, $item, $tplContent);
        }

        return $tplContent;
    }

    protected function callCommand($type, $fileName, $command, $option = [])
    {
        $fileName = $this->setCreateFileName($fileName, $type);
        $commendArr = ['name' => $fileName];
        $commendArr = array_merge($commendArr, $option);
        $this->call($command, $commendArr);
    }

    protected function setCreateFileName($name, $type, $prefix = '')
    {
        $upperType = ucfirst($type);
        $path = $this->getNamespaceByType($type);

        if (in_array($type, $this->addSuffix)) {
            $name = !ends_with($name, $upperType) ? $name . $upperType : $name;
        }

        $name = ucfirst(camel_case($name));
        $name = !empty($prefix) ? $prefix . '/' . $name : $name;
        $name = !empty($path) ? $path . '\\' . $name : $name;

        return $this->setExcept($type, $name);
    }

    protected function setExcept($type, $name)
    {
        if (in_array($type, $this->except)) {
            $arr = explode('\\', $name);

            return implode('\\', array_unique($arr));
        }

        return $name;
    }

    protected function getTplContent($type)
    {
        $basePath = $this->generatorTplPathInfo['base'];
        $path = $this->generatorTplPathInfo[$type];
        $path = $basePath . $path;
        return File::get($path);
    }

    protected function getFullTplContent($type, $name, $option, $method = '', $isSnake = false)
    {
        $tplContent = $this->getTplContent($type);
        $tplContent = $this->replaceTplVars($tplContent, $this->getTplVars());

        if (!empty($method)) {
            $name = $isSnake ? snake_case($name) : $name;
            return $this->setTplMethodByOption($option, $name, $tplContent, $method);
        }

        return $tplContent;
    }

    protected function setTplMethodByOption($option, $name, $content, $methodType)
    {
        if ($option) {
            $methodContent = $this->getTplContent($methodType);
            $methodContent = $this->replaceTplVars($methodContent, ['class_name' => $name]);

            return $this->replaceTplVars($content, [$methodType => $methodContent, 'var_name' => lcfirst($name)]);
        }

        return str_replace('{' . $methodType . '}', '', $content);
    }

    protected function binding($flg)
    {
        $path = $this->getProviderPath();
        $provider = File::get($path);
        $tpl = $this->getFullTplContent('binding', '', '');
        $newTpl = str_replace($flg, $tpl, $provider);
        File::put($path, $newTpl);
        $this->showSuccessInfo('binding');
    }

    protected function getProviderPath()
    {
        $filePath = str_replace('\\', '/', $this->getNamespaceByType('provider'));

        return app_path($filePath) . '.php';
    }

    protected function writeFileByType($type, $name, $content, $prefix = '')
    {
        $fileName = $this->setCreateFileName($name, $type, $prefix) . '.php';
        $fileName = app_path(str_replace('\\', '/', $fileName));

        throw_if(
            File::exists($fileName),
            new FileExistsException($fileName)
        );

        $dir = dirname($fileName);

        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0777, true, true);
        }

        File::put($fileName, $content);
        $this->showSuccessInfo($type);
    }

    protected function showSuccessInfo($name)
    {
        $name = ucfirst(camel_case($name));
        $this->info($name . ' created successfully.');
    }
}