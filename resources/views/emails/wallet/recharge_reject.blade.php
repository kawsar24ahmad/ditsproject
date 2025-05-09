<x-mail::message>
# Hello {{ $user->name }},

We're sorry to inform you that your wallet recharge of **{{ number_format($transaction->amount, 2) }} ৳** has been **rejected**.

### 📄 Recharge Details

- **Sender Number:** {{ $transaction->sender_number ?? 'N/A' }}
- **Amount:** {{ number_format($transaction->amount, 2) }} ৳
- **Status:** Rejected
- **Requested At:** {{ $transaction->created_at->format('Y-m-d H:i:s') }}
- **Transaction ID:** {{ $transaction->transaction_id ?? 'N/A' }}
- **Recharge Method:** {{ ucfirst($transaction->method ?? 'N/A') }}
- **Payment Platform:** {{ ucfirst($transaction->payment_method ?? 'N/A') }}
- **Transaction Type:** {{ ucfirst($transaction->type ?? 'N/A') }}
- **Note:** {{ $transaction->description ?? 'N/A' }}

---

If you believe this was a mistake or need further clarification, please contact our support team.

<x-mail::button :url="route('user.dashboard')">
View Your Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
