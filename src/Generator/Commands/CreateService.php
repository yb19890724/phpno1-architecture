<?php

namespace Phpno1\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Generator\GeneratorHelp;

class CreateService extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:service {name} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Service';

    protected $name;

    protected $option;

    protected const COMMAND_KEY = 'service';

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
        $this->option = $this->option('resource');
        $tplContent = $this->getFullTplContent(static::COMMAND_KEY, $this->name, $this->option, 'method');
        $this->writeFileByType(static::COMMAND_KEY, $this->name, $tplContent);
    }

    protected function getTplVars()
    {
        return [
            'var_name'             => lcfirst($this->name),
            'class_name'           => $this->name,
            'namespace'            => $this->getFullNamespaceByType(static::COMMAND_KEY),
            'repository_injection' => $this->getFullNamespaceByType('repository') . '\\' . $this->name . 'Repository',
        ];
    }
}
