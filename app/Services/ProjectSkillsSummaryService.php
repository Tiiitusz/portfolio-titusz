<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\File;

class ProjectSkillsSummaryService
{
    private string $outputPath;

    public function __construct()
    {
        $this->outputPath = storage_path('app/private/project-skills-summary.txt');
    }

    public function regenerateFile(): string
    {
        $summary = $this->buildSummaryText();

        $directory = dirname($this->outputPath);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put($this->outputPath, $summary.PHP_EOL);

        return $summary;
    }

    public function readSummary(): ?string
    {
        if (! File::exists($this->outputPath) || ! File::isReadable($this->outputPath)) {
            return null;
        }

        $summary = trim((string) File::get($this->outputPath));

        return $summary === '' ? null : $summary;
    }

    public function buildSummaryText(): string
    {
        $counts = [];

        Project::query()->select('technologies')->chunk(100, function ($projects) use (&$counts) {
            foreach ($projects as $project) {
                $skills = json_decode((string) $project->technologies, true);

                if (! is_array($skills)) {
                    continue;
                }

                foreach ($skills as $skill) {
                    if (! is_string($skill)) {
                        continue;
                    }

                    $normalized = strtolower(trim($skill));

                    if ($normalized === '') {
                        continue;
                    }

                    $counts[$normalized] = ($counts[$normalized] ?? 0) + 1;
                }
            }
        });

        if ($counts === []) {
            return 'Skills I used in my projects, the more a skill is included, the better I am at that skill: none listed yet.';
        }

        arsort($counts);

        $items = collect($counts)
            ->map(fn (int $count, string $skill) => sprintf('%s (%d)', $skill, $count))
            ->values()
            ->all();

        return 'Skills I used in my projects, the more a skill is included, the better I am at that skill: '.implode(', ', $items).'.';
    }
}
