<?php

namespace Phpno1\Architecture\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Architecture\Generator\GeneratorHelp;

class CreateCriteria extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpno1:criteria {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Criteria';

    /**
     * 创建的实体名称
     *
     * @var string
     */
    protected $name;

    protected const COMMAND_KEY = 'criteria';

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
    }

    protected function getTplVars()
    {
        return [
            'class_name' => $this->name,
            'namespace'  => $this->getFullNamespaceByType(static::COMMAND_KEY),
            'interface'  => 'ICriteria',
        ];
    }
}
