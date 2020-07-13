<?php

namespace Itstructure\MultiMenu\Commands;

use Illuminate\Console\Command;
use Itstructure\MultiMenu\MultiMenuServiceProvider;

/**
 * Class PublishCommand
 *
 * @package Itstructure\MultiMenu\Commands
 *
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'multimenu:publish '.
    '{--force : Overwrite existing files by default. This option can not be used.}'.
    '{--only= : Publish only specific part. Available parts: config, views. This option can not be used.}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Publish the Multi menu package parts.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->info('Starting publication process of Multi menu package parts...');

        $callArguments = ['--provider' => MultiMenuServiceProvider::class];

        if ($this->option('only')) {
            switch ($this->option('only')) {
                case 'config':
                    $this->info('Publish just a part: config.');
                    $callArguments['--tag'] = 'config';
                    break;

                case 'views':
                    $this->info('Publish just a part: views.');
                    $callArguments['--tag'] = 'views';
                    break;

                default:
                    $this->error('Invalid "only" argument value!');
                    return;
                    break;
            }

        } else {
            $this->info('Publish all parts: config, views.');
        }

        if ($this->option('force')) {
            $this->warn('Force publishing.');
            $callArguments['--force'] = true;
        }

        $this->call('vendor:publish', $callArguments);
    }
}
