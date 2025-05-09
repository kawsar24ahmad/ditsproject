@extends('user.layouts.app')

@section('content')

<div class="content-wrapper">
    <div class="container py-5 d-flex justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card p-4 shadow rounded-4 border-0 bg-white">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <span class="fs-2 text-primary">📢</span>
                    </div>
                    <h4 class="mb-0 mt-3 fw-bold">ফেসবুক অ্যাডস</h4>
                </div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('user.services.facebook_ad.buy') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-capitalize">Profile / Page Link</label>
                        <input type="text" name="page_link" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        @php
                        $user = auth()->user();
                        $pages = $user->facebookPages;
                        @endphp
                        <label for="facebookPageSelect" class="form-label fw-semibold text-capitalize">Select Your Page</label>
                        <select name="facebook_page_id" id="facebookPageSelect" class="form-select">
                            <option value="">Select Page</option>
                            @foreach ($pages as $page)
                            <option class="text-black" value="{{ $page->id }}">{{ $page->page_name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="customRange1" class="form-label">Budget</label>
                        <div class="d-flex justify-content-center">
                            <span class="ms-3"> <strong style="font-size:x-large;" id="selectedValue">$5</strong></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span id="minValue">$1</span>
                            <input type="range" name="budget" class="form-range" id="customRange1" min="1" max="500" value="5">
                            <span id="maxValue">$500</span>

                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-capitalize">Duration (Days)</label>
                        <input type="number" name="duration" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3 d-flex justify-content-between">
                        <div class="me-2 w-50">
                            <label class="form-label fw-semibold text-capitalize">Min Age</label>
                            <input type="number" name="min_age" class="form-control rounded-3" required>
                        </div>
                        <div class="ms-2 w-50">
                            <label class="form-label fw-semibold text-capitalize">Max Age</label>
                            <input type="number" name="max_age" class="form-control rounded-3" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-capitalize">Location</label>
                        <input type="text" name="location" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
    <label class="form-label fw-semibold text-capitalize">Button</label>
    <select name="button" id="buttonSelect" class="form-select rounded-3" required>
        <option value="">Select</option>
        <option value="no_button">No Button</option>
        <option value="book_now">Book Now</option>
        <option value="learn_more">Learn More</option>
        <option value="shop_now">Shop Now</option> <!-- Added shop_now -->
        <option value="sign_up">Sign Up</option>
        <option value="send_message">Send Message (Messenger/WhatsApp)</option>
        <option value="call_now">Call Now</option>
    </select>
</div>

<div class="mb-3 d-none" id="urlDiv">
    <label class="form-label fw-semibold">URL</label>
    <input type="text" name="url" id="urlInput" class="form-control rounded-3" placeholder="https://example.com" required>
</div>
<div class="mb-3 d-none" id="phoneDiv">
    <label class="form-label fw-semibold">Phone Number</label>
    <input type="text" name="number" id="phoneInput" class="form-control rounded-3" placeholder="+8801XXXXXXXXX" required>
</div>

                    <div class="mb-3">
                        <p class="form-label fw-semibold">Automatic message template</p>
                        <label class="form-label fw-semibold">Greeting</label>
                        <textarea name="greeting" class="form-control rounded-3" rows="4">{{ old('greeting', 'Hi, আসসালামু আলাইকুম! আগ্রহ প্রকাশ করার জন্য ধন্যবাদ...') }}</textarea>

                    </div>




                    <div class="mb-4 text-center">
                        <strong>Price:</strong>
                        <input type="hidden" name="price" value="{{ $service->offer_price ?? $service->price }}">
                        <span class="text-success fs-5">{{ $service->offer_price ?? $service->price }} ৳</span>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow">
                            Submit & Pay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    const rangeInput = document.getElementById('customRange1');
    const selectedValue = document.getElementById('selectedValue');

    rangeInput.addEventListener('input', function() {
        selectedValue.textContent = `$${this.value}`;
    });

    const buttonSelect = document.getElementById('buttonSelect');
    const urlDiv = document.getElementById('urlDiv');
    const phoneDiv = document.getElementById('phoneDiv');
    const urlInput = document.getElementById('urlInput');
    const phoneInput = document.getElementById('phoneInput');

    buttonSelect.addEventListener('change', function() {
        const val = this.value;

        // Show URL input for these buttons
        if (['book_now', 'learn_more', 'shop_now', 'sign_up'].includes(val)) {
            urlDiv.classList.remove('d-none');
            urlInput.required = true;
        } else {
            urlDiv.classList.add('d-none');
            urlInput.required = false;
            urlInput.value = '';
        }

        // Show phone number input for these buttons
        if (['send_message', 'call_now'].includes(val)) {
            phoneDiv.classList.remove('d-none');
            phoneInput.required = true;
        } else {
            phoneDiv.classList.add('d-none');
            phoneInput.required = false;
            phoneInput.value = '';
        }

        // Hide both for no_button or empty selection
        if (val === '' || val === 'no_button') {
            urlDiv.classList.add('d-none');
            phoneDiv.classList.add('d-none');
            urlInput.required = false;
            phoneInput.required = false;
            urlInput.value = '';
            phoneInput.value = '';
        }
    });
</script>
@endsection
