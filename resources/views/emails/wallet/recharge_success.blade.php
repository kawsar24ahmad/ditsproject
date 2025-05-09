<x-mail::message>
# Hello {{ $user->name }},

Your wallet has been successfully recharged with **{{ number_format($transaction->amount, 2) }} ৳**.

You can now use your wallet to purchase services from your dashboard.

### 📄 Recharge Details:

- **Sender Number:** {{ $transaction->sender_number ?? 'N/A' }}
- **Amount:** {{ number_format($transaction->amount, 2) }} ৳
- **Status:** Approved
- **Purchase Date:** {{ $transaction->created_at->format('Y-m-d H:i:s') }}
- **Transaction ID:** {{ $transaction->transaction_id ?? 'N/A' }}
- **Method:** {{ ucfirst($transaction->method ?? 'N/A') }}
- **Payment Method:** {{ ucfirst($transaction->payment_method ?? 'N/A') }}
- **Transaction Type:** {{ ucfirst($transaction->type ?? 'N/A') }}
- **Description:** {{ $transaction->description ?? 'N/A' }}

---

<x-mail::button :url="route('user.dashboard')">
Your Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
