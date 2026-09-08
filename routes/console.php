<?php

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('openai:chat {prompt* : Message to send to OpenAI} {--model=gpt-4o-mini : Model to use}', function () {
    if (! filled(config('openai.api_key'))) {
        $this->error('OPENAI_API_KEY is missing in your .env file.');
        return self::FAILURE;
    }

    $prompt = implode(' ', $this->argument('prompt'));
    $caBundlePath = config('openai.ca_bundle') ?: base_path('cacert.pem');
    $verify = is_string($caBundlePath) && $caBundlePath !== '' && is_readable($caBundlePath)
        ? $caBundlePath
        : true;

    try {
        $factory = \OpenAI::factory()
            ->withApiKey((string) config('openai.api_key'))
            ->withOrganization(config('openai.organization'))
            ->withHttpClient(new GuzzleClient([
                'verify' => $verify,
                'timeout' => (int) config('openai.request_timeout', 30),
            ]));

        if (is_string(config('openai.project')) && config('openai.project') !== '') {
            $factory->withProject((string) config('openai.project'));
        }

        if (is_string(config('openai.base_uri')) && config('openai.base_uri') !== '') {
            $factory->withBaseUri((string) config('openai.base_uri'));
        }

        $response = $factory->make()->chat()->create([
            'model' => (string) $this->option('model'),
            'max_tokens' => 150,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $reply = trim((string) ($response->choices[0]->message->content ?? ''));

        if ($reply === '') {
            $this->error('OpenAI returned an empty response.');
            return self::FAILURE;
        }

        $this->line($reply);
        return self::SUCCESS;
    } catch (\Throwable $e) {
        $this->error('OpenAI request failed: '.$e->getMessage());
        return self::FAILURE;
    }
})->purpose('Send a prompt to OpenAI and print the response');
