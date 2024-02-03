<?php

declare(strict_types=1);

namespace Axleus\DebugBar\Collector;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use Mezzio\Router\RouteCollectorInterface;

final class MezzioRouteCollector extends DataCollector implements Renderable, AssetProvider
{
    public function __construct(
        private RouteCollectorInterface $routeCollector
    ) {
    }

    public function getWidgets() { }

    public function getAssets() { }

}
