<?php

namespace Phpno1\Repository\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Repository\Generator\GeneratorHelp;

class CreateRepository extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Repository';

    protected $name;

    protected const COMMAND_KEY = 'repository';

    protected const BINDING_FLG = '//end-binding';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->generatorInit();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name = ucfirst($this->argument('name'));
        $tplContent = $this->getFullTplContent(static::COMMAND_KEY, $this->name, null);
        $this->writeFileByType(static::COMMAND_KEY, $this->name, $tplContent);
        $tplContent = $this->getFullTplContent('repository_eloquent', $this->name, null);
        $this->writeFileByType('repository_eloquent', $this->name, $tplContent);
        $this->binding(static::BINDING_FLG);
    }

    protected function getTplVars()
    {
        return [
            'class_name'           => $this->name,
            'namespace'            => $this->getFullNamespaceByType(static::COMMAND_KEY),
            'namespace_eloquent'   => $this->getFullNamespaceByType('repository_eloquent'),
            'namespace_model'      => $this->getFullNamespaceByType('model'),
            'interface'            => $this->getFullNamespaceByType(static::COMMAND_KEY) . '\\' . $this->name . 'Repository',
            'repository'           => $this->getFullNamespaceByType('repository_eloquent') . '\\' . $this->name . 'RepositoryEloquent',
        ];
    }
}
