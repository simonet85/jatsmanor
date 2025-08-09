<!-- Contact Form -->
<form class="space-y-6" method="POST" action="{{ route('contact.store') }}" id="contactForm">
    @csrf
    
    <!-- Loading overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">{{ trans('messages.contact_form.sending') }}</p>
        </div>
    </div>
    
    @if ($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ trans('messages.contact_form.validation_errors') }}</h3>
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
                {{ trans('messages.contact_form.full_name') }} *
            </label>
            <input
                type="text"
                id="name"
                name="name"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                value="{{ old('name') }}"
                placeholder="{{ trans('messages.contact_form.full_name_placeholder') }}"
                required
            />
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">
                <i class="fas fa-envelope mr-2 text-blue-600"></i>
                {{ trans('messages.contact_form.email') }} *
            </label>
            <input
                type="email"
                id="email"
                name="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                value="{{ old('email') }}"
                placeholder="{{ trans('messages.contact_form.email_placeholder') }}"
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
            {{ trans('messages.contact_form.phone') }}
        </label>
        <input
            type="tel"
            id="phone"
            name="phone"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
            value="{{ old('phone') }}"
            placeholder="{{ trans('messages.contact_form.phone_placeholder') }}"
        />
        @error('phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2" for="subject">
            <i class="fas fa-tag mr-2 text-blue-600"></i>
            {{ trans('messages.contact_form.subject') }} *
        </label>
        <select
            id="subject"
            name="subject"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('subject') border-red-500 @enderror"
            required
        >
            <option value="">{{ trans('messages.contact_form.choose_subject') }}</option>
            <option value="{{ trans('messages.contact_form.reservation') }}" {{ old('subject') == trans('messages.contact_form.reservation') ? 'selected' : '' }}>{{ trans('messages.contact_form.reservation') }}</option>
            <option value="{{ trans('messages.contact_form.general_info') }}" {{ old('subject') == trans('messages.contact_form.general_info') ? 'selected' : '' }}>{{ trans('messages.contact_form.general_info') }}</option>
            <option value="{{ trans('messages.contact_form.technical_support') }}" {{ old('subject') == trans('messages.contact_form.technical_support') ? 'selected' : '' }}>{{ trans('messages.contact_form.technical_support') }}</option>
            <option value="{{ trans('messages.contact_form.complaint') }}" {{ old('subject') == trans('messages.contact_form.complaint') ? 'selected' : '' }}>{{ trans('messages.contact_form.complaint') }}</option>
            <option value="{{ trans('messages.contact_form.partnership') }}" {{ old('subject') == trans('messages.contact_form.partnership') ? 'selected' : '' }}>{{ trans('messages.contact_form.partnership') }}</option>
            <option value="{{ trans('messages.contact_form.other') }}" {{ old('subject') == trans('messages.contact_form.other') ? 'selected' : '' }}>{{ trans('messages.contact_form.other') }}</option>
        </select>
        @error('subject')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2" for="message">
            <i class="fas fa-comment mr-2 text-blue-600"></i>
            {{ trans('messages.contact_form.message') }} *
        </label>
        <textarea
            id="message"
            name="message"
            rows="5"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none @error('message') border-red-500 @enderror"
            placeholder="{{ trans('messages.contact_form.message_placeholder') }}"
            required
        >{{ old('message') }}</textarea>
        @error('message')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">
            <span id="charCount">0</span>/1000 {{ trans('messages.contact_form.characters') }}
        </p>
    </div>
    
    <button
        type="submit"
        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg px-6 py-4 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >
        <i class="fas fa-paper-plane mr-2"></i>
        {{ trans('messages.contact_form.send_message') }}
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
                alert('{{ trans('messages.contact_form.fill_required_fields') }}');
                return;
            }
            
            if (message.length < 10) {
                e.preventDefault();
                alert('{{ trans('messages.contact_form.message_min_length') }}');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ trans('messages.contact_form.sending') }}';
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
