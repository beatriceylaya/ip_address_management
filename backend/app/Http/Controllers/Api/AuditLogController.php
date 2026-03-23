<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditResource;
use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuditLogController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $audits = Audit::with(['causer', 'subject'])
            ->filter($request)
            ->latest()
            ->paginate($request->input('per_page', 15));

        return AuditResource::collection($audits);
    }

    public function getSessionOptions(Request $request): JsonResponse
    {
        $sessions = Audit::query()
            ->select('session_id')
            ->whereNotNull('session_id')
            ->filterSessionOptions($request)
            ->distinct()
            ->orderBy('session_id')
            ->paginate($request->input('per_page', 20));

        return response()->json($sessions);
    }
}
