<!-- Forgot Password Form -->
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? 'Mot de passe oublié' }}
    </h2>
    
    <div class="mb-4 text-sm text-gray-600 text-center">
        Vous avez oublié votre mot de passe ? Pas de problème. Indiquez simplement votre adresse email et nous vous enverrons un lien de réinitialisation.
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
            <label class="block font-semibold mb-2" for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="border rounded px-4 py-2 w-full @error('email') border-red-500 @enderror"
                placeholder="Votre adresse email"
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
            Envoyer le lien de réinitialisation
        </button>
    </form>
    
    <div class="mt-6 text-center text-sm">
        Vous vous souvenez de votre mot de passe ?
        <a href="{{ route('login') }}" class="text-blue-700 hover:underline font-semibold">
            Se connecter
        </a>
    </div>
</div>
