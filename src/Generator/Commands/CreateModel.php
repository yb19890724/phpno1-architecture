<?php

namespace Phpno1\Repository\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Repository\Generator\GeneratorHelp;

class CreateModel extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Model';

    protected $name;

    protected const COMMAND_KEY = 'model';

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
        $this->callCommand(static::COMMAND_KEY, $this->name, 'make:model', ['--migration' => true]);
    }
}
