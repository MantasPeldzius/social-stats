<?php
namespace App\Controllers;

use App\Services\CurlConnector;

class App
{
    
    private $analyzer;
    private $config;
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    
    public function debug()
    {
        echo '<pre>';
    }
    
    public function run()
    {
        try {
            $this->prepare();
            $this->process();
            $this->end();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    
    private function prepare()
    {
        $this->initAnalyzer();
        $this->analyzer->setPages($this->config['pages']);
    }
    
    private function process()
    {
        $this->analyzer->analyze();
    }
    
    private function end()
    {
        echo json_encode($this->analyzer->getStats());
    }
    
    private function initAnalyzer()
    {
        $connector = new CurlConnector($this->config['api']);
        $connector->init();
        $this->analyzer = new Analyzer($connector);
    }
}

