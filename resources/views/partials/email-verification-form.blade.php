<!-- Email Verification Form -->
<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">
        {{ $title ?? 'Vérification de l\'email' }}
    </h2>
    
    <div class="mb-6 text-sm text-gray-700 text-center">
        <i class="fas fa-envelope-circle-check text-4xl text-blue-700 mb-4"></i>
        <p class="mb-3">
            Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ?
        </p>
        <p>
            Si vous n'avez pas reçu l'email, nous vous en enverrons un autre avec plaisir.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm text-center">
            <i class="fas fa-check-circle mr-1"></i>
            Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de l'inscription.
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
                Renvoyer l'email de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded font-semibold w-full transition duration-200"
            >
                <i class="fas fa-sign-out-alt mr-2"></i>
                Se déconnecter
            </button>
        </form>
    </div>
    
    <div class="mt-6 text-center text-sm text-gray-500">
        <i class="fas fa-info-circle mr-1"></i>
        Vérifiez aussi votre dossier spam ou courrier indésirable
    </div>
</div>
