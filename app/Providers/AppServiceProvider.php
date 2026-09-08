<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Contracts\ClientContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ClientContract::class, function () {
            $caBundle = $this->resolveCaBundlePath();

            $httpClient = new Client([
                'verify' => $caBundle ?? true,
                'timeout' => config('openai.request_timeout', 30),
            ]);

            return OpenAI::factory()
                ->withApiKey(config('openai.api_key'))
                ->withOrganization(config('openai.organization'))
                ->withProject(config('openai.project'))
                ->withBaseUri(config('openai.base_uri') ?? 'api.openai.com/v1')
                ->withHttpClient($httpClient)
                ->make();
        });

            $this->app->alias(ClientContract::class, \OpenAI\Client::class);
    }

    /**
     * Resolve a readable CA bundle path from config and known locations.
     */
    private function resolveCaBundlePath(): ?string
    {
        $candidates = [
            config('openai.ca_bundle'),
            env('SSL_CERT_FILE'),
            base_path('cacert.pem'),
            storage_path('cacert.pem'),
        ];

        foreach ($candidates as $path) {
            if (is_string($path) && $path !== '' && is_readable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $caBundle = $this->resolveCaBundlePath();

        if ($caBundle !== null) {
            ini_set('curl.cainfo', $caBundle);
            ini_set('openssl.cafile', $caBundle);
            putenv("SSL_CERT_FILE={$caBundle}");
        }
    }
}
