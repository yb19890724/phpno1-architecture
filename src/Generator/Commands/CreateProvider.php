<?php

namespace Phpno1\Repository\Generator\Commands;

use Illuminate\Console\Command;
use Phpno1\Repository\Generator\GeneratorHelp;
use League\Flysystem\FileExistsException;

class CreateProvider extends Command
{
    use GeneratorHelp;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repository:provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Create Provider';

    protected $fullName;

    protected $className;

    protected const COMMAND_KEY = 'provider';

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
        $this->fullName = $this->getFullNamespaceByType(static::COMMAND_KEY);
        $this->className = class_basename($this->fullName);
        $tplContent = $this->getFullTplContent(static::COMMAND_KEY, '', null);

        try {
            $this->writeFileByType(static::COMMAND_KEY, $this->className, $tplContent);
        } catch (FileExistsException $e) {
            return;
        }

    }

    public function getTplVars()
    {
        return [
            'class_name' => $this->className,
            'namespace'  => str_replace('\\' . $this->className, '', $this->fullName),
        ];
    }


}
