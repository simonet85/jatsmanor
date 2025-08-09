<!-- Login Form -->
<div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 w-full max-w-md mx-4 sm:mx-0">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? trans('messages.auth.login_title') }}
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

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
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
                autofocus
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
                placeholder="{{ trans('messages.auth.password_placeholder') }}"
                required
            />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    class="form-checkbox accent-blue-700 h-5 w-5"
                    {{ old('remember') ? 'checked' : '' }}
                />
                <span class="ml-2 text-sm">{{ trans('messages.auth.remember_me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-blue-700 text-sm hover:underline">
                    {{ trans('messages.auth.forgot_password') }}
                </a>
            @endif
        </div>
        
        <button
            type="submit"
            class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded font-semibold w-full transition duration-200"
        >
            {{ trans('messages.auth.login_button') }}
        </button>
    </form>
    
    <div class="mt-6 text-center text-sm">
        {{ trans('messages.auth.no_account') }}
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="text-blue-700 hover:underline font-semibold">
                {{ trans('messages.auth.create_account') }}
            </a>
        @else
            <a href="{{ route('contact') }}" class="text-blue-700 hover:underline font-semibold">
                {{ trans('messages.auth.contact_us') }}
            </a>
        @endif
    </div>
</div>
