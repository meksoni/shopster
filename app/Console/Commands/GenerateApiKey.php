<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use Carbon\Carbon;
use Illuminate\Console\Command;


class GenerateApiKey extends Command
{
    protected $signature = 'api-key:generate';
    protected $description = 'Generate a new API key.';

    public function handle()
    {

        ApiKey::where('expires_at', '<=', Carbon::now())->delete();


        $apiKey = bin2hex(random_bytes(32)); // Generisanje slučajnog API ključa
        $expiresAt = Carbon::now()->addMinutes(5); // Vreme isteka ključa (5minuta)

        ApiKey::create([
            'key' => $apiKey,
            'expires_at' => $expiresAt,
        ]);

        $this->info('New API key generated successfully.');

        \Log::info('New API key generated: ' . $apiKey);
    }
}
