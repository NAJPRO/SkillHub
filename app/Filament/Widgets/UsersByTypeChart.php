<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class UsersByTypeChart extends ChartWidget
{

   // protected static ?string $heading = __('user.user_distribution');
    protected static string $chartType = 'pie';
    protected static ?string $maxHeight = '400px';
    protected static ?int $sort = 2;

    public function getHeading(): string{
        return  __('users.user_distribution');
    }

    public static function canView(): bool
    {
         /** @var User $user */
         $user = Auth::user();
        return $user->can('show_usersByTypeChart');
    }



    protected function getData(): array
    {


        $types = ['client', 'freelance', 'admin'];

        $counts = [];

        foreach ($types as $type) {
            $counts[] = User::where('role', $type)->count();
        }

        return [
            'labels' => [
                __('users.role.client'),
                __('users.role.freelance'),
                __('users.role.admin'),
            ],
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => ['#3b82f6', '#ef4444', '#10b981'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
