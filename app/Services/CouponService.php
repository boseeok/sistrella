<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Resolve a coupon by code if it is currently usable for the given
     * subtotal and (optionally) user. Returns null when not applicable.
     */
    public function resolve(?string $code, float $subtotal, ?int $userId = null): ?Coupon
    {
        if (! $code) {
            return null;
        }

        $coupon = Coupon::whereRaw('LOWER(code) = ?', [strtolower(trim($code))])->first();

        if (! $coupon || ! $coupon->isValid($subtotal)) {
            return null;
        }

        if ($userId && $coupon->usage_limit_per_user) {
            $perUser = DB::table('coupon_user')
                ->where('coupon_id', $coupon->id)
                ->where('user_id', $userId)
                ->count();

            if ($perUser >= $coupon->usage_limit_per_user) {
                return null;
            }
        }

        return $coupon;
    }

    /**
     * Validate a code and return a structured result for AJAX endpoints.
     *
     * @return array{ok: bool, message: string, discount: float, coupon: ?Coupon}
     */
    public function validate(string $code, float $subtotal, ?int $userId = null): array
    {
        $coupon = $this->resolve($code, $subtotal, $userId);

        if (! $coupon) {
            return ['ok' => false, 'message' => 'This coupon is invalid or has expired.', 'discount' => 0, 'coupon' => null];
        }

        return [
            'ok'       => true,
            'message'  => "Coupon \"{$coupon->code}\" applied.",
            'discount' => $coupon->discountFor($subtotal),
            'coupon'   => $coupon,
        ];
    }
}
