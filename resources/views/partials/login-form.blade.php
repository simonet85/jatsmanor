<!-- Login Form -->
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? 'Connexion à Jatsmanor' }}
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
            <label class="block font-semibold mb-2" for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="border rounded px-4 py-2 w-full @error('email') border-red-500 @enderror"
                placeholder="Votre email"
                value="{{ old('email') }}"
                required
                autofocus
            />
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block font-semibold mb-2" for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                class="border rounded px-4 py-2 w-full @error('password') border-red-500 @enderror"
                placeholder="Votre mot de passe"
                required
            />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex items-center justify-between mb-4">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    class="form-checkbox accent-blue-700 h-5 w-5"
                    {{ old('remember') ? 'checked' : '' }}
                />
                <span class="ml-2 text-sm">Se souvenir de moi</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-blue-700 text-sm hover:underline">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>
        
        <button
            type="submit"
            class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded font-semibold w-full transition duration-200"
        >
            Se connecter
        </button>
    </form>
    
    <div class="mt-6 text-center text-sm">
        Pas encore de compte ?
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="text-blue-700 hover:underline font-semibold">
                Créer un compte
            </a>
        @else
            <a href="{{ route('contact') }}" class="text-blue-700 hover:underline font-semibold">
                Nous contacter
            </a>
        @endif
    </div>
</div>
