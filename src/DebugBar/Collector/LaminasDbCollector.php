<?php

declare(strict_types=1);

namespace Axleus\DebugBar\Collector;

use BjyProfiler\Db\Adapter\ProfilingAdapter;
use BjyProfiler\Db\Profiler\Profiler;
use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use Laminas\Db\Adapter\Adapter;

final class LaminasDbCollector extends DataCollector implements Renderable, AssetProvider
{
    /** @var Profiler $profiler */
    private Profiler $profiler;

    /**
     *
     * @param AdapterInterface $adapter
     * @return void
     */
    public function __construct(
        private ProfilingAdapter $adapter,
    ) {
        $this->profiler = $adapter->getProfiler();
    }

    public function collect(): array
    {
        $queries  = [];
        $profiles = $this->profiler->getQueryProfiles();
        $duration = 0;
        foreach($profiles as $query) {
            $queries[] = [
                'sql'          => $query->getSql(),
                'params'       => $query->getParams(),
                'duration'     => $query->getElapsedTime(),
                'duration_str' => (string) $query->getElapsedTime()
            ];
            $duration += $query->getElapsedTime();
        }
        //return $queries;
        return [
            'nb_statements' => count($profiles),
            'accumulated_duration_str' => $duration,
            'statements' => $queries,
        ];
    }

    public function getName(): string
    {
        return 'laminas-db';
    }

    public function getWidgets(): array
    {
        return [
            'database' => [
                'icon' => 'arrow-right',
                'widget' => "PhpDebugBar.Widgets.SQLQueriesWidget",
                'map'    => 'laminas-db',
                'default' => '[]',
            ],
            'database:badge' => [
                'map' => 'laminas-db.nb_statements',
                'default' => 0,
            ],
        ];
    }

    public function getAssets(): array
    {
        return [
            'css' => 'widgets/sqlqueries/widget.css',
            'js'  => 'widgets/sqlqueries/widget.js',
        ];
    }
}
