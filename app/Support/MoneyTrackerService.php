<?php

namespace App\Support;

use App\Models\CashFlow;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;

class MoneyTrackerService
{
    public function cutoffDay(): int
    {
        return 25;
    }

    public function usesCutoff(?User $user = null): bool
    {
        if (! $user) {
            return true;
        }

        return (bool) $user->is_cutoff_enabled;
    }

    public function periodForMonth(?string $month = null, ?User $user = null): array
    {
        $target = $month
            ? CarbonImmutable::createFromFormat('Y-m', $month)->startOfMonth()
            : CarbonImmutable::now()->startOfMonth();

        if (! $this->usesCutoff($user)) {
            return [
                'month' => $target->format('Y-m'),
                'label' => $target->translatedFormat('F Y'),
                'start' => $target->startOfMonth(),
                'end' => $target->endOfMonth(),
                'is_cutoff_enabled' => false,
            ];
        }

        $previous = $target->subMonth();
        $cutoff = $this->cutoffDay();

        return [
            'month' => $target->format('Y-m'),
            'label' => $target->translatedFormat('F Y'),
            'start' => $previous->day($cutoff + 1)->startOfDay(),
            'end' => $target->day($cutoff)->endOfDay(),
            'is_cutoff_enabled' => true,
        ];
    }

    public function savingMonthForDate(CarbonInterface $date, ?User $user = null): string
    {
        $immutable = CarbonImmutable::instance($date);

        if (! $this->usesCutoff($user)) {
            return $immutable->format('Y-m');
        }

        $cutoff = $this->cutoffDay();

        return ($immutable->day <= $cutoff ? $immutable : $immutable->addMonth())->format('Y-m');
    }

    public function mustSavingForCashFlow(CashFlow $cashFlow): int
    {
        if ($cashFlow->type !== 'expense' || ! $cashFlow->category) {
            return 0;
        }

        return (int) round($cashFlow->amount * ((float) $cashFlow->category->percentage / 100));
    }

    public function mustSavingTotal(Collection $cashFlows): int
    {
        return $cashFlows->sum(fn (CashFlow $cashFlow) => $this->mustSavingForCashFlow($cashFlow));
    }
}
