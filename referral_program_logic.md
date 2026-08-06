# Referral Program — System Logic

## 1. Referral Code Generation
- Every registered user is automatically issued a unique referral code (e.g. a 6–8 character alphanumeric string tied to their account).
- The code is shown in **My Profile**, with a "Copy" button and quick-share buttons for social platforms (Facebook, Messenger, etc.).
- Use a format that avoids ambiguous characters (0/O, 1/I) to prevent manual-entry errors.

## 2. Applying a Referral Code (Registration Flow)
- After a new user creates an account, prompt once: **"Do you have a referral code?"** → [Enter code] / [Skip]
- Validation rules:
  1. Code must exist and belong to an active account.
  2. A user cannot use their own code (self-referral block).
  3. A user may redeem a referral code **only once, ever** — the limit lives on the *redeeming account*, not on the code, so it can't be reused later by skipping now and entering it after.
- If valid, the system links referee → referrer and marks the referral as **pending**.

## 3. Reward Trigger — decision needed
Pick one:
- **On registration** — simplest, but easiest to abuse with throwaway sign-ups.
- **On the referee's first completed transaction** — a bit more friction, but confirms a real, paying user before paying out. *Recommended.*

## 4. Reward Issued to the Referrer
Once the referral is confirmed, the referrer receives a **voucher**:
- Discount: 5–10% off (decide: one flat % for everyone, or tiered — e.g. 5% for the first few invites, 10% after a threshold)
- No minimum spend required
- Maximum discount amount: pick one fixed cap, e.g. **₱300** or **₱500** — a "300–500" range isn't enforceable as written; either fix one number or tier it the same way as the %
- Validity: **7 days** from the date it's issued

A referrer can earn this voucher **repeatedly** — once per new person they successfully bring in. There's no cap on how many people one code can be used by; the only cap is on the *referee* side (one redemption per new account).

## 5. Anti-Abuse Safeguards
- One redemption per referee account, enforced at the account level.
- Block self-referral (code owner ≠ redeemer).
- Consider basic multi-accounting checks (same device ID, phone number, or payment method) so one person can't spin up throwaway accounts to farm rewards for themselves.
- Log every redemption attempt, valid or invalid, for fraud monitoring.

## 6. Data to Track

| Entity | Key fields |
|---|---|
| User | `referral_code` (unique), `referred_by` (nullable) |
| Referral | `referrer_id`, `referee_id`, `code_used`, `status` (pending/completed), `created_at` |
| Voucher | `user_id`, `discount_percent`, `max_discount_amount`, `issued_at`, `expires_at`, `used` (bool) |

## 7. Notifications
- Notify the referrer when their code is redeemed and a voucher is credited.
- Send a reminder ~1 day before a voucher expires, to nudge usage.

## 8. Open Decisions Before Building
1. Reward trigger: on registration, or on referee's first purchase?
2. Exact discount %: flat 5%, flat 10%, or tiered by invite count?
3. Exact voucher cap: ₱300 or ₱500 — pick one, or tier it?
4. Does the **referee** (the one entering the code) also get a starter voucher, or does only the referrer earn a reward? Most referral programs reward both sides, since that's what makes entering a code worth it.
