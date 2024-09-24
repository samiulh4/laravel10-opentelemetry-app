<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\API\Trace\SpanKind;

class OpenTelemetryTraceMiddleware
{
    protected $tracer;
    public function __construct(TracerInterface $tracer)
    {
        $this->tracer = $tracer;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $span = $this->tracer->spanBuilder($request->fullUrl().'-'.date('YMd h:i:s A'))
            ->setSpanKind(SpanKind::KIND_SERVER)
            ->startSpan();

        // Set attributes from the request
//        $span->setAttribute('http.method', $request->method());
       $span->setAttribute('http.url', $request->fullUrl());
//        $span->setAttribute('http.host', $request->getHost());
//        $span->setAttribute('http.scheme', $request->getScheme());
       $span->setAttribute('http.user_agent', $request->header('User-Agent'));
//
//        // Set additional system and process attributes
//        $span->setAttribute('host.arch', php_uname('m')); // Architecture
//        $span->setAttribute('host.name', php_uname('n')); // Hostname
//        $span->setAttribute('os.name', php_uname('s')); // OS name
//        $span->setAttribute('os.version', php_uname('v')); // OS version
//        $span->setAttribute('os.description', php_uname('r')); // OS description
//        $span->setAttribute('os.type', php_uname('s')); // OS type
//        $span->setAttribute('process.executable.path', PHP_BINARY); // Executable path
//        $span->setAttribute('process.pid', getmypid()); // Process ID
//        $span->setAttribute('process.runtime.name', 'php'); // Runtime name
//        $span->setAttribute('process.runtime.version', phpversion()); // Runtime version
          $span->setAttribute('context.trace_id', $span->getContext()->getTraceId());

        try {
            $response = $next($request);

            // Set additional attributes from the response
//            $span->setAttribute('http.status_code', $response->status());
//            $span->setAttribute('http.response_content_length', $response->headers->get('Content-Length'));

            return $response;
        } finally {
            $span->end();
        }
    }
}
