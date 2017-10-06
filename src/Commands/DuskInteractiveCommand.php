<?php

namespace Laracademy\Commands\Commands;

use Illuminate\Console\Command;

class DuskInteractiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive Dusk Tests';

    /**
     * The directory of where the tests are kept.
     *
     * @var string
     */
    protected $directory = '/tests/Browser/';

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
        $this->info('********* Interactive Laravel Dusk Tests *********');

        // find all tests
        $files = [
            0 => 'Exit',
            1 => 'Run Laravel Dusk Normally',
        ];

        foreach(glob(base_path() . $this->directory .'*.php') as $filename) {
            // replace full path
            $f = str_replace(base_path() . $this->directory, '', $filename);

            // replace .php
            $f = str_replace('.php', '', $f);

            // store file name
            $files[] = $f;
        }

        // choice returns the value from the array
        $choice = $this->choice('Please select a test from the list below that you would like to run', $files);

        // transform it into the key
        $key = array_search($choice, $files);

        // what kind of dusk test are we running
        switch($key) {
            case 0:
                // exit program
                $this->info('Exiting program');
                break;
            case 1:
                // all tests
                $this->info('Starting Laravel Dusk normally');

                // execute dusk
                exec('php artisan dusk', $output);

                // output result
                $this->output($output);
                break;
            default:
                // single test
                $this->info('Starting Laravel Dusk with the following test '. $files[$key]);

                // execute dusk with the specific test
                exec('php artisan dusk '. substr($this->directory, 1) . $files[$key] .'.php', $output);

                // output result
                $this->output($output);
                break;
        }
    }

    public function output($output)
    {
        foreach($output as $line) {
            $this->info($line);
        }
    }

}