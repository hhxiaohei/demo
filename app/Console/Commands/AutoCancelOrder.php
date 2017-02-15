<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forone:cancel_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取消超时订单';

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
        $orders =  Order::with("carts")->needCancel()->get();
        foreach($orders as $order){
            $order->autoCancel();
        }
    }
}
