<?php

namespace Phpno1\Console\Commands;

use Illuminate\Console\Command;

class CreateFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:filter {name} {--sort}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Filter And sort';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
