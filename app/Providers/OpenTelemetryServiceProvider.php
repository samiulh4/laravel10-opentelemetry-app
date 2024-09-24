<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Contrib\Zipkin\Exporter as ZipkinExporter;
use OpenTelemetry\SDK\Common\Export\Http\PsrTransportFactory;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;



class OpenTelemetryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TracerInterface::class, function () {

            //            $resource = ResourceInfo::create(
//                Attributes::create([
//                    'service.name' => env('OTEL_SERVICE_NAME', 'Laravel-OpenTelemetry')
//                ])
//            );
            $resource = ResourceInfoFactory::defaultResource()->merge(
                ResourceInfo::create(Attributes::create([
                    'service.name' => env('OTEL_SERVICE_NAME', 'Laravel-OpenTelemetry'),
                ]))
            );

            return (new TracerProvider(
                [
                    new SimpleSpanProcessor(
                        new ZipkinExporter(
                            PsrTransportFactory::discover()->create('http://localhost:9411/api/v2/spans', 'application/json')
                        )
                    ),
                ],
                new AlwaysOnSampler(),
                $resource
            ))->getTracer('Hello World Laravel Web Server');
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
