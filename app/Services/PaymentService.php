<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Handles advance/balance payment submissions by customers and their
 * verification by admins. Verification updates the order's amount_paid
 * and advances the order status accordingly.
 */
class PaymentService
{
    public function __construct(
        private readonly ActivityLogger $logger,
        private readonly AdminNotifier $adminNotifier,
    ) {
    }

    /**
     * Customer submits proof of an advance (or balance) payment.
     */
    public function submit(Order $order, array $data, ?UploadedFile $proof = null): Payment
    {
        $path = $proof?->store('payments', 'public');

        $payment = $order->payments()->create([
            'kind'      => $data['kind'] ?? 'advance',
            'amount'    => $data['amount'] ?? $order->advance_amount,
            'method'    => $data['method'] ?? 'bank_transfer',
            'reference' => $data['reference'] ?? null,
            'proof_path' => $path,
            'status'    => 'submitted',
            'note'      => $data['note'] ?? null,
        ]);

        // Reflect that the customer has acted, so it surfaces in the
        // admin verification queue.
        if ($order->status === 'pending_payment') {
            $order->update(['status' => 'payment_submitted']);
            $order->statusHistory()->create([
                'from_status' => 'pending_payment',
                'to_status'   => 'payment_submitted',
                'note'        => 'Customer submitted payment proof.',
                'changed_by'  => $order->user_id,
            ]);
        }

        // Alert staff there is a payment to verify.
        $this->adminNotifier->notify(
            'payments.manage',
            'Payment to verify',
            money($payment->amount)." submitted for order {$order->order_number} — please verify.",
            'bi-cash-coin',
            route('admin.orders.show', $order->order_number),
        );

        return $payment;
    }

    /**
     * Admin verifies a payment: bump amount_paid and move the order forward.
     */
    public function verify(Payment $payment, ?string $adminNote = null): Payment
    {
        return DB::transaction(function () use ($payment, $adminNote) {
            $payment->update([
                'status'      => 'verified',
                'admin_note'  => $adminNote,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $order = $payment->order;
            $paid  = (float) $order->payments()->where('status', 'verified')->sum('amount');
            $order->amount_paid = $paid;

            // Determine the resulting status from how much is now paid.
            $from = $order->status;
            if ($paid >= (float) $order->grand_total) {
                $order->status = 'confirmed';
                $order->confirmed_at ??= now();
            } elseif ($paid >= (float) $order->advance_amount && $order->advance_amount > 0) {
                $order->status = $order->status === 'confirmed' ? 'confirmed' : 'partially_paid';
            }
            $order->save();

            if ($from !== $order->status) {
                $order->statusHistory()->create([
                    'from_status' => $from,
                    'to_status'   => $order->status,
                    'note'        => 'Payment verified by admin.',
                    'changed_by'  => Auth::id(),
                ]);
            }

            $this->logger->log('payment.verified', "Verified {$payment->amount} for order {$order->order_number}", $order);

            // Notify the customer their payment was accepted.
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderStatusNotification(
                    $order,
                    'Payment verified',
                    'We verified your payment of '.money($payment->amount)." for order {$order->order_number}.",
                    'bi-cash-coin',
                ));
            }

            return $payment->fresh();
        });
    }

    public function reject(Payment $payment, ?string $adminNote = null): Payment
    {
        $payment->update([
            'status'      => 'rejected',
            'admin_note'  => $adminNote,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $order = $payment->order;
        if ($order->status === 'payment_submitted') {
            $order->update(['status' => 'pending_payment']);
        }

        $this->logger->log('payment.rejected', "Rejected payment for order {$order->order_number}", $order);

        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusNotification(
                $order,
                'Payment needs attention',
                "Your payment for order {$order->order_number} could not be verified. Please re-submit.",
                'bi-exclamation-circle',
            ));
        }

        return $payment->fresh();
    }
}
