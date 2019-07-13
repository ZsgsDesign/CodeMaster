<?php

namespace App\Console\Commands\Babel;

use Illuminate\Console\Command;
use Exception;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'babel:install {extension : The package name of the extension}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a given Babel Extension to NOJ';

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
        $extension = $this->argument('extension');
        $submitter=self::create($extension);
        if(!is_null($submitter)) $submitter->install();
        else throw new Exception("Installer Not Provided");
    }

    public static function create($oj) {
        $className = "App\\Babel\\Extension\\$oj\\Installer";
        if(class_exists($className)) {
            return new $className();
        } else {
            return null;
        }
    }
}