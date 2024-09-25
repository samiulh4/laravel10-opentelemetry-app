<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\API\Trace\Span;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\Context\Context;

class WebController extends Controller
{
    protected $tracer;

    public function __construct(TracerInterface $tracer)
    {
        $this->tracer = $tracer;
    }
    public function index()
    {
        return view('pages.web.index');
    }
    public function webUserList()
    {
        // Get the current span from the context
        $parentSpan = Span::fromContext(Context::getCurrent());

        // Create a child span
        $childSpan = $this->tracer->spanBuilder('webUserList_processing')
            ->setParent($parentSpan->storeInContext(Context::getCurrent())) // Set parent span
            ->setSpanKind(SpanKind::KIND_INTERNAL)
            ->startSpan();

        try {
            // Your logic for retrieving users
            $users = DB::table('users')->get();

            // Optionally, add events or attributes to the child span
            $childSpan->addEvent('users.fetched', [
                'user.count' => $users->count(),
            ]);

            return view('pages.web.user-list', compact('users'));
        } finally {
            // End the child span
            $childSpan->end();
        }
    }
}
