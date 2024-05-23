<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CulturalProperty;
use Carbon\Carbon;

class UpdatePropertyStatus extends Command
{
    protected $signature = 'update:property-status';

    protected $description = 'Update status of cultural properties';

    public function handle()
    {
        // Set the timezone to Manila
        date_default_timezone_set('Asia/Manila');

        // Get current time in Manila timezone
        $now = Carbon::now();

        $properties = CulturalProperty::where('is_Validated', 5)
            ->where('validation_started_at', '<=', $now->subDays(30))
            ->get();

        foreach ($properties as $property) {
            $property->update(['is_Validated' => 6]); // Update to new status
        }

        $this->info('Cultural property statuses updated successfully.');
    }
}
