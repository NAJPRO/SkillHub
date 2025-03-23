<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class NewUsersChart extends ChartWidget
{

    public function getHeading(): string{
        return  __('users.new_users_last_30_days');
    }


    public static function canView(): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->can('show_new_users_last_30_days');
    }
    protected function getData(): array
    {

        $dates = [];
        $counts = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $counts[] = User::whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => __('users.inscription'),
                    'data' => $counts,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
