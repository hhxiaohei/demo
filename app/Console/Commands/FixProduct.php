<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\TrackProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forone:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修复产品信息';

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
        $products = TrackProduct::whereEnabled(false)->get();
    }
}
