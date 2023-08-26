<?php

namespace App\Jobs;

use App\Services\StockService\StockServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTheStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $orderId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(StockServiceContract $stockService): void
    {
        $stockService->updateStock($this->orderId);
    }
}
