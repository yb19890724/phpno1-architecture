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
    protected $signature = 'phpno1:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Repository';

    protected $name;

    protected const COMMAND_KEY = 'repository';

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
        $this->call('phpno1:provider');
        $this->call('phpno1:binding', ['name' => $this->name]);
    }

    protected function getTplVars()
    {
        return [
            'class_name'           => $this->name,
            'namespace'            => $this->getFullNamespaceByType(static::COMMAND_KEY),
            'namespace_eloquent'   => $this->getFullNamespaceByType('repository_eloquent'),
            'namespace_model'      => $this->getFullNamespaceByType('model'),
        ];
    }
}
