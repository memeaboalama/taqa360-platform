# Test Deposit Execution & Log Analysis

## ⚠️ Important: Cannot Execute Live Test Without Moyasar Credentials

To run an actual test deposit and capture real Laravel logs, you need:
1. **Moyasar test/sandbox API credentials** (public + secret keys)
2. **Running Laravel application** with `php artisan serve`
3. **Moyasar sandbox environment** enabled

## Without live test, here's the exact diagnostic flow:

### Step 1: Set Test Environment
```bash
# Add to .env file
MOYASAR_API_PUBLIC=test_public_key
MOYASAR_API_SECRET=test_secret_key
MOYASAR_API_URL=https://test.moyasar.com/v1
```

### Step 2: Run Migrations
```bash
php artisan migrate
# This creates: payments, wallet_transactions tables
```

### Step 3: Start Server & Test
```bash
php artisan serve
# Then visit: http://localhost:8000/account/wallet/deposit
```

### Step 4: Execute Deposit 100 SAR
1. Enter amount: 100
2. Select: Mada/Visa/MasterCard
3. Complete payment in Moyasar sandbox
4. Moyasar sends POST callback to: `/wallet/payment/check`

### Step 5: Capture & Show Logs
After completion, run:
```bash
tail -50 storage/logs/laravel.log
```

**Expected log sequence (based on added logging):**

```
[2026-...] INFO: === payment_check started ===
[2026-...] INFO: Request data: {"id":"py-12345","status":"paid","amount":100,"currency":"SAR",...}
[2026-...] INFO: checkDeposit started
[2026-...] INFO: Input data: {"id":"py-12345","status":"paid","amount":100}
[2026-...] INFO: Payment status from API: paid
[2026-...] INFO: Payment found by payment_id: yes (id: 1)
[2026-...] INFO: Wallet deposit found: yes (id: 1)
[2026-...] INFO: Payment status is 'paid', updating wallet deposit confirmed=1, status=confirmed
[2026-...] INFO: User found: 1
[2026-...] INFO: Checking wallet: UID_1_Main
[2026-...] INFO: Wallet balance before deposit: 50.00
[2026-...] INFO: About to call depositFloat with amount: 100
[2026-...] INFO: depositFloat called successfully
[2026-...] INFO: Wallet balance after deposit: 150.00
[2026-...] INFO: payment_status success: updated deposit confirmed=1, status=confirmed for id=1
```

### Step 6: Verify Database Results

** wallet_transactions table:**
| id | user_id | type | amount | confirmed | status |
|----|---------|------|--------|-----------|--------|
| 1 | 1 | deposit | 100 | 1 | confirmed |

** payments table:**
| id | payment_id | status | amount | payable_id | payable_type |
|----|------------|--------|--------|------------|--------------|
| 1 | py-12345 | paid | 100 | 1 | App\Models\User |

** wallets table:** balance increased by 100 SAR

## If Logs Show Different Results, Here's How to Fix:

### Case A: "Callback not reaching server"
- Check: Route accepts POST (fixed to `match(['get','post'])`)
- Check: Callback URL configured in Moyasar dashboard points to correct route

### Case B: "Payment found but no wallet deposit"
- Verify: `payable_id` and `payable_type` are set in Moyasar savePayment (fixed)
- Check: WalletTransaction has correct user_id and type='deposit'

### Case C: "Wallet found but depositFloat not called"
- Check: User has wallet 'UID_{id}_Main'
- Check: depositFloat parameters are correct (amount, type, status, confirmed)

### Case D: "depositFloat called but balance not updating"
- Check: Currency conversion (riyal vs halla - Moyasar may send in halla = amount*100)
- Check: No exceptions thrown (try-catch added in code)