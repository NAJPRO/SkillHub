<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class UserStatusBarChart extends ChartWidget
{

    public function getHeading(): string{
        return  __('users.userReportByStatus');
    }

    public static function canView(): bool
    {
         /** @var User $user */
         $user = Auth::user();
        return $user->can('show_userStatusBarChart');
    }
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        /*$user = Filament::auth()->user();
        if (!$user->can('show_userStatusBarChart')) {
            return [];
        }*/
        $counts = User::query()->where('role', '!=' ,'super-admin')
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'labels' => array_map(fn($status) => __('users.status.' . $status), array_keys($counts)),
            'datasets' => [
                [
                    'label' => __('users.number_user'),
                    'data' => array_values($counts),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
