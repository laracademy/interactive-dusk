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

    protected $options;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive Dusk Tests';

    protected $testDirectory;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->testDirectory = 'tests/Browser';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('********* Interactive Dusk Tests *********');

        // find all tests
        $files = [
            0 => 'All Tests'
        ];

        foreach(glob(base_path() .'/'. $this->testDirectory .'/*.php') as $filename) {
            $files[] = str_replace(base_path() .'/'. $this->testDirectory .'/', '', $filename);
        }

        // choice returns the value from the array
        $choice = $this->choice('Which test would you like to run?', $files);

        // transform it into the key
        $key = array_search($choice, $files);

        if($key == 0) {
            // all the tests
            $this->info('Running all Dusk tests');

            exec('php artisan dusk', $output);

            foreach($output as $line) {
                $this->info($line);
            }

        } else {
            // single test
            $this->info('Running test for '. $files[$key]);

            exec('php artisan dusk '. $this->testDirectory .'/'. $files[$key], $output);

            foreach($output as $line) {
                $this->info($line);
            }
        }

    }

}