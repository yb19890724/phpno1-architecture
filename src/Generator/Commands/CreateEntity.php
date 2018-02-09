<?php

namespace Phpno1\Repository\Generator\Commands;

use Illuminate\Console\Command;

/**
 * 仓储自动创建实体文件
 *
 * Class CreateEntity
 * @package App\Console\Commands
 */
class CreateEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:entity {name} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Entity';


    protected $name;

    protected $option;

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
        $this->name = ucfirst($this->argument('name'));
        $this->option = $this->option('resource') ?? false;
        $params = ['name' => $this->name];
        $fullParams = array_merge($params, ['--resource' => $this->option]);
        $this->call('create:controller', $fullParams);
        $this->call('create:model', $params);
        $this->call('create:repository', $params);

        if ($this->confirm('Do you want to create Service ? [y|n]')) {
            $this->call('create:service', $fullParams);
        } else {
            $this->warn('skip');
        }

        if ($this->confirm('Do you want to create Request ? [y|n]')) {
            $name = $this->name . 'StoreRequest';
            $this->call('create:request', ['name' => $name, '--dir' => $this->name]);
            $name = $this->name . 'UpdateRequest';
            $this->call('create:request', ['name' => $name, '--dir' => $this->name]);
        } else {
            $this->warn('skip');
        }

        if ($this->confirm('Do you want to create Response ? [y|n]')) {
            $name = $this->name . 'IndexResponse';
            $this->call('create:response', ['name' => $name], ['--dir' => $this->name]);
            $name = $this->name . 'ShowResponse';
            $this->call('create:response', ['name' => $name], ['--dir' => $this->name]);
        } else {
            $this->warn('skip');
        }

        if ($this->confirm('Do you want to create Seeder ? [y|n]')) {
            $this->call('create:seeder', $params);
        }
    }
}
