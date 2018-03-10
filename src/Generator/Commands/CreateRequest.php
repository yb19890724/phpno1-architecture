<?php

namespace Phpno1\Architecture\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Architecture\Generator\GeneratorHelp;

class CreateRequest extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpno1:request {name} {--dir=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Request';

    protected $name;

    protected $option;

    protected const COMMAND_KEY = 'request';

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
        $this->name  = ucfirst($this->argument('name'));
        $this->option = $this->option('dir');

        if ($this->option) {
            $this->name = ucfirst($this->option) . '\\' . $this->name;
        }

        $this->callCommand(static::COMMAND_KEY, $this->name, 'make:request');
    }
}
