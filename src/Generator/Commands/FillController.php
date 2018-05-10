<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Phpno1\Architecture\Generator\GeneratorHelp;

class FillController extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpno1:fill-c {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill Controller';

    protected $name;

    protected const COMMAND_KEY = 'controller';

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
        $this->fileReplaceContent(static::COMMAND_KEY, $this->name, $tplContent, 'Http\\Controllers');
    }

    protected function getTplVars()
    {
        $namespace = $this->getNamespaceByType(static::COMMAND_KEY);

        if (!empty($namespace)) {
            $namespace = '\\' . $namespace;
        }

        return [
            'class_name' => $this->name,
            'var_name'   => lcfirst($this->name),
            'classes_name' => Str::plural($this->name),
            'namespace'  => $namespace,
        ];
    }
}
