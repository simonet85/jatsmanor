<!-- Email Verification Form -->
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? trans('messages.auth.email_verification_title') }}
    </h2>
    
    <div class="mb-6 text-sm text-gray-700 text-center">
        <i class="fas fa-envelope-circle-check text-4xl text-blue-700 mb-4"></i>
        <p class="mb-3">
            {{ trans('messages.auth.verification_message') }}
        </p>
        <p>
            {{ trans('messages.auth.verification_not_received') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm text-center">
            <i class="fas fa-check-circle mr-1"></i>
            {{ trans('messages.auth.verification_link_sent') }}
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

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button
                type="submit"
                class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded font-semibold w-full transition duration-200"
            >
                <i class="fas fa-paper-plane mr-2"></i>
                {{ trans('messages.auth.resend_verification') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded font-semibold w-full transition duration-200"
            >
                <i class="fas fa-sign-out-alt mr-2"></i>
                {{ trans('messages.auth.logout') }}
            </button>
        </form>
    </div>
    
    <div class="mt-6 text-center text-sm text-gray-500">
        <i class="fas fa-info-circle mr-1"></i>
        {{ trans('messages.auth.check_spam') }}
    </div>
</div>
