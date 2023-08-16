<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveDuplicateMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $migrations = DB::table('migrations')
            ->select('migration')
            ->groupBy('migration')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($migrations as $migration) {
            DB::table('migrations')
                ->where('migration', $migration->migration)
                ->orderByDesc('batch')
                ->skip(1)
                ->delete();
        }

        $this->info('Duplicate migrations removed successfully.');
    }

}
