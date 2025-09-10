<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Analytics;
use App\Models\PersonalInfo;
use App\Models\User;
use Carbon\Carbon;

class AnalyticsSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users and portfolios
        $users = User::all();
        $portfolios = PersonalInfo::where('status', 'approved')->get();

        if ($users->isEmpty() || $portfolios->isEmpty()) {
            $this->command->info('No users or portfolios found. Please create some first.');
            return;
        }

        $this->command->info('Creating sample analytics data...');

        // Generate page views for the last 30 days
        $this->generatePageViews($users, $portfolios);

        // Generate portfolio views
        $this->generatePortfolioViews($users, $portfolios);

        // Generate downloads
        $this->generateDownloads($users, $portfolios);

        // Generate search queries
        $this->generateSearchQueries($users);

        $this->command->info('Sample analytics data created successfully!');
    }

    private function generatePageViews($users, $portfolios)
    {
        $pages = ['homepage', 'portfolios', 'faculty', 'search', 'about'];
        
        for ($i = 0; $i < 200; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
            
            Analytics::create([
                'event_type' => 'page_view',
                'event_name' => $pages[array_rand($pages)],
                'event_data' => [],
                'user_agent' => $this->getRandomUserAgent(),
                'ip_address' => $this->getRandomIP(),
                'session_id' => 'session_' . rand(1000, 9999),
                'user_id' => $users->random()->id,
                'page_url' => 'http://127.0.0.1:8000/' . $pages[array_rand($pages)],
                'referrer' => $this->getRandomReferrer(),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function generatePortfolioViews($users, $portfolios)
    {
        for ($i = 0; $i < 150; $i++) {
            $portfolio = $portfolios->random();
            $date = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
            
            Analytics::create([
                'event_type' => 'portfolio_view',
                'event_name' => 'portfolio_viewed',
                'event_data' => ['portfolio_id' => $portfolio->id],
                'user_agent' => $this->getRandomUserAgent(),
                'ip_address' => $this->getRandomIP(),
                'session_id' => 'session_' . rand(1000, 9999),
                'user_id' => $users->random()->id,
                'portfolio_id' => $portfolio->id,
                'page_url' => 'http://127.0.0.1:8000/personal-info/' . $portfolio->id,
                'referrer' => $this->getRandomReferrer(),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function generateDownloads($users, $portfolios)
    {
        for ($i = 0; $i < 50; $i++) {
            $portfolio = $portfolios->random();
            $date = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
            
            Analytics::create([
                'event_type' => 'download',
                'event_name' => 'portfolio_downloaded',
                'event_data' => ['portfolio_id' => $portfolio->id],
                'user_agent' => $this->getRandomUserAgent(),
                'ip_address' => $this->getRandomIP(),
                'session_id' => 'session_' . rand(1000, 9999),
                'user_id' => $users->random()->id,
                'portfolio_id' => $portfolio->id,
                'page_url' => 'http://127.0.0.1:8000/personal-info/' . $portfolio->id . '/download',
                'referrer' => 'http://127.0.0.1:8000/personal-info/' . $portfolio->id,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function generateSearchQueries($users)
    {
        $searchQueries = [
            'web development', 'mobile app', 'UI/UX design', 'database', 'API',
            'React', 'Vue.js', 'Laravel', 'PHP', 'JavaScript', 'Python',
            'machine learning', 'data science', 'blockchain', 'IoT',
            'portfolio', 'project', 'student', 'graduation', 'internship'
        ];
        
        for ($i = 0; $i < 80; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
            
            Analytics::create([
                'event_type' => 'search',
                'event_name' => 'search_performed',
                'event_data' => [
                    'search_query' => $searchQueries[array_rand($searchQueries)],
                    'results_count' => rand(0, 20)
                ],
                'user_agent' => $this->getRandomUserAgent(),
                'ip_address' => $this->getRandomIP(),
                'session_id' => 'session_' . rand(1000, 9999),
                'user_id' => $users->random()->id,
                'page_url' => 'http://127.0.0.1:8000/search?q=' . urlencode($searchQueries[array_rand($searchQueries)]),
                'referrer' => $this->getRandomReferrer(),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function getRandomUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Android 11; Mobile; rv:89.0) Gecko/89.0 Firefox/89.0',
        ];
        
        return $userAgents[array_rand($userAgents)];
    }

    private function getRandomIP()
    {
        $ips = [
            '192.168.1.' . rand(1, 254),
            '10.0.0.' . rand(1, 254),
            '172.16.' . rand(0, 31) . '.' . rand(1, 254),
            '203.0.113.' . rand(1, 254),
            '198.51.100.' . rand(1, 254),
        ];
        
        return $ips[array_rand($ips)];
    }

    private function getRandomReferrer()
    {
        $referrers = [
            'https://www.google.com/',
            'https://www.bing.com/',
            'https://www.facebook.com/',
            'https://www.twitter.com/',
            'https://www.linkedin.com/',
            'https://www.github.com/',
            'https://stackoverflow.com/',
            null,
        ];
        
        return $referrers[array_rand($referrers)];
    }
}
