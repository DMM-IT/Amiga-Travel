<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /**
     * Get the authenticated user's referral code (generate if missing).
     */
    public function myCode(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->referral_code) {
            $user->referral_code = $this->generateCode();
            $user->save();
        }

        $totalReferrals    = Referral::where('referrer_id', $user->id)->count();
        $completedReferrals = Referral::where('referrer_id', $user->id)->where('status', 'completed')->count();

        return response()->json([
            'status'              => 'success',
            'referral_code'       => $user->referral_code,
            'total_referrals'     => $totalReferrals,
            'completed_referrals' => $completedReferrals,
        ]);
    }

    /**
     * Apply a referral code after registration.
     * Called once per user — if they've already redeemed or already have a referral, deny.
     */
    public function applyCode(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|max:10']);

        $user = $request->user();

        // Already redeemed
        if ($user->referral_redeemed) {
            return response()->json(['status' => 'error', 'message' => 'You have already used a referral code.'], 422);
        }

        $code = strtoupper(trim($request->code));
        $referrer = User::where('referral_code', $code)->first();

        if (! $referrer) {
            return response()->json(['status' => 'error', 'message' => 'Invalid referral code.'], 422);
        }

        // Self-referral block
        if ($referrer->id === $user->id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot use your own referral code.'], 422);
        }

        // Already has a referral row (e.g. set at registration)
        if (Referral::where('referee_id', $user->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'You have already used a referral code.'], 422);
        }

        Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id'  => $user->id,
            'code_used'   => $code,
            'status'      => 'pending',
        ]);

        $user->referral_redeemed = true;
        $user->referred_by       = $referrer->id;
        $user->save();

        // Give the referee (new user) a 5% starter voucher capped at ₱50
        $this->issueVoucher($user->id, 5, 50, 'Welcome referral bonus — 5% OFF your first booking!');

        // Notify the referrer that someone used their code
        UserNotification::notify(
            $referrer->id,
            '🎉 Someone used your referral code!',
            "{$user->name} just registered with your code. You'll earn a bonus once they complete their first booking.",
            'referral',
            'card_giftcard',
            ['referee_name' => $user->name]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Referral code applied! You have been given a 5% starter voucher.',
        ]);
    }

    /**
     * Called internally when a referred user completes their first paid booking.
     * Issues a tiered reward voucher to the referrer.
     */
    public static function onBookingCompleted(int $userId): void
    {
        $referral = Referral::where('referee_id', $userId)
            ->where('status', 'pending')
            ->with('referrer')
            ->first();

        if (! $referral) {
            return;
        }

        $referral->update(['status' => 'completed', 'completed_at' => now()]);

        $referrer = $referral->referrer;

        // Tiered reward: count completed referrals AFTER this one
        $completedCount = Referral::where('referrer_id', $referrer->id)
            ->where('status', 'completed')
            ->count();

        // First 3 completed = 5%, after that = 10%
        $percent  = $completedCount <= 3 ? 5 : 10;
        $cap      = $completedCount <= 3 ? 300 : 500;

        (new self())->issueVoucher($referrer->id, $percent, $cap, "Referral reward — {$percent}% OFF for bringing in a new booker!");

        UserNotification::notify(
            $referrer->id,
            '🎁 Referral Reward Unlocked!',
            "Your referral ({$referral->referee->name}) just completed their first booking! A {$percent}% OFF voucher has been added to your account.",
            'referral',
            'card_giftcard',
            ['referee_name' => $referral->referee->name, 'discount_percent' => $percent]
        );
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function generateCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no 0/O, 1/I
        do {
            $code = '';
            for ($i = 0; $i < 7; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    private function issueVoucher(int $userId, int $percent, int $maxPeso, string $description): void
    {
        try {
            $voucher = Voucher::create([
                'code'                 => 'REF-' . strtoupper(Str::random(6)),
                'name'                 => $description,
                'discount_type'        => 'percentage',
                'discount_value'       => $percent,
                'max_discount'         => $maxPeso,
                'is_active'            => true,
                'is_hidden'            => true,
                'one_use_per_customer' => true,
                'end_at'               => now()->addDays(7),
            ]);

            $voucher->claimedByUsers()->attach($userId);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('ReferralController: could not issue voucher — ' . $e->getMessage());
        }
    }
}
