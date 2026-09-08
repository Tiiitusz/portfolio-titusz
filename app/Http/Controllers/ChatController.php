<?php

namespace App\Http\Controllers;

use App\Services\ProjectSkillsSummaryService;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * The system prompt that defines the assistant's personality and knowledge.
     * Edit this to match your real CV and preferred tone.
     */
    private string $baseSystemPrompt = <<<'PROMPT'
You are a friendly, concise AI assistant on Titusz Papp's personal portfolio website.
Your job is to answer visitor questions about Titusz — his skills, experience, and availability.
Never make up information. If you don't know something, say so and invite them to contact Titusz directly.
Keep every response to 2–3 short sentences maximum.

--- About Titusz ---
Name: Titusz Papp
Role: Full-Stack Web Developer
Location: Hungary
Tech stack: PHP (Laravel), JavaScript, Vue.js, Tailwind CSS, MySQL, SQLite
Experience: [TODO — paste relevant CV bullet points here]
Education: [TODO — paste your education here]
Languages: Hungarian (native), English (professional)
Open to: Freelance projects, full-time roles, collaborations
Contact: via the Contact section on this site
PROMPT;

    public function __construct(private readonly ProjectSkillsSummaryService $skillsSummaryService)
    {
    }

    public function diagnostic()
    {
        $apiKeyConfigured = filled(config('openai.api_key'));
        $baseUrlConfigured = filled(config('openai.base_uri')) || filled(env('OPENAI_BASE_URL'));
        $caBundlePath = config('openai.ca_bundle') ?: base_path('cacert.pem');
        $caBundleReadable = is_string($caBundlePath) && $caBundlePath !== '' && is_readable($caBundlePath);

        $checks = [
            'route_available' => true,
            'api_key_configured' => $apiKeyConfigured,
            'base_url_configured' => $baseUrlConfigured,
            'curl_enabled' => extension_loaded('curl'),
            'openssl_enabled' => extension_loaded('openssl'),
            'ca_bundle_readable' => $caBundleReadable,
        ];

        $status = $apiKeyConfigured ? 'ok' : 'missing_key';

        return response()->json([
            'status' => $status,
            'message' => $apiKeyConfigured
                ? 'OpenAI looks configured correctly.'
                : 'Missing OPENAI_API_KEY in .env. Add it and restart the app.',
            'checks' => $checks,
            'hint' => 'Add OPENAI_API_KEY=your_key_here to your .env file.',
        ]);
    }

    public function reply(Request $request)
    {
        logger()->channel('stderr')->info('Incoming chat request', ['request' => $request->all()]);
        $request->validate([
            'messages' => ['required', 'array', 'max:20'],
            'messages.*.role' => ['required', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:500'],
        ]);

        if (! filled(config('openai.api_key'))) {
            return response()->json([
                'status' => 'missing_key',
                'message' => 'OpenAI API key is missing. Add OPENAI_API_KEY to your .env file and restart the app.',
            ], 500);
        }

        try {
            $response = $this->makeOpenAiClient()->chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'max_tokens' => 150,
                'messages' => [
                    ['role' => 'system', 'content' => $this->buildSystemPrompt()],
                    ...$request->input('messages'),
                ],
            ]);

            $content = trim((string) ($response->choices[0]->message->content ?? ''));

            if ($content === '') {
                return response()->json([
                    'status' => 'empty_response',
                    'message' => 'OpenAI returned an empty response.',
                ], 502);
            }

            return response()->json([
                'status' => 'ok',
                'reply' => $content,
            ]);
        } catch (\Throwable $e) {
            logger()->error('OpenAI chat request failed', ['exception' => $e]);

            return response()->json([
                'status' => 'openai_error',
                'message' => 'The AI request failed. Check your OpenAI key, network access, and billing status.',
                'debug' => app()->environment('local') ? $e->getMessage() : null,
            ], 502);
        }
    }

    private function makeOpenAiClient(): \OpenAI\Client
    {
        $caBundlePath = config('openai.ca_bundle') ?: base_path('cacert.pem');
        $verify = is_string($caBundlePath) && $caBundlePath !== '' && is_readable($caBundlePath)
            ? $caBundlePath
            : true;

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

        return $factory->make();
    }

    private function buildSystemPrompt(): string
    {
        $summary = $this->skillsSummaryService->readSummary();

        if ($summary === null) {
            $summary = $this->skillsSummaryService->buildSummaryText();
        }

        return $this->baseSystemPrompt."\n\n{$summary}";
    }
}
