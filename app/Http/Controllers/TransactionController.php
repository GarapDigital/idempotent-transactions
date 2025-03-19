<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\IdempotencyKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $idempotencyKey = $request->header('Idempotency-Key');

        if (!$idempotencyKey) {
            return response()->json(['message' => 'Idempotency Key is required'], 400);
        }

        DB::beginTransaction();

        try {
            // Cek apakah permintaan sudah pernah diproses
            $existingKey = IdempotencyKey::where('key', $idempotencyKey)->first();
            if ($existingKey) {
                return response()->json([
                    'message' => 'Transaction already processed',
                    'transaction_id' => $existingKey->transaction_id,
                ], 409);
            }

            // Simpan transaksi baru
            $transaction = Transaction::create([
                'transaction_id' => Str::uuid(),
                'user_id' => $user->id,
                'amount' => $request->amount,
                'status' => 'success',
            ]);

            // Simpan idempotency key
            IdempotencyKey::create([
                'key' => $idempotencyKey,
                'user_id' => $user->id,
                'transaction_id' => $transaction->transaction_id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Transaction successful',
                'transaction_id' => $transaction->transaction_id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed'], 500);
        }
    }

    public function history(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:' . date('Y'),
        ]);

        $query = Transaction::where('user_id', auth()->id());

        if (! empty($request->month)) {
            $query->whereMonth('created_at', $request->month);
        }

        if (! empty($request->year)) {
            $query->whereYear('created_at', $request->year);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }
}
