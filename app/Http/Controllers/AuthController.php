<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'The provided credentials are incorrect.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function dashboard(): View
    {
        $projects = Project::latest()->get();

        return view('admin.dashboard', compact('projects'));
    }

    public function createProject(): View
    {
        return view('admin.projects.create', [
            'project' => null,
        ]);
    }

    public function storeProject(Request $request): RedirectResponse
    {
        $validated = $this->validateProject($request, false);
        [$thumbnail, $images] = $this->storeProjectFiles($request);

        Project::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? '',
            'description' => $validated['description'],
            'thumbnail' => $thumbnail,
            'github_url' => $validated['github_url'] ?? '',
            'technologies' => json_encode($this->splitList($validated['technologies'] ?? '')),
            'images' => json_encode($images),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function editProject(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function updateProject(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateProject($request, true);
        $removedImages = $request->input('remove_images', []);
        [$thumbnail, $images] = $this->storeProjectFiles($request, $project, $removedImages);

        $project->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? '',
            'description' => $validated['description'],
            'thumbnail' => $thumbnail,
            'github_url' => $validated['github_url'] ?? '',
            'technologies' => json_encode($this->splitList($validated['technologies'] ?? '')),
            'images' => json_encode($images),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function destroyProject(Project $project): RedirectResponse
    {
        $thumbnail = $project->thumbnail;
        $galleryImages = json_decode($project->images ?? '[]', true) ?: [];

        $project->delete();

        $imagesToCheck = array_values(array_unique(array_filter(array_merge([$thumbnail], $galleryImages))));

        foreach ($imagesToCheck as $image) {
            $this->deleteStoredImageIfUnused($image);
        }

        return redirect()->route('admin.dashboard');
    }

    public function uploadProjectImages(Request $request, Project $project)
    {
        $maxImageKb = $this->maxImageUploadKilobytes();

        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:'.$maxImageKb],
        ], [
            'images.*.uploaded' => 'One of the selected images failed to upload. The current server limit is '.$maxImageKb.' KB per image.',
            'images.*.max' => 'Each image must be '.$maxImageKb.' KB or smaller.',
        ]);

        $existingImages = json_decode($project->images ?? '[]', true) ?: [];
        $uploadedImages = [];

        foreach ($request->file('images', []) as $imageFile) {
            if ($imageFile instanceof UploadedFile) {
                $filename = $this->storeUploadedImage($imageFile);
                $existingImages[] = $filename;
                $uploadedImages[] = [
                    'name' => $filename,
                    'url' => asset('images/'.$filename),
                ];
            }
        }

        $project->update([
            'images' => json_encode(array_values(array_unique($existingImages))),
        ]);

        return response()->json([
            'images' => $uploadedImages,
        ]);
    }

    private function validateProject(Request $request, bool $isUpdate): array
    {
        $maxImageKb = $this->maxImageUploadKilobytes();

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'thumbnail' => array_merge($isUpdate ? ['nullable'] : ['required'], ['file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:'.$maxImageKb]),
            'github_url' => ['nullable', 'url', 'max:255'],
            'technologies' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:'.$maxImageKb],
            'is_featured' => ['nullable', 'boolean'],
        ], [
            'thumbnail.uploaded' => 'Thumbnail upload failed. The current server limit is '.$maxImageKb.' KB.',
            'thumbnail.max' => 'Thumbnail must be '.$maxImageKb.' KB or smaller.',
            'images.*.uploaded' => 'One of the selected images failed to upload. The current server limit is '.$maxImageKb.' KB per image.',
            'images.*.max' => 'Each image must be '.$maxImageKb.' KB or smaller.',
        ]);
    }

    private function maxImageUploadKilobytes(): int
    {
        $configuredKb = $this->iniSizeToKilobytes(ini_get('upload_max_filesize'));

        if ($configuredKb <= 0) {
            return 2048;
        }

        return $configuredKb;
    }

    private function iniSizeToKilobytes(string $size): int
    {
        $size = trim($size);

        if ($size === '') {
            return 0;
        }

        $unit = strtolower(substr($size, -1));
        $value = (float) $size;

        return match ($unit) {
            'g' => (int) round($value * 1024 * 1024),
            'm' => (int) round($value * 1024),
            'k' => (int) round($value),
            default => (int) round($value / 1024),
        };
    }

    private function storeProjectFiles(Request $request, ?Project $project = null, array $removedImages = []): array
    {
        $thumbnail = $project?->thumbnail ?? '';

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $this->storeUploadedImage($request->file('thumbnail'));
        }

        $images = $project ? (json_decode($project->images ?? '[]', true) ?: []) : [];

        if (! empty($removedImages)) {
            $removedImages = array_fill_keys($removedImages, true);
            $images = array_values(array_filter($images, fn ($image) => ! isset($removedImages[$image])));

            foreach (array_keys($removedImages) as $image) {
                $this->deleteStoredImage($image);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile instanceof UploadedFile) {
                    $images[] = $this->storeUploadedImage($imageFile);
                }
            }
        }

        return [$thumbnail, $images];
    }

    private function storeUploadedImage(UploadedFile $file): string
    {
        $directory = public_path('images');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('project_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return $filename;
    }

    private function deleteStoredImage(string $filename): void
    {
        $path = public_path('images/'.$filename);

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function deleteStoredImageIfUnused(string $filename): void
    {
        $isStillUsed = Project::query()
            ->where('thumbnail', $filename)
            ->orWhere('images', 'like', '%"'.$filename.'"%')
            ->exists();

        if (! $isStillUsed) {
            $this->deleteStoredImage($filename);
        }
    }

    private function splitList(string $value): array
    {
        return collect(preg_split('/[\r\n,]+/', $value) ?: [])
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}