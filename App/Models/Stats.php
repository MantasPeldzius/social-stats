<?php
namespace App\Models;

abstract class Stats
{
    protected $stats; 
    protected $data; 
    
    public abstract function fillData($post);
    
    public abstract function calculateStats();
}

