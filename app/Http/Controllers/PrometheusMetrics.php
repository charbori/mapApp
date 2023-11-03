<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Prometheus\CollectorRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Service\SportRecordService;
use Illuminate\Support\Facades\Redis;

class PrometheusMetrics extends Controller
{
    public function registerMetrics(Request $request) {
        debugbar()->disable();
        $datas = array( "URI_LOG" => null,
                        "ERR_LOG" => null);

        $registry = new CollectorRegistry(new InMemory());
        
        $gauge_memory_usage = $registry->registerGauge('memory', 'usage_gauge', 'it sets', ['type']);
        $gauge_memory_usage->set(memory_get_usage() / 1000000, ['blue']);

        $gauge_cpu_usage = $registry->registerGauge('cpu', 'usage_gauge', 'it sets', ['type']);
        $gauge_cpu_usage->incBy(sys_getloadavg()[0] / 1000000, ['blue']);

        $redis_client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '172.27.0.6',
            'port'   => 6379,
        ]);

        $datas['URI_LOG'] = $redis_client->lrange('URI_LOG', 0, -1);
        $redis_client->del('URI_LOG');
        $http_counter = $registry->registerCounter('http', 'request_statcd', 'it sets', ['url', 'status']);
        for ($i = 0; $i < count($datas['URI_LOG']); $i++) {
            $http_request_data = unserialize($datas['URI_LOG'][$i]);
            if ($http_request_data['url'] == 'metrics') continue;
            $http_counter->incBy(1, [$http_request_data['url'], $http_request_data['status']]);
        }
        Log::debug(print_r($datas, true));

        $datas['ERR_LOG'] = $redis_client->lrange('ERR_LOG', 0, -1);
        $redis_client->del('ERR_LOG');
        $err_usage = $registry->registerGauge('err', 'gauge', 'it sets', ['type']);
        $err_usage->set(count($datas['ERR_LOG']), ['blue']);

        $renderer = new RenderTextFormat();
        $result = $renderer->render($registry->getMetricFamilySamples());

        /*
        $counter = $registry->getOrRegisterCounter('test', 'some_counter', 'it increases', ['type']);
        $counter->incBy(3, ['blue']);

        $gauge = $registry->getOrRegisterGauge('test', 'some_gauge', 'it sets', ['type']);
        $gauge->set(2.5, ['blue']);

        $histogram = $registry->getOrRegisterHistogram('test', 'some_histogram', 'it observes', ['type'], [0.1, 1, 2, 3.5, 4, 5, 6, 7, 8, 9]);
        $histogram->observe(3.5, ['blue']);

        $summary = $registry->getOrRegisterSummary('test', 'some_summary', 'it observes a sliding window', ['type'], 84600, [0.01, 0.05, 0.5, 0.95, 0.99]);
        $summary->observe(5, ['blue']);

        '처리시간(ms): ' . (microtime(true) - LARAVEL_START),
        '메모리(MB) : ' . memory_get_usage() / 1000000,
        'CPU(%): ' . sys_getloadavg()[0],
        */
        
        header('Content-type: ' . RenderTextFormat::MIME_TYPE);
        echo $result;
    }
}