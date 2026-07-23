<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\GraciaEarningRule;
use App\Models\GraciaPointLedger;
use App\Models\GraciaUserBalance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GraciaPointsService
{
    public function getActiveRule(): ?GraciaEarningRule
    {
        return GraciaEarningRule::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest('id')
            ->first();
    }

    public function awardPointsForBooking(Booking $booking, ?User $admin = null): void
    {
        if ($booking->status !== 'confirmed') return;
        if (!$booking->user_id) return;
        
        $idempotencyKey = "booking_{$booking->id}_verified";

        DB::transaction(function () use ($booking, $admin, $idempotencyKey) {
            if (GraciaPointLedger::where('idempotency_key', $idempotencyKey)->exists()) {
                return;
            }

            $rule = $this->getActiveRule();
            if (!$rule || $rule->spend_threshold_centavos <= 0) {
                return;
            }

            $spendCentavos = (int) round($booking->total_price * 100);
            
            $balance = GraciaUserBalance::firstOrCreate(
                ['user_id' => $booking->user_id],
                ['current_points' => 0, 'unconverted_spend_centavos' => 0]
            );

            $totalEligibleCentavos = $balance->unconverted_spend_centavos + $spendCentavos;
            
            $awardedMultiples = intdiv($totalEligibleCentavos, $rule->spend_threshold_centavos);
            $pointsEarned = $awardedMultiples * $rule->points_awarded;
            
            $remainderCentavos = $totalEligibleCentavos % $rule->spend_threshold_centavos;
            
            if ($pointsEarned > 0 || $spendCentavos > 0) {
                GraciaPointLedger::create([
                    'user_id' => $booking->user_id,
                    'booking_id' => $booking->id,
                    'gracia_earning_rule_id' => $rule->id,
                    'points' => $pointsEarned,
                    'entry_type' => 'earned',
                    'qualifying_spend_centavos' => $spendCentavos,
                    'reason' => 'Points earned for booking ' . $booking->transaction_number,
                    'admin_id' => $admin?->id,
                    'idempotency_key' => $idempotencyKey,
                ]);

                $balance->current_points += $pointsEarned;
                $balance->unconverted_spend_centavos = $remainderCentavos;
                $balance->save();
            }
        });
    }

    public function reversePointsForBooking(Booking $booking, ?User $admin = null): void
    {
        if (!$booking->user_id) return;

        $idempotencyKey = "booking_{$booking->id}_reversed";

        DB::transaction(function () use ($booking, $admin, $idempotencyKey) {
            if (GraciaPointLedger::where('idempotency_key', $idempotencyKey)->exists()) {
                return;
            }

            $earnedEntry = GraciaPointLedger::where('booking_id', $booking->id)
                ->where('entry_type', 'earned')
                ->first();

            if (!$earnedEntry) {
                return;
            }

            $balance = GraciaUserBalance::firstOrCreate(
                ['user_id' => $booking->user_id],
                ['current_points' => 0, 'unconverted_spend_centavos' => 0]
            );

            $reversedPoints = -$earnedEntry->points;
            
            $rule = $earnedEntry->rule;
            $unconvertedAdjustment = 0;
            if ($rule && $rule->points_awarded > 0) {
                $multiplesReversed = $earnedEntry->points / $rule->points_awarded;
                $centavosReversedFromPoints = $multiplesReversed * $rule->spend_threshold_centavos;
                $unconvertedAdjustment = $centavosReversedFromPoints - $earnedEntry->qualifying_spend_centavos;
            } else {
                $unconvertedAdjustment = -$earnedEntry->qualifying_spend_centavos;
            }

            GraciaPointLedger::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'gracia_earning_rule_id' => $earnedEntry->gracia_earning_rule_id,
                'points' => $reversedPoints,
                'entry_type' => 'reversed',
                'qualifying_spend_centavos' => -$earnedEntry->qualifying_spend_centavos,
                'reason' => 'Points reversed for cancelled/refunded booking ' . $booking->transaction_number,
                'admin_id' => $admin?->id,
                'idempotency_key' => $idempotencyKey,
            ]);

            $balance->current_points += $reversedPoints;
            $balance->unconverted_spend_centavos += $unconvertedAdjustment;
            $balance->save();
        });
    }

    public function addManualAdjustment(User $user, int $points, string $reason, User $admin): void
    {
        DB::transaction(function () use ($user, $points, $reason, $admin) {
            GraciaPointLedger::create([
                'user_id' => $user->id,
                'points' => $points,
                'entry_type' => 'admin_adjustment',
                'reason' => $reason,
                'admin_id' => $admin->id,
            ]);

            $balance = GraciaUserBalance::firstOrCreate(
                ['user_id' => $user->id],
                ['current_points' => 0, 'unconverted_spend_centavos' => 0]
            );

            $balance->current_points += $points;
            $balance->save();
        });
    }
}
