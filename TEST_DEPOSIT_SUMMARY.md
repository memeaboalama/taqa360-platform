# Test Deposit Diagnostic Log Analysis

## Current Status
The storage/logs directory doesn't exist yet because no deposits have been processed through the system. To capture actual logs, a test deposit needs to be executed through the running Laravel application.

## Expected Log Flow (based on code changes)

### 1. Deposit Initiation (processDeposit)
When user clicks "Continue" on the deposit form:
```
POST /account/wallet/deposit.process
→ WalletController@processDeposit
→ walletDepositAction->create()
→ creates WalletTransaction with status='confirmed', confirmed=0
→ redirects to payment iframe with token
```

### 2. Payment Iframe Load
When iframe loads:
```
GET /account/wallet/payment/iframe/{token}
→ WalletController@paymentIframe (shows payment form)
```

### 3. Moyasar Payment Completion
When user completes payment with Mada/Visa/MasterCard:
- Moyasar processes the payment
- Moyasar sends POST callback to: `wallet/payment/check` (now accepts both GET/POST)
- Moyasar saves payment record with payable_id and payable_type (fixed)

### 4. Callback Receipt - Expected Logs
From `WalletDepositController@payment_check`:
```
[2026-...] INFO: === payment_check started ===
[2026-...] INFO: Request data: {"id":"py-...", "status":"paid", "amount":100, ...}
[2026-...] INFO: checkDeposit started
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

### 5. Expected Database State After Successful Deposit
- `wallet_transactions` table: status='confirmed', confirmed=1
- `payments` table: status='paid', payable_id=<wallet_transaction_id>, payable_type=App\Domain\User\Models\User
- Wallets table: balance increased by deposit amount

## Common Issues Identified & Fixed

### Issue 1: payable_id/payable_type not set
**Before fix:** Payment record saved without linking to WalletTransaction
**After fix:** `savePayment()` now sets:
```php
$postFields['payable_id'] = $model->id;
$postFields['payable_type'] = get_class($model);
```

### Issue 2: Callback route only accepted GET
**Before fix:** Route was `Route::get('wallet/payment/check', ...)`
**After fix:** `Route::match(['get', 'post'], 'wallet/payment/check', ...)`

### Issue 3: No diagnostic logging
**Before fix:** No logs showing the actual flow
**After fix:** Comprehensive logging at each step to trace the issue

## How to Test

To get actual logs, you need to:

1. **Set up Moyasar test credentials** in .env:
   ```
   MOYASAR_API_PUBLIC=your_test_public_key
   MOYASAR_API_SECRET=your_test_secret_key
   ```

2. **Run migrations** to ensure database tables exist:
   ```bash
   php artisan migrate
   ```

3. **Start the Laravel server:**
   ```bash
   php artisan serve
   ```

4. **Execute a test deposit:**
   - Go to `/account/wallet/deposit`
   - Enter amount (e.g., 100)
   - Select "Mada/Visa/MasterCard"
   - Complete the payment through Moyasar sandbox
   - Moyasar will send callback to `/wallet/payment/check`

5. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

**Expected transaction test:**
- Deposit 100 SAR
- Moyasar confirms success
- Check logs show the full sequence above
- Verify wallet balance increased by 100 SAR
- Verify database: wallet_transactions status='confirmed', payments status='paid'