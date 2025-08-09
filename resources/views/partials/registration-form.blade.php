<!-- Registration Form -->
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? trans('messages.auth.register_title') }}
    </h2>
    
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block font-semibold mb-2" for="name">{{ trans('messages.auth.full_name') }}</label>
            <input
                type="text"
                id="name"
                name="name"
                class="border rounded px-4 py-2 w-full @error('name') border-red-500 @enderror"
                placeholder="{{ trans('messages.auth.full_name_placeholder') }}"
                value="{{ old('name') }}"
                required
                autofocus
            />
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block font-semibold mb-2" for="email">{{ trans('messages.auth.email') }}</label>
            <input
                type="email"
                id="email"
                name="email"
                class="border rounded px-4 py-2 w-full @error('email') border-red-500 @enderror"
                placeholder="{{ trans('messages.auth.email_placeholder') }}"
                value="{{ old('email') }}"
                required
            />
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block font-semibold mb-2" for="password">{{ trans('messages.auth.password') }}</label>
            <input
                type="password"
                id="password"
                name="password"
                class="border rounded px-4 py-2 w-full @error('password') border-red-500 @enderror"
                placeholder="{{ trans('messages.auth.choose_password') }}"
                required
            />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">{{ trans('messages.auth.password_min') }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block font-semibold mb-2" for="password_confirmation">{{ trans('messages.auth.confirm_password') }}</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="border rounded px-4 py-2 w-full @error('password_confirmation') border-red-500 @enderror"
                placeholder="{{ trans('messages.auth.repeat_password') }}"
                required
            />
            @error('password_confirmation')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    name="terms"
                    class="form-checkbox accent-blue-700 h-5 w-5 @error('terms') border-red-500 @enderror"
                    required
                />
                <span class="ml-2 text-sm">
                    {{ trans('messages.auth.accept_terms_start') }} 
                    @if (Route::has('terms'))
                        <a href="{{ route('terms') }}" class="text-blue-700 hover:underline" target="_blank">
                            {{ trans('messages.auth.terms_conditions') }}
                        </a>
                    @else
                        <a href="#" class="text-blue-700 hover:underline">
                            {{ trans('messages.auth.terms_conditions') }}
                        </a>
                    @endif
                </span>
            </label>
            @error('terms')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    name="newsletter"
                    class="form-checkbox accent-blue-700 h-5 w-5"
                    {{ old('newsletter') ? 'checked' : '' }}
                />
                <span class="ml-2 text-sm">
                    {{ trans('messages.auth.newsletter_consent') }}
                </span>
            </label>
        </div>
        
        <button
            type="submit"
            class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded font-semibold w-full transition duration-200"
        >
            {{ trans('messages.auth.register_button') }}
        </button>
    </form>
    
    <div class="mt-6 text-center text-sm">
        {{ trans('messages.auth.already_registered') }}
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="text-blue-700 hover:underline font-semibold">
                {{ trans('messages.auth.login_link') }}
            </a>
        @else
            <a href="{{ route('connexion') }}" class="text-blue-700 hover:underline font-semibold">
                {{ trans('messages.auth.login_link') }}
            </a>
        @endif
    </div>
</div>
