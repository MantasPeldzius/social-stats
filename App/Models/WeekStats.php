<?php
namespace App\Models;

class WeekStats extends Stats
{
    public function fillData($post)
    {
        $week = date('Y-W', strtotime($post->created_time));
        if (!isset($this->data[$week])) {
            $this->data[$week] = [
                'posts_count' => 0,
            ];
        }
        $this->data[$week]['posts_count']++;
    }
    
    public function calculateStats()
    {
        foreach ($this->data as $week => $data) {
            $stats[$week] = [
                'count_posts_week' => $data['posts_count'],
            ];
        }
        ksort($stats);
        $this->stats = $stats;
    }
    
    public function getPostCount()
    {
        $result = [];
        foreach ($this->stats as $week => $data) {
            $result[$week] = $data['count_posts_week'];
        }
        return $result;
    }
}

