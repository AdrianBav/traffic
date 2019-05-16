<?php

namespace AdrianBav\Traffic\Console;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traffic:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database migrations for Traffic';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('migrate', [
            '--database' => 'traffic',
            '--path' => 'vendor/adrianbav/traffic/database/migrations',
        ]);
    }
}
