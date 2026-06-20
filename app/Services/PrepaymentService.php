<?php

namespace App\Services;

/**
 * Encapsulates the platform's signature business rule:
 *
 *   - Orders with a total <= threshold  => full Cash On Delivery allowed.
 *   - Orders with a total >  threshold  => a mandatory advance (a percent of
 *     the total) is required to confirm; the remainder is collected as COD.
 *
 * Returns an immutable breakdown used consistently across the product page,
 * cart, checkout, order summary, WhatsApp message and admin panel.
 */
class PrepaymentService
{
    public function __construct(private readonly SettingService $settings)
    {
    }

    public function threshold(): float
    {
        return (float) $this->settings->get('prepayment_threshold', 500);
    }

    public function percent(): float
    {
        return (float) $this->settings->get('prepayment_percent', 50);
    }

    public function requiresPrepayment(float $total): bool
    {
        return $total > $this->threshold();
    }

    /**
     * Compute the full payment breakdown for a given order total.
     *
     * @return array{
     *   total: float,
     *   threshold: float,
     *   percent: float,
     *   requires_prepayment: bool,
     *   advance_amount: float,
     *   cod_balance: float,
     *   payment_method: string,
     *   notice: string
     * }
     */
    public function breakdown(float $total): array
    {
        $threshold = $this->threshold();
        $percent   = $this->percent();
        $requires  = $this->requiresPrepayment($total);

        $advance = $requires ? round($total * ($percent / 100), 2) : 0.0;
        $balance = round($total - $advance, 2);

        return [
            'total'               => round($total, 2),
            'threshold'           => $threshold,
            'percent'             => $percent,
            'requires_prepayment' => $requires,
            'advance_amount'      => $advance,
            'cod_balance'         => $balance,
            'payment_method'      => $requires ? 'prepayment' : 'cod',
            'notice'              => $this->notice(),
        ];
    }

    public function notice(): string
    {
        $percent = rtrim(rtrim(number_format($this->percent(), 2), '0'), '.');

        return sprintf(
            'Orders above %s require %s%% advance payment to confirm your order.',
            money($this->threshold(), false),
            $percent,
        );
    }
}
