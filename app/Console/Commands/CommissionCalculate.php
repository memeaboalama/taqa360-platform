<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class CommissionCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:commission-calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    
    public function handle()
    {
        
        DB::update("
        UPDATE merchant_orders mo
        JOIN (
            SELECT 
                m.id AS order_id,
                ROUND((p.commission * (m.subtotal - m.discount)) / 100, 2) AS commission,
                p.commission AS commission_rate,
                mp.package_id AS package_id
            FROM 
                merchant_orders m
            JOIN 
                merchant_packages mp ON mp.merchant_id = m.merchant_id
            JOIN 
                packages p ON p.id = mp.package_id
        ) AS derived ON mo.id = derived.order_id
        SET 
            mo.commission_amount = derived.commission,
            mo.commission_rate = derived.commission_rate,
            mo.package_id = derived.package_id
    ");
    
            $this->info('Commission Calculate successfully.');
        
    }
}
