<!-- Sélecteur de langue avec drapeaux -->
<div class="relative inline-block text-left" id="language-selector">
    <!-- Bouton principal -->
    <button type="button" 
            onclick="toggleLanguageDropdown()" 
            class="inline-flex items-center justify-center w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
            aria-haspopup="true" 
            aria-expanded="false">
        
        <!-- Drapeau et langue actuelle -->
        <span class="flex items-center">
            @if(app()->getLocale() === 'fr')
                <span class="text-lg mr-2">🇫🇷</span>
                <span class="hidden sm:inline">Français</span>
                <span class="sm:hidden">FR</span>
            @else
                <span class="text-lg mr-2">🇬🇧</span>
                <span class="hidden sm:inline">English</span>
                <span class="sm:hidden">EN</span>
            @endif
        </span>
        
        <!-- Icône dropdown -->
        <svg class="ml-2 -mr-1 h-4 w-4 transition-transform duration-200" 
             id="language-arrow" 
             xmlns="http://www.w3.org/2000/svg" 
             viewBox="0 0 20 20" 
             fill="currentColor">
            <path fill-rule="evenodd" 
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                  clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Menu dropdown -->
    <div id="language-dropdown" 
         class="hidden absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none transform opacity-0 scale-95 transition-all duration-200 ease-out">
        <div class="py-1" role="menu" aria-orientation="vertical">
            
            <!-- Option Français -->
            <button onclick="switchLanguage('fr')" 
                    class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150 {{ app()->getLocale() === 'fr' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}"
                    role="menuitem">
                <span class="text-xl mr-3">🇫🇷</span>
                <div class="flex flex-col items-start">
                    <span class="font-medium">Français</span>
                    <span class="text-xs text-gray-500 group-hover:text-blue-500">France</span>
                </div>
                @if(app()->getLocale() === 'fr')
                    <svg class="ml-auto h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </button>
            
            <!-- Option English -->
            <button onclick="switchLanguage('en')" 
                    class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150 {{ app()->getLocale() === 'en' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}"
                    role="menuitem">
                <span class="text-xl mr-3">🇬🇧</span>
                <div class="flex flex-col items-start">
                    <span class="font-medium">English</span>
                    <span class="text-xs text-gray-500 group-hover:text-blue-500">United Kingdom</span>
                </div>
                @if(app()->getLocale() === 'en')
                    <svg class="ml-auto h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </button>
            
        </div>
    </div>
</div>

<!-- Toast container pour les notifications -->
<div id="language-toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// ============ GESTION DU SÉLECTEUR DE LANGUE ============

let isLanguageDropdownOpen = false;

function toggleLanguageDropdown() {
    const dropdown = document.getElementById('language-dropdown');
    const arrow = document.getElementById('language-arrow');
    
    if (!isLanguageDropdownOpen) {
        // Ouvrir le dropdown
        dropdown.classList.remove('hidden');
        setTimeout(() => {
            dropdown.classList.remove('opacity-0', 'scale-95');
            dropdown.classList.add('opacity-100', 'scale-100');
        }, 10);
        arrow.style.transform = 'rotate(180deg)';
        isLanguageDropdownOpen = true;
        
        // Fermer si on clique ailleurs
        document.addEventListener('click', closeLanguageDropdownOnClickOutside);
    } else {
        closeLanguageDropdown();
    }
}

function closeLanguageDropdown() {
    const dropdown = document.getElementById('language-dropdown');
    const arrow = document.getElementById('language-arrow');
    
    dropdown.classList.remove('opacity-100', 'scale-100');
    dropdown.classList.add('opacity-0', 'scale-95');
    arrow.style.transform = 'rotate(0deg)';
    
    setTimeout(() => {
        dropdown.classList.add('hidden');
    }, 200);
    
    isLanguageDropdownOpen = false;
    document.removeEventListener('click', closeLanguageDropdownOnClickOutside);
}

function closeLanguageDropdownOnClickOutside(event) {
    const selector = document.getElementById('language-selector');
    if (!selector.contains(event.target)) {
        closeLanguageDropdown();
    }
}

// ============ CHANGEMENT DE LANGUE ============

function switchLanguage(locale) {
    closeLanguageDropdown();
    
    // Méthode simple avec paramètre GET
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('lang', locale);
    
    // Afficher un toast de chargement
    showLanguageToast('Changement de langue...', 'loading');
    
    // Rediriger avec le paramètre de langue
    window.location.href = currentUrl.toString();
}

// ============ SYSTÈME DE TOAST POUR LA LANGUE ============

function showLanguageToast(message, type = 'success', duration = 3000) {
    const container = document.getElementById('language-toast-container');
    const toast = document.createElement('div');
    const toastId = 'lang-toast-' + Date.now();
    toast.id = toastId;
    
    const config = {
        success: {
            icon: 'fas fa-check-circle',
            bgColor: 'bg-green-500',
            textColor: 'text-white'
        },
        error: {
            icon: 'fas fa-times-circle',
            bgColor: 'bg-red-500',
            textColor: 'text-white'
        },
        loading: {
            icon: 'fas fa-spinner fa-spin',
            bgColor: 'bg-blue-500',
            textColor: 'text-white'
        }
    };
    
    const settings = config[type] || config.success;
    
    toast.className = `transform transition-all duration-300 ease-in-out ${settings.bgColor} ${settings.textColor} px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 min-w-64 translate-x-full opacity-0`;
    
    toast.innerHTML = `
        <i class="${settings.icon}"></i>
        <span class="font-medium">${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    
    // Auto-suppression
    if (type !== 'loading' && duration > 0) {
        setTimeout(() => {
            removeLanguageToast(toastId);
        }, duration);
    }
    
    return toastId;
}

function removeLanguageToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

// Fermer le dropdown avec Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && isLanguageDropdownOpen) {
        closeLanguageDropdown();
    }
});
</script>
