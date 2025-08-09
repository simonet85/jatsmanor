<!-- Forgot Password Form -->
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? trans('messages.auth.forgot_password_title') }}
    </h2>
    
    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ trans('messages.auth.forgot_password_desc') }}
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <div class="mb-6">
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
        
        <button
            type="submit"
            class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded font-semibold w-full transition duration-200"
        >
            {{ trans('messages.auth.send_reset_link') }}
        </button>
    </form>
    
    <div class="mt-6 text-center text-sm">
        {{ trans('messages.auth.remember_password') }}
        <a href="{{ route('login') }}" class="text-blue-700 hover:underline font-semibold">
            {{ trans('messages.auth.login_link') }}
        </a>
    </div>
</div>
