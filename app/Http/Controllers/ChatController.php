<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    /**
     * The system prompt that defines the assistant's personality and knowledge.
     * Edit this to match your real CV and preferred tone.
     */
    private string $systemPrompt = <<<PROMPT
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

    public function reply(Request $request)
    {
        $request->validate([
            'messages' => ['required', 'array', 'max:20'],
            'messages.*.role' => ['required', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:500'],
        ]);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'max_tokens' => 150,
            'messages' => [
                ['role' => 'system', 'content' => $this->systemPrompt],
                ...$request->input('messages'),
            ],
        ]);

        return response()->json([
            'reply' => $response->choices[0]->message->content,
        ]);
    }
}
