<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\Invoice;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today()->format('Y-m-d');

        $todayRevenue = (float) Invoice::whereDate('created_at', $today)->where('status', 'issued')->sum('total_amount');
        $todayCars = Booking::whereDate('booking_date', $today)->where('status', 'completed')->count();
        $activeQueue = Booking::whereDate('booking_date', $today)->whereIn('status', ['new', 'confirmed', 'arrived', 'inspection', 'in_service'])->count();
        $activeMembers = CustomerMembership::where('status', 'active')->where('expires_at', '>=', now())->count();
        $totalCustomers = Customer::count();
        $totalSpend = (float) Invoice::where('status', 'issued')->sum('total_amount');

        return [
            Stat::make("Today's Revenue", '₹' . number_format($todayRevenue, 0))
                ->description($todayCars . ' cars completed today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Active Studio Queue', $activeQueue)
                ->description('Bays & arrivals today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Drive Club Members', $activeMembers)
                ->description('Active recurring healthcare plans')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary'),

            Stat::make('Total CRM Vehicles', Vehicle::count())
                ->description($totalCustomers . ' registered customer profiles')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),
        ];
    }
}
