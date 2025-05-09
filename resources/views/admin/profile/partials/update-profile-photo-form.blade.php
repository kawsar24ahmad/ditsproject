<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Photo') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('admin.profile.changePhoto') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label :value="__('Photo')" />
            <div>
                @if (auth()->user()->avatar != null)
                <img  class="w-40 h-40" src="{{  asset(auth()->user()->avatar) }}" alt="">
                @else
                    <img class="w-40 h-40" src="{{ asset('default.png') }}" alt="">
                @endif
            </div>
        </div>

        <div>
            <x-input-label for="avatar" :value="__('Change Photo')" />
            <input type="file" name="avatar">
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
