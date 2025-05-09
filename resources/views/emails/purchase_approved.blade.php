@component('mail::message')
# Hello {{ $user->name }},

Your purchase for the service **{{ $service->service->title ?? 'Service' }}** has been **approved**!

---

### 📄 Purchase Details:

- **Service:** {{ $service->service->title ?? 'Service' }}
- **Facebook Page Name:** {{ $service->walletTransaction?->facebookAd?->facebookPage?->page_name ?? 'N/A' }}
- **Price:** {{ number_format($service->price, 2) }} ৳
- **Status:** Approved
- **Purchase Date:** {{ $service->created_at->format('Y-m-d H:i:s') }}
- **Transaction ID:** {{ $service->walletTransaction->transaction_id ?? 'N/A' }}
- **Payment Method:** {{ ucfirst($service->walletTransaction->method ?? 'N/A') }}
- **Transaction Type:** {{ ucfirst($service->walletTransaction->type ?? 'N/A') }}

---

@component('mail::button', ['url' => route('user.dashboard')])
View My Services
@endcomponent

Thank you for choosing our service.

Regards,<br>
{{ config('app.name') }}
@endcomponent
