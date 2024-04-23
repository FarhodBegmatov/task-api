<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch exchange rates from openexchangerates.org API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $response = Http::get('https://openexchangerates.org/api/currencies.json?prettyprint=false&show_alternative=false&show_inactive=false&app_id=1%20API');
        $data = $response->json();

        foreach ($data as $code => $country) {
            ExchangeRate::query()->updateOrCreate(['code' => $code],['country' => $country]);
        }

        $this->info('Exchange rates updated successfully');
    }
}
