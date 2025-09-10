<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Performance;
use App\Services\PerformanceService;

class PerformanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:manage {action} {--id=} {--title=} {--content=} {--category=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage performances from command line';

    protected $service;

    public function __construct(PerformanceService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listPerformances();
                break;
            case 'create':
                $this->createPerformance();
                break;
            case 'update':
                $this->updatePerformance();
                break;
            case 'delete':
                $this->deletePerformance();
                break;
            case 'stats':
                $this->showStats();
                break;
            case 'cleanup':
                $this->cleanup();
                break;
            default:
                $this->error('Invalid action. Available actions: list, create, update, delete, stats, cleanup');
                return 1;
        }

        return 0;
    }

    private function listPerformances()
    {
        $performances = Performance::orderByDesc('created_at')->get();

        if ($performances->isEmpty()) {
            $this->info('No performances found.');
            return;
        }

        $this->table(
            ['ID', 'Title', 'Category', 'Status', 'Featured', 'Views', 'Created'],
            $performances->map(function ($performance) {
                return [
                    $performance->id,
                    $performance->title,
                    $performance->category,
                    $performance->status ? 'Active' : 'Inactive',
                    $performance->featured ? 'Yes' : 'No',
                    $performance->views,
                    $performance->created_at->format('Y-m-d H:i')
                ];
            })
        );
    }

    private function createPerformance()
    {
        $title = $this->option('title') ?: $this->ask('Enter title');
        $content = $this->option('content') ?: $this->ask('Enter content');
        $category = $this->option('category') ?: $this->choice('Select category', ['web', 'mobile', 'desktop', 'other']);

        $data = [
            'title' => $title,
            'content' => $content,
            'category' => $category,
            'status' => true,
            'featured' => false,
        ];

        $performance = $this->service->create($data);
        $this->info("Performance '{$performance->title}' created successfully with ID: {$performance->id}");
    }

    private function updatePerformance()
    {
        $id = $this->option('id') ?: $this->ask('Enter performance ID');
        $performance = Performance::find($id);

        if (!$performance) {
            $this->error('Performance not found.');
            return;
        }

        $title = $this->option('title') ?: $this->ask('Enter new title', $performance->title);
        $content = $this->option('content') ?: $this->ask('Enter new content', $performance->content);
        $category = $this->option('category') ?: $this->choice('Select category', ['web', 'mobile', 'desktop', 'other'], array_search($performance->category, ['web', 'mobile', 'desktop', 'other']));

        $data = [
            'title' => $title,
            'content' => $content,
            'category' => $category,
        ];

        $this->service->update($performance, $data);
        $this->info("Performance '{$performance->title}' updated successfully.");
    }

    private function deletePerformance()
    {
        $id = $this->option('id') ?: $this->ask('Enter performance ID');
        $performance = Performance::find($id);

        if (!$performance) {
            $this->error('Performance not found.');
            return;
        }

        if ($this->confirm("Are you sure you want to delete '{$performance->title}'?")) {
            $this->service->delete($performance);
            $this->info("Performance '{$performance->title}' deleted successfully.");
        }
    }

    private function showStats()
    {
        $stats = $this->service->getStatistics();

        $this->info('Performance Statistics:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Performances', $stats['total']],
                ['Active Performances', $stats['active']],
                ['Featured Performances', $stats['featured']],
                ['Total Views', $stats['total_views']],
                ['Average Views', round($stats['avg_views'], 2)],
            ]
        );

        if (!empty($stats['categories'])) {
            $this->info('Performances by Category:');
            $this->table(
                ['Category', 'Count'],
                collect($stats['categories'])->map(function ($count, $category) {
                    return [$category, $count];
                })->toArray()
            );
        }
    }

    private function cleanup()
    {
        if ($this->confirm('This will delete all inactive performances. Continue?')) {
            $deleted = Performance::where('status', false)->delete();
            $this->info("Deleted {$deleted} inactive performances.");
        }
    }
}
