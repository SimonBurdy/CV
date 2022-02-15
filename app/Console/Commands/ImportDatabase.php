<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:database';//this is what you will call  to import table

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the .sql file for ImportXTable';

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
        $sql = public_path('burdysimoncv.sql');// write the sql filename here to import
        
        
        DB::unprepared(file_get_contents($sql));
    }
}
