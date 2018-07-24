<?php

namespace JKocik\Laravel\Profiler\Services;

use Illuminate\Config\Repository;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Application;

class ConfigService
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * ConfigService constructor.
     * @param Application $app
     * @param Repository $config
     */
    public function __construct(Application $app, Repository $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function isProfilerEnabled(): bool
    {
        $forceDisableOn = Collection::make($this->config->get('profiler.force_disable_on'));
        $envToDisable = $forceDisableOn->filter(function ($disable) {
            return $disable;
        })->keys();

        if ($this->app->environment($envToDisable->toArray())) {
            return false;
        }

        return $this->config->get('profiler.enabled') === true;
    }

    /**
     * @return Collection
     */
    public function trackers(): Collection
    {
        return Collection::make($this->config->get('profiler.trackers'));
    }

    /**
     * @return Collection
     */
    public function processors(): Collection
    {
        return Collection::make($this->config->get('profiler.processors'));
    }

    /**
     * @return string
     */
    public function broadcastingEvent(): string
    {
        return $this->config->get('profiler.broadcasting_event');
    }

    /**
     * @return string
     */
    public function broadcastingUrl(): string
    {
        $address = $this->config->get('profiler.broadcasting_address');
        $port = $this->config->get('profiler.broadcasting_port');

        return  $address . ':' . $port;
    }

    /**
     * @return bool
     */
    public function broadcastingLogErrors(): bool
    {
        return $this->config->get('profiler.broadcasting_log_errors');
    }
}
