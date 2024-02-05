<?php

declare(strict_types=1);

namespace Axleus\DebugBar\Collector;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use Mezzio\Middleware\LazyLoadingMiddleware;
use Mezzio\Router\Route;
use Mezzio\Router\RouteCollectorInterface;

use function get_class;

final class MezzioRouteCollector extends DataCollector implements Renderable, AssetProvider
{
    /** @var array<int, Route> $routes */
    private array $routes;

    protected $useHtmlVarDumper = false;

    public function __construct(
        RouteCollectorInterface $collector
    ) {
        $this->routes = $collector->getRoutes();
    }

    public function getName(): string
    {
        return 'routes';
    }

    public function getAssets() { }
    public function collect(): array
    {
        $data = [];
        foreach ($this->routes as $route) {
            $debugInfo = [
                'path' => $route->getPath(),
                'methods' => $route->getAllowedMethods(),
                'options' => $route->getOptions(),
                'name' => $route->getName(),
            ];
            $middleware = $route->getMiddleware();
            if ($middleware instanceof LazyLoadingMiddleware) {
                $debugInfo['middleware'] = $middleware->middlewareName;
            }
            if ($this->isHtmlVarDumperUsed()) {
                $data[$debugInfo['name']] = $this->getVarDumper()->renderVar($debugInfo);
            } else {
                $data[$debugInfo['name']] = $this->getDataFormatter()->formatVar($debugInfo);
            }
        }
        return $data;
    }

    public function getWidgets()
    {
        $name = $this->getName();
        $widget = $this->isHtmlVarDumperUsed()
            ? "PhpDebugBar.Widgets.HtmlVariableListWidget"
            : "PhpDebugBar.Widgets.VariableListWidget";
        return [
            'routes' => [
                "icon" => "gear",
                "widget" => "$widget",
                "map" => 'routes',
                "default" => "[]"
            ],
        ];
    }

    /**
     * Sets a flag indicating whether the Symfony HtmlDumper will be used to dump variables for
     * rich variable rendering.
     *
     * @param bool $value
     * @return $this
     */
    public function useHtmlVarDumper($value = true)
    {
        $this->useHtmlVarDumper = $value;
        return $this;
    }

    /**
     * Indicates whether the Symfony HtmlDumper will be used to dump variables for rich variable
     * rendering.
     *
     * @return mixed
     */
    public function isHtmlVarDumperUsed()
    {
        return $this->useHtmlVarDumper;
    }
}
