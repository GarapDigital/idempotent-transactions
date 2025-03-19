<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\IdempotencyKey;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIdempotency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('Idempotency-Key');

        if (!$key) {
            return response()->json(['message' => 'Idempotency Key is required'], Response::HTTP_BAD_REQUEST);
        }

        $idempotencyRecord = IdempotencyKey::where('key', $key)->first();

        if ($idempotencyRecord) {
            return response()->json([
                'message' => 'Duplicate request',
                'transaction_id' => $idempotencyRecord->transaction_id,
            ], Response::HTTP_CONFLICT);
        }

        return $next($request);
    }
}
