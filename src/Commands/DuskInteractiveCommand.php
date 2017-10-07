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
    protected $signature = 'dusk:interactive
                            {--stop-on-error       : Stop execution upon first error}
                            {--stop-on-failure     : Stop execution upon first error or failure}
                            {--stop-on-warning     : Stop execution upon first warning}
                            {--stop-on-risky       : Stop execution upon first risky test}
                            {--stop-on-skipped     : Stop execution upon first skipped test}
                            {--stop-on-incomplete  : Stop execution upon first incomplete test}
                            {--fail-on-warning     : Treat tests with warnings as failures}
                            {--fail-on-risky       : Treat risky tests as failures}';

    /**
     * Native PHPUnit options that can be passed when running tests.
     *
     * @var array
     */
    protected $phpUnitOptions = [
        'stop-on-error',
        'stop-on-failure',
        'stop-on-warning',
        'stop-on-risky',
        'stop-on-skipped',
        'stop-on-incomplete',
        'fail-on-warning',
        'fail-on-risky',
    ];

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

        foreach (glob(base_path() . $this->directory . '*.php') as $filename) {
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
        switch ($key) {
            case 0:
                // exit program
                $this->info('Exiting program');
                break;
            case 1:
                // all tests
                $this->info('Starting Laravel Dusk normally');

                // execute dusk
                exec("php artisan dusk {$this->setPhpUnitOptions()}", $output);

                // output result
                $this->output($output);
                break;
            default:
                // single test
                $this->info('Starting Laravel Dusk with the following test ' . $files[$key]);

                // execute dusk with the specific test
                $testName = substr($this->directory, 1) . $files[$key] . '.php';
                exec("php artisan dusk {$testName} {$this->setPhpUnitOptions()}", $output);

                // output result
                $this->output($output);
                break;
        }
    }

    /**
     * Set the options to run the Dusk tests with.
     *
     * @return string
     */
    public function setPhpUnitOptions()
    {
        $options = '';

        foreach ($this->phpUnitOptions as $phpUnitOption) {
            if ($this->option($phpUnitOption)) {
                $options .= "--{$phpUnitOption} ";
            }
        }

        return $options;
    }

    /**
     * Output the result.
     *
     * @param array $output
     */
    public function output($output)
    {
        foreach ($output as $line) {
            $this->info($line);
        }
    }
}
