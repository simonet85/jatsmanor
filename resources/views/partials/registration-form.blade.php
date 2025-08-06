<!-- Registration Form -->
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? 'Créer un compte' }}
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
            <label class="block font-semibold mb-2" for="name">Nom complet</label>
            <input
                type="text"
                id="name"
                name="name"
                class="border rounded px-4 py-2 w-full @error('name') border-red-500 @enderror"
                placeholder="Votre nom complet"
                value="{{ old('name') }}"
                required
                autofocus
            />
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
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
                placeholder="Choisissez un mot de passe"
                required
            />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
        </div>
        
        <div class="mb-4">
            <label class="block font-semibold mb-2" for="password_confirmation">Confirmer le mot de passe</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="border rounded px-4 py-2 w-full @error('password_confirmation') border-red-500 @enderror"
                placeholder="Répétez le mot de passe"
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
                    J'accepte les 
                    @if (Route::has('terms'))
                        <a href="{{ route('terms') }}" class="text-blue-700 hover:underline" target="_blank">
                            conditions d'utilisation
                        </a>
                    @else
                        <a href="#" class="text-blue-700 hover:underline">
                            conditions d'utilisation
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
                    Je souhaite recevoir les offres et actualités par email
                </span>
            </label>
        </div>
        
        <button
            type="submit"
            class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded font-semibold w-full transition duration-200"
        >
            S'inscrire
        </button>
    </form>
    
    <div class="mt-6 text-center text-sm">
        Déjà inscrit ?
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="text-blue-700 hover:underline font-semibold">
                Se connecter
            </a>
        @else
            <a href="{{ route('connexion') }}" class="text-blue-700 hover:underline font-semibold">
                Se connecter
            </a>
        @endif
    </div>
</div>
