<?php
namespace App\Controllers;

use App\Services\CurlConnector;
use App\Models\MonthStats;
use App\Models\WeekStats;

class Analyzer
{
    private $connector;
    private $pages;
    private $month_stats;
    private $week_stats;
    
    public function __construct(CurlConnector $connector)
    {
        $this->connector = $connector;
        $this->month_stats = new MonthStats();
        $this->week_stats = new WeekStats();
    }
    
    /**
     * Set how many pages of post will be analyzed
     * 
     * @param int $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }
    
    public function analyze()
    {
        $this->fetchAllPosts();
    }
    
    public function getStats()
    {
        $this->month_stats->calculateStats();
        $this->week_stats->calculateStats();
        $result = [
            'avg_posts_length_month' => $this->month_stats->getAveragePostLength(),
            'max_posts_length_month' => $this->month_stats->getMaxPostLength(),
            'count_posts_week' => $this->week_stats->getPostCount(),
            'avg_posts_count_user_month' => $this->month_stats->getAverageUserPostCount(),
        ];
        return $result;
    }
    
    private function fetchAllPosts()
    {
        for ($i = 0; $i < $this->pages; $i++) {
            $posts = $this->connector->fetchPosts($i);
            if ($posts !== false) {
                foreach ($posts as $post) {
                    $this->month_stats->fillData($post);
                    $this->week_stats->fillData($post);
                }
            } else {
                throw new \Exception('Couldn\'t get Posts');
            }
        }
    }
}

