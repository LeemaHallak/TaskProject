<?php

namespace App\Console\Commands;

use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteDiscount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:discount';

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
        $discounts = Discount::all();
        foreach ($discounts as $discount) {
            $createdAt = $discount->created_at;
            $conditionDate = $createdAt->addDays($discount->duration);
            $currentDate = Carbon::now();
            if ($conditionDate->lte($currentDate)) {
                $discount->delete();
            }
        }
    }


}
