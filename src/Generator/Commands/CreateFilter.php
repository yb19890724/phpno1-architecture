<?php

namespace Phpno1\Repository\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Repository\Generator\GeneratorHelp;

class CreateFilter extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpno1:filter {name} {--prefix=} {--sort}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Filter And sort';

    protected $name;

    protected $sort;

    protected $prefix;

    protected const COMMAND_KEY = 'filter';

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
        $this->sort = $this->option('sort');
        $this->prefix = $this->option('prefix') ?? '';
        $tplContent = $this->getFullTplContent(static::COMMAND_KEY, $this->name, $this->sort, 'sort_method', true);
        $this->writeFileByType(static::COMMAND_KEY, $this->name, $tplContent, $this->prefix);
    }

    protected function getTplVars()
    {
        return [
            'class_name'     => $this->name,
            'namespace'      => $this->setPrefix($this->getFullNamespaceByType(static::COMMAND_KEY)),
            'var_name'       => snake_case($this->name),
            'sort_interface' => $this->sort ? 'implements IOrder' : ''
        ];
    }

    protected function setPrefix($namespace)
    {
        if (!empty($this->prefix)) {
            return $namespace . '\\' . $this->prefix . ';';
        }

        return $namespace . ';';
    }
}
