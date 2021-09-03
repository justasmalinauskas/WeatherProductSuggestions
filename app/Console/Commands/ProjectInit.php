<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProjectInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

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
        if($this->checkDBConnection() === true) {
            $this->call('optimize');
            $this->call('migrate'); // optional arguments
            $this->call('db:seed');
            $this->call('optimize');
            return 0;
        }
        return 1;

    }


    private function checkDBConnection(): bool
    {
        for ($i = 0; $i < 6; $i++) {
            try {
                DB::connection()->getPdo();
                if(DB::connection()->getDatabaseName()){
                    return true;
                }else{
                    sleep(5);
                }
            } catch (\Exception $e) {
                sleep(5);
            }
        }
        return false;
    }

}
