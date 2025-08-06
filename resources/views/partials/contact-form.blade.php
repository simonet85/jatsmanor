<!-- Contact Form -->
<form class="space-y-6" method="POST" action="{{ route('contact.store') }}" id="contactForm">
    @csrf
    
    <!-- Loading overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Envoi en cours...</p>
        </div>
    </div>
    
    @if ($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Erreurs de validation :</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="name">
                <i class="fas fa-user mr-2 text-blue-600"></i>
                Nom complet *
            </label>
            <input
                type="text"
                id="name"
                name="name"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                value="{{ old('name') }}"
                placeholder="Votre nom complet"
                required
            />
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">
                <i class="fas fa-envelope mr-2 text-blue-600"></i>
                Email *
            </label>
            <input
                type="email"
                id="email"
                name="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                value="{{ old('email') }}"
                placeholder="votre@email.com"
                required
            />
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2" for="phone">
            <i class="fas fa-phone mr-2 text-blue-600"></i>
            Téléphone
        </label>
        <input
            type="tel"
            id="phone"
            name="phone"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
            value="{{ old('phone') }}"
            placeholder="+225 XX XX XX XX"
        />
        @error('phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2" for="subject">
            <i class="fas fa-tag mr-2 text-blue-600"></i>
            Sujet *
        </label>
        <select
            id="subject"
            name="subject"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('subject') border-red-500 @enderror"
            required
        >
            <option value="">Choisissez un sujet</option>
            <option value="Réservation" {{ old('subject') == 'Réservation' ? 'selected' : '' }}>Réservation</option>
            <option value="Information générale" {{ old('subject') == 'Information générale' ? 'selected' : '' }}>Information générale</option>
            <option value="Support technique" {{ old('subject') == 'Support technique' ? 'selected' : '' }}>Support technique</option>
            <option value="Réclamation" {{ old('subject') == 'Réclamation' ? 'selected' : '' }}>Réclamation</option>
            <option value="Partenariat" {{ old('subject') == 'Partenariat' ? 'selected' : '' }}>Partenariat</option>
            <option value="Autre" {{ old('subject') == 'Autre' ? 'selected' : '' }}>Autre</option>
        </select>
        @error('subject')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2" for="message">
            <i class="fas fa-comment mr-2 text-blue-600"></i>
            Message *
        </label>
        <textarea
            id="message"
            name="message"
            rows="5"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none @error('message') border-red-500 @enderror"
            placeholder="Décrivez votre demande en détail..."
            required
        >{{ old('message') }}</textarea>
        @error('message')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">
            <span id="charCount">0</span>/1000 caractères
        </p>
    </div>
    
    <button
        type="submit"
        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg px-6 py-4 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >
        <i class="fas fa-paper-plane mr-2"></i>
        Envoyer le message
    </button>
</form>

@push('scripts')
<script>
// Character counter for message field
document.addEventListener('DOMContentLoaded', function() {
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    if (messageField && charCount) {
        messageField.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 900) {
                charCount.className = 'text-red-500 font-semibold';
            } else if (count > 800) {
                charCount.className = 'text-yellow-500 font-semibold';
            } else {
                charCount.className = 'text-gray-500';
            }
        });
        
        // Initial count
        charCount.textContent = messageField.value.length;
    }

    // Form submission with loading state
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    if (form && submitBtn && loadingOverlay) {
        form.addEventListener('submit', function(e) {
            // Client-side validation
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (!name || !email || !message) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                return;
            }
            
            if (message.length < 10) {
                e.preventDefault();
                alert('Le message doit contenir au moins 10 caractères.');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
        });
    }

    // Auto-hide success/error messages after 5 seconds
    const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>
@endpush
