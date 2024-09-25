<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Listen to all events in the application
        /*Event::listen('*', function (string $eventName, array $eventData) {
            // You can add logic here to process the events
            $this->traceEvent($eventName, $eventData);
        });*/
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    protected function traceEvent(string $eventName, array $eventData)
    {
        // Create a new span for each event dynamically
        $tracer = app(\OpenTelemetry\API\Trace\TracerInterface::class);
        $span = $tracer->spanBuilder($eventName)->startSpan();

        // Set attributes related to the event
        $span->setAttribute('event.name', $eventName);
        $span->setAttribute('event.data', json_encode($eventData));

        // Optionally, set more dynamic attributes based on event type
        if (!empty($eventData) && is_array($eventData)) {
            foreach ($eventData as $key => $value) {
                $span->setAttribute("event.data.{$key}", json_encode($value));
            }
        }

        $span->end(); // End the span after event handling
    }
}
