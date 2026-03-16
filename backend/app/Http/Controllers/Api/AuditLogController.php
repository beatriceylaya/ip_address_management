<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditResource;
use App\Models\Audit;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Audit::query();

        $query->with(['causer', 'subject']);

        $query->orderByDesc('id');

        return AuditResource::collection($query->paginate());
    }
}
