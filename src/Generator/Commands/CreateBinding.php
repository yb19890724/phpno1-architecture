<?php

namespace Phpno1\Repository\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Repository\Generator\GeneratorHelp;

class CreateBinding extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpno1:binding {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $name;

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
        $this->binding(static::BINDING_FLG);
    }

    protected function getTplVars()
    {
        return [
            'interface'            => $this->getFullNamespaceByType('repository') . '\\' . $this->name . 'Repository',
            'repository'           => $this->getFullNamespaceByType('repository_eloquent') . '\\' . $this->name . 'RepositoryEloquent',
        ];
    }
}
