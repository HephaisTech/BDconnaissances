<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

class ClearExpiredToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'App:expToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete expired token description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = Sanctum::$personalAccessTokenModel;

        $this->components->task(
            'Pruning tokens with expired expires_at timestamps',
            fn () => $model::where('expires_at', '<', Carbon::now())->delete()
        );

        if ($expiration = config('sanctum.expiration')) {
            $this->components->task(
                'Pruning tokens with expired expiration value based on configuration file',
                fn () => $model::where('created_at', '<', now()->subMinutes($expiration))->delete()
            );
        } else {
            $this->components->warn('Expiration value not specified in configuration file.');
        }

        $this->components->info("Tokens expired  pruned successfully.");

        return 0;
    }
}