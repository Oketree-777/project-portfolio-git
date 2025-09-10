<?php

namespace App\Services;

use App\Models\Performance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PerformanceService
{
    /**
     * Create a new performance
     */
    public function create(array $data, ?UploadedFile $image = null): Performance
    {
        if ($image) {
            $data['image'] = $this->uploadImage($image);
        }

        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = $this->parseTags($data['tags']);
        }

        return Performance::create($data);
    }

    /**
     * Update a performance
     */
    public function update(Performance $performance, array $data, ?UploadedFile $image = null): bool
    {
        if ($image) {
            // Delete old image
            if ($performance->image) {
                $this->deleteImage($performance->image);
            }
            $data['image'] = $this->uploadImage($image);
        }

        if (isset($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = $this->parseTags($data['tags']);
        }

        return $performance->update($data);
    }

    /**
     * Delete a performance
     */
    public function delete(Performance $performance): bool
    {
        if ($performance->image) {
            $this->deleteImage($performance->image);
        }

        return $performance->delete();
    }

    /**
     * Upload image
     */
    private function uploadImage(UploadedFile $image): string
    {
        return $image->store('performances', 'public');
    }

    /**
     * Delete image
     */
    private function deleteImage(string $imagePath): bool
    {
        return Storage::disk('public')->delete($imagePath);
    }

    /**
     * Parse tags string to array
     */
    private function parseTags(string $tags): array
    {
        return array_map('trim', explode(',', $tags));
    }

    /**
     * Get performance statistics
     */
    public function getStatistics(): array
    {
        return [
            'total' => Performance::count(),
            'active' => Performance::where('status', true)->count(),
            'featured' => Performance::where('featured', true)->count(),
            'total_views' => Performance::sum('views'),
            'avg_views' => Performance::avg('views'),
            'categories' => Performance::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
        ];
    }

    /**
     * Get top performing works
     */
    public function getTopPerformances(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Performance::active()
            ->orderByDesc('views')
            ->limit($limit)
            ->get();
    }

    /**
     * Search performances
     */
    public function search(string $query, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $performances = Performance::query();

        // Search in title, content, description
        $performances->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('content', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        });

        // Apply filters
        if (isset($filters['category'])) {
            $performances->where('category', $filters['category']);
        }

        if (isset($filters['status'])) {
            $performances->where('status', $filters['status']);
        }

        if (isset($filters['featured'])) {
            $performances->where('featured', $filters['featured']);
        }

        return $performances->orderByDesc('created_at')->paginate(12);
    }
}
