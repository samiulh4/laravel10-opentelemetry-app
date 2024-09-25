<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\Contrib\Zipkin\Exporter as ZipkinExporter;
use Zipkin\Endpoint;
use App\Models\Trace;
use Zipkin\Annotation;
use Zipkin\Samplers\BinarySampler;
use Zipkin\TracingBuilder;
use Zipkin\Reporters\Http;*/


/*use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Contrib\Zipkin\Exporter as ZipkinExporter;
use OpenTelemetry\Context\Context;
use OpenTelemetry\API\Trace\Span;
use GuzzleHttp\Client;
use OpenTelemetry\SDK\Common\Export\Http\PsrTransportFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenTelemetry\SDK\Common\Future\CompletedFuture;
use Psr\Log\NullLogger;*/

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use OpenTelemetry\API\Trace\Span;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Contrib\Zipkin\Exporter as ZipkinExporter;
use OpenTelemetry\SDK\Common\Export\Http\PsrTransportFactory;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\SpanProcessor\BatchSpanProcessor;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Common\Attribute\Attributes;


class OpenTelemetryController extends Controller
{
    protected TracerInterface $tracer;

    public function __construct(TracerInterface $tracer)
    {
        $this->tracer = $tracer;
    }
    public function zipkinIndex()
    {
//        $request = Request::capture();
//        $span = $this->tracer->spanBuilder('zipkin_index-'.date('YMd h:i:s A'))->startSpan();
//        $spanScope = $span->activate();
//        //$span->setAttribute('http.url', $request->url());
//        //$span->setAttribute('http.method', $request->method());


        try {
            return response()->json(['message' => 'Zipkin data capture successfully.']);
        } finally {
//            $span->end();
//            $spanScope->detach();
        }
    }
    /*public function index()
    {
        $httpClient = new Client();
        $httpFactory = new HttpFactory();
        $tracer = (new TracerProvider(
            [
                new SimpleSpanProcessor(
                    new ZipkinExporter(
                        PsrTransportFactory::discover()->create('http://localhost:9411/api/v2/spans', 'application/json')
                    ),
                ),
            ],
            new AlwaysOnSampler(),
        ))->getTracer('Hello World Laravel Web Server');
        $request = Request::capture();
        $span = $tracer->spanBuilder($request->url())->startSpan();
        $spanScope = $span->activate();
        $span->end();
        $spanScope->detach();
    }*/

    /*public function index3()
    {
        $endpoint = Endpoint::create('my_service');

        $reporter = new Http(['endpoint_url' => 'http://localhost:9411/api/v2/spans']);
        $sampler = BinarySampler::createAsAlwaysSample();
        $tracing = TracingBuilder::create()
            ->havingLocalEndpoint($endpoint)
            ->havingSampler($sampler)
            ->havingReporter($reporter)
            ->build();
        $tracer = $tracing->getTracer();
        $tracer->flush();
    }*/
    public function index2()
    {
        // Provide only the IP address, no port
        $endpoint = Endpoint::create('your-laravel-app', '127.0.0.1');

        // Zipkin Exporter with the full Zipkin collector URL (including the port)
        $zipkinExporter = new ZipkinExporter('http://localhost:9411/api/v2/spans', $endpoint);

        // Create the resource info for the tracer provider
        $resource = ResourceInfo::create(Attributes::create([
            'service.name' => 'your-laravel-app',
        ]));

        // Create Tracer Provider with the Zipkin Exporter
        $tracerProvider = new TracerProvider($zipkinExporter);
        $tracer = $tracerProvider->getTracer('app_tracer');

        // Start a span
        $span = $tracer->spanBuilder('request')->startSpan();
        $span->setAttribute('http.method', request()->method());
        $span->setAttribute('http.url', request()->url());

        // Log trace data to the database (optional)
        Trace::create([
            'trace_id' => $span->getContext()->getTraceId(),
            'span_id' => $span->getContext()->getSpanId(),
            'operation_name' => $span->getName(),
            'attributes' => json_encode($span->getAttributes()),
        ]);

        // Finish the span
        $span->end();
    }
}
