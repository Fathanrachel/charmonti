<?php

namespace App\Providers;

use App\Models\FinancialReport;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SalesReport;
use App\Observers\OrderObserver;
use App\Policies\FinancialReportPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SalesReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Product::class        => ProductPolicy::class,
        Order::class          => OrderPolicy::class,
        Payment::class        => PaymentPolicy::class,
        FinancialReport::class => FinancialReportPolicy::class,
        SalesReport::class    => SalesReportPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        Order::observe(OrderObserver::class);

        // Konfigurasi Global Format Tanggal di Tabel Filament (Admin & Owner)
        \Filament\Tables\Table::configureUsing(function (\Filament\Tables\Table $table): void {
            $table->defaultDateTimeDisplayFormat('d M Y');
            $table->defaultDateDisplayFormat('d M Y');
        });
    }
}
