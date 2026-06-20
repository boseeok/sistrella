<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Banner;
use App\Models\CustomRequest;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Services\OrderService;
use App\Services\PrepaymentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeds demo customers, coupons, banners, orders (covering the full prepayment
 * + COD scenarios), custom requests, reviews and marketing records so the
 * storefront and admin panel are populated for evaluation.
 */
class DemoDataSeeder extends Seeder
{
    public function __construct(private readonly PrepaymentService $prepayment)
    {
    }

    public function run(): void
    {
        $customers = $this->customers();
        $this->coupons();
        $this->banners();
        $this->orders($customers);
        $this->customRequests($customers);
        $this->reviews($customers);
        $this->marketing();
    }

    /** @return \Illuminate\Support\Collection<int,User> */
    private function customers()
    {
        $names = [
            'Aarati Sharma'   => 'aarati@example.com',
            'Bishal Thapa'    => 'bishal@example.com',
            'Sushmita Gurung' => 'sushmita@example.com',
            'Niraj Karki'     => 'niraj@example.com',
            'Pratima Rai'     => 'pratima@example.com',
        ];

        $customers = collect();

        $i = 1;
        foreach ($names as $name => $email) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => $name,
                    'password'          => Hash::make('password'),
                    'phone'             => '98000000'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ],
            );
            $user->syncRoles(['customer']);

            $user->addresses()->firstOrCreate(
                ['line1' => 'House '.($i * 7).', Ward '.$i],
                [
                    'label'      => 'Home',
                    'full_name'  => $name,
                    'phone'      => $user->phone,
                    'city'       => 'Kathmandu',
                    'district'   => 'Kathmandu',
                    'province'   => 'Bagmati',
                    'postal_code' => '4460'.$i,
                    'country'    => 'Nepal',
                    'is_default' => true,
                ],
            );

            $customers->push($user);
            $i++;
        }

        return $customers;
    }

    private function coupons(): void
    {
        Coupon::updateOrCreate(['code' => 'WELCOME10'], [
            'description'          => '10% off your first order',
            'type'                 => 'percent',
            'value'                => 10,
            'min_order_amount'     => 500,
            'max_discount_amount'  => 500,
            'usage_limit_per_user' => 1,
            'is_active'            => true,
            'expires_at'           => now()->addMonths(3),
        ]);

        Coupon::updateOrCreate(['code' => 'FLAT200'], [
            'description'      => 'NPR 200 off orders above NPR 2000',
            'type'             => 'fixed',
            'value'            => 200,
            'min_order_amount' => 2000,
            'is_active'        => true,
            'expires_at'       => now()->addMonth(),
        ]);

        Coupon::updateOrCreate(['code' => 'FESTIVE15'], [
            'description'         => 'Festive 15% off (capped)',
            'type'                => 'percent',
            'value'               => 15,
            'max_discount_amount' => 1000,
            'is_active'           => true,
            'expires_at'          => now()->addWeeks(2),
        ]);
    }

    private function banners(): void
    {
        Banner::updateOrCreate(['title' => 'Handmade Crochet, Made with Love'], [
            'subtitle'    => 'Discover unique amigurumi, wearables & home decor',
            'image'       => '',
            'link'        => '/shop',
            'button_text' => 'Shop Now',
            'position'    => 'hero',
            'is_active'   => true,
            'sort_order'  => 0,
        ]);

        Banner::updateOrCreate(['title' => 'Flash Sale Live Now'], [
            'subtitle'    => 'Up to 20% off selected handmade pieces',
            'image'       => '',
            'link'        => '/shop?sort=popular',
            'button_text' => 'Grab the Deal',
            'position'    => 'hero',
            'is_active'   => true,
            'sort_order'  => 1,
        ]);

        foreach (['Free WhatsApp Support', 'Custom Orders Welcome', 'Cash on Delivery Available'] as $n => $title) {
            Banner::updateOrCreate(['title' => $title], [
                'subtitle'   => 'Tap to learn more',
                'image'      => '',
                'link'       => $n === 1 ? '/custom-order' : '/about',
                'position'   => 'promo',
                'is_active'  => true,
                'sort_order' => $n,
            ]);
        }
    }

    /**
     * Build a spread of orders directly (without the cart) so we can control
     * the prepayment scenarios and statuses shown in the admin panel.
     */
    private function orders($customers): void
    {
        if (Order::count() > 0) {
            return; // don't duplicate orders on re-seed
        }

        $products = Product::inRandomOrder()->limit(20)->get();
        if ($products->isEmpty()) {
            return;
        }

        $orderSvc = app(OrderService::class);

        $statuses = [
            'pending_payment', 'payment_submitted', 'partially_paid',
            'confirmed', 'processing', 'shipped', 'delivered', 'cancelled',
        ];

        for ($n = 1; $n <= 16; $n++) {
            $customer = $customers->random();
            $picks    = $products->random(rand(1, 3));

            $subtotal = 0;
            $lines    = [];
            foreach ($picks as $p) {
                $qty   = rand(1, 3);
                $price = $p->current_price;
                $subtotal += $price * $qty;
                $lines[] = [
                    'product_id' => $p->id,
                    'name'       => $p->name,
                    'sku'        => $p->sku,
                    'unit_price' => $price,
                    'quantity'   => $qty,
                    'line_total' => $price * $qty,
                ];
            }

            $shipping = 100.0;
            $grand    = $subtotal + $shipping;
            $pp       = $this->prepayment->breakdown($grand);
            $status   = $statuses[($n - 1) % count($statuses)];

            // amount_paid depends on status
            $paid = match ($status) {
                'pending_payment', 'payment_submitted', 'cancelled' => 0.0,
                'partially_paid' => $pp['advance_amount'],
                default          => $pp['requires_prepayment'] ? $pp['advance_amount'] : 0.0,
            };
            if (in_array($status, ['delivered'], true)) {
                $paid = $grand; // fully collected on delivery
            }

            $order = Order::create([
                'order_number'         => $orderSvc->generateOrderNumber(),
                'user_id'              => $customer->id,
                'customer_name'        => $customer->name,
                'customer_email'       => $customer->email,
                'customer_phone'       => $customer->phone,
                'shipping_address'     => $customer->defaultAddress?->toSnapshot(),
                'subtotal'             => $subtotal,
                'shipping_total'       => $shipping,
                'grand_total'          => $grand,
                'requires_prepayment'  => $pp['requires_prepayment'],
                'prepayment_threshold' => $pp['threshold'],
                'prepayment_percent'   => $pp['percent'],
                'advance_amount'       => $pp['advance_amount'],
                'cod_balance'          => $pp['cod_balance'],
                'amount_paid'          => $paid,
                'payment_method'       => $pp['payment_method'],
                'status'               => $status,
                'confirmed_at'         => in_array($status, ['confirmed', 'processing', 'shipped', 'delivered'], true) ? now()->subDays(rand(1, 10)) : null,
                'shipped_at'           => in_array($status, ['shipped', 'delivered'], true) ? now()->subDays(rand(1, 5)) : null,
                'delivered_at'         => $status === 'delivered' ? now()->subDays(rand(0, 3)) : null,
                'cancelled_at'         => $status === 'cancelled' ? now()->subDays(rand(0, 3)) : null,
                'created_at'           => now()->subDays(rand(0, 25)),
            ]);

            foreach ($lines as $line) {
                $order->items()->create($line);
            }

            $order->statusHistory()->create([
                'to_status' => $status,
                'note'      => 'Seeded demo order.',
            ]);

            // A verified advance payment for paid orders.
            if ($paid > 0) {
                $order->payments()->create([
                    'kind'        => $paid >= $grand ? 'full' : 'advance',
                    'amount'      => $paid,
                    'method'      => 'bank_transfer',
                    'reference'   => 'TXN'.strtoupper(Str::random(8)),
                    'status'      => 'verified',
                    'verified_at' => now(),
                ]);
            }

            // A pending submission for the verification queue.
            if ($status === 'payment_submitted') {
                $order->payments()->create([
                    'kind'      => 'advance',
                    'amount'    => $pp['advance_amount'],
                    'method'    => 'esewa',
                    'reference' => 'ESW'.strtoupper(Str::random(8)),
                    'status'    => 'submitted',
                    'note'      => 'Paid the advance via eSewa, please verify.',
                ]);
            }
        }
    }

    private function customRequests($customers): void
    {
        if (CustomRequest::count() > 0) {
            return;
        }

        $samples = [
            ['Custom Elephant Amigurumi', 'pending', null],
            ['Personalised Name Bunting', 'under_review', null],
            ['Wedding Bouquet (Roses & Lilies)', 'quoted', 3500],
            ['Matching Baby Set (Hat + Booties)', 'in_production', 1800],
            ['Graduation Bear with Gown', 'delivered', 1200],
        ];

        $year = now()->year;
        $seq  = 1;
        foreach ($samples as [$title, $status, $price]) {
            $customer = $customers->random();
            CustomRequest::create([
                'request_number' => "CCR-{$year}-".str_pad((string) $seq++, 4, '0', STR_PAD_LEFT),
                'user_id'        => $customer->id,
                'customer_name'  => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'title'          => $title,
                'notes'          => 'Please make it in soft pastel colours if possible.',
                'color'          => collect(['Pastel Pink', 'Cream', 'Sky Blue', 'Mint'])->random(),
                'size'           => collect(['Small', 'Medium', 'Large'])->random(),
                'quantity'       => 1,
               'preferred_delivery_date' => ['required', 'date', 'after_or_equal:today'],
                'quoted_price'   => $price,
                'quoted_at'      => $price ? now()->subDays(rand(1, 5)) : null,
                'quote_note'     => $price ? 'Includes premium yarn and gift packaging.' : null,
                'status'         => $status,
            ]);
        }
    }

    private function reviews($customers): void
    {
        if (Review::count() > 0) {
            return;
        }

        $comments = [
            ['Absolutely adorable!', 'Even cuter in person. The craftsmanship is amazing.'],
            ['Loved it', 'Soft, well-made and arrived quickly. Highly recommend.'],
            ['Great quality', 'Very happy with my purchase, lovely neat stitching.'],
            ['Perfect gift', 'Bought it for my niece and she adores it.'],
            ['Beautiful work', 'Gorgeous colours, exactly as pictured. Thank you!'],
        ];

        Product::inRandomOrder()->limit(12)->get()->each(function (Product $p) use ($customers, $comments) {
            foreach ($customers->random(rand(1, 3)) as $customer) {
                [$title, $body] = $comments[array_rand($comments)];
                Review::updateOrCreate(
                    ['product_id' => $p->id, 'user_id' => $customer->id],
                    [
                        'rating'               => collect([5, 5, 4, 5, 3])->random(),
                        'title'                => $title,
                        'body'                 => $body,
                        'is_approved'          => true,
                        'is_verified_purchase' => (bool) rand(0, 1),
                    ],
                );
            }
        });
    }

    private function marketing(): void
    {
        foreach (['fan1@example.com', 'fan2@example.com', 'fan3@example.com'] as $email) {
            NewsletterSubscriber::updateOrCreate(
                ['email' => $email],
                ['is_active' => true, 'subscribed_at' => now()->subDays(rand(1, 30))],
            );
        }

        ContactMessage::firstOrCreate(
            ['email' => 'curious@example.com'],
            [
                'name'    => 'Curious Customer',
                'phone'   => '9812345678',
                'subject' => 'Do you ship outside Kathmandu?',
                'message' => 'Hi! I love your work. Do you deliver to Pokhara, and how long does it take?',
                'is_read' => false,
            ],
        );
    }
}
