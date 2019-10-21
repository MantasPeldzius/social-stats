<?php
namespace App\Models;

class MonthStats extends Stats
{
    public function fillData($post)
    {
        $month = date('Y-m', strtotime($post->created_time));
        if (!isset($this->data[$month])) {
            $this->data[$month] = [
                'posts_total_length' => 0,
                'posts_count' => 0,
                'post_max_length' => 0,
                'users' => [],
                'user_posts_count' => 0,
            ];
        }
        $length = mb_strlen($post->message);
        $this->data[$month]['posts_total_length'] += $length; 
        $this->data[$month]['posts_count']++;
        if ($this->data[$month]['post_max_length'] < $length) {
            $this->data[$month]['post_max_length'] = $length;
        }
        if (!isset($this->data[$month]['users'][$post->from_id])) {
            $this->data[$month]['users'][$post->from_id] = 1;
        }
        $this->data[$month]['user_posts_count']++;
    }

    public function calculateStats()
    {
        foreach ($this->data as $month => $data) {
            $stats[$month] = [
                'avg_posts_length_month' => round($data['posts_total_length'] / $data['posts_count'], 2),
                'max_posts_length_month' => $data['post_max_length'],
                'avg_posts_count_user_month' => round($data['user_posts_count'] / count($data['users']), 2),
            ];
        }
        ksort($stats);
        $this->stats = $stats;
    }

    public function getAveragePostLength()
    {
        $result = [];
        foreach ($this->stats as $month => $data) {
            $result[$month] = $data['avg_posts_length_month'];
        }
        return $result;
    }

    public function getMaxPostLength()
    {
        $result = [];
        foreach ($this->stats as $month => $data) {
            $result[$month] = $data['max_posts_length_month'];
        }
        return $result;
    }

    public function getAverageUserPostCount()
    {
        $result = [];
        foreach ($this->stats as $month => $data) {
            $result[$month] = $data['avg_posts_count_user_month'];
        }
        return $result;
    }
}

