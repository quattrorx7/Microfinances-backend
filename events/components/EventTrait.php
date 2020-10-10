<?php

namespace app\events\components;

trait EventTrait
{

    private array $events = [];

    protected function registerEvent($event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

}