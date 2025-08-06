@extends('layouts.app')

@section('title', 'Contact - Jatsmanor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100">
    <!-- Header Section -->
    <section class="relative bg-gradient-to-r from-blue-800 to-blue-600 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Contactez-nous</h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                Notre équipe est à votre disposition pour répondre à toutes vos questions
            </p>
            <div class="mt-8">
                <div class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 rounded-full">
                    <i class="fas fa-clock mr-3 text-2xl"></i>
                    <span class="text-lg">Service client 24h/24</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="order-2 lg:order-1">
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">Envoyez-nous un message</h2>
                            <p class="text-gray-600">Nous vous répondons généralement dans les 24 heures</p>
                        </div>
                        @include('partials.contact-form')
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="order-1 lg:order-2">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-xl p-8 text-white">
                        <h2 class="text-3xl font-bold mb-8">Nos coordonnées</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-2">Adresse</h3>
                                    <p class="opacity-90">123 Cocody Résidentiel<br>Abidjan, Côte d'Ivoire</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <i class="fas fa-phone text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-2">Téléphone</h3>
                                    <p class="opacity-90">+225 07 07 07 07</p>
                                    <p class="opacity-90">+225 01 01 01 01</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-2">Email</h3>
                                    <p class="opacity-90">contact@jatsmanor.ci</p>
                                    <p class="opacity-90">info@jatsmanor.ci</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg mb-2">Horaires</h3>
                                    <p class="opacity-90">Lun - Ven: 8h00 - 18h00</p>
                                    <p class="opacity-90">Sam - Dim: 9h00 - 17h00</p>
                                    <p class="opacity-90 text-sm mt-2">Service d'urgence 24h/24</p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="mt-12 pt-8 border-t border-white border-opacity-20">
                            <h3 class="font-semibold text-lg mb-4">Suivez-nous</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-3 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-facebook-f text-xl"></i>
                                </a>
                                <a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-3 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-instagram text-xl"></i>
                                </a>
                                <a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-3 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-twitter text-xl"></i>
                                </a>
                                <a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-3 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Notre localisation</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Venez nous rendre visite dans nos bureaux situés au cœur de Cocody
                </p>
            </div>
            
            <!-- Map Placeholder -->
            <div class="bg-gray-200 rounded-2xl shadow-lg overflow-hidden" style="height: 400px;">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-map-marked-alt text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Carte interactive</h3>
                        <p>La carte sera bientôt disponible</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Questions fréquentes</h2>
                <p class="text-gray-600">Trouvez rapidement les réponses à vos questions</p>
            </div>
            
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                        Comment puis-je réserver une résidence ?
                        <i class="fas fa-chevron-down float-right mt-1"></i>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        Vous pouvez réserver directement en ligne via notre site web ou nous contacter par téléphone pour une assistance personnalisée.
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                        Quels sont les modes de paiement acceptés ?
                        <i class="fas fa-chevron-down float-right mt-1"></i>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        Nous acceptons les cartes bancaires, les virements, Orange Money et MTN Mobile Money.
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                        Y a-t-il des frais d'annulation ?
                        <i class="fas fa-chevron-down float-right mt-1"></i>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        Les conditions d'annulation varient selon le type de réservation. Consultez nos conditions générales pour plus de détails.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// FAQ Toggle
document.addEventListener('DOMContentLoaded', function() {
    const faqButtons = document.querySelectorAll('[data-faq-toggle]');
    
    // Si pas d'attribut data-faq-toggle, utiliser tous les boutons dans la section FAQ
    const buttons = faqButtons.length > 0 ? faqButtons : document.querySelectorAll('.bg-white.rounded-lg.shadow-md button');
    
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i.fa-chevron-down');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('fa-chevron-up');
                icon.classList.remove('fa-chevron-down');
            } else {
                content.classList.add('hidden');
                icon.classList.add('fa-chevron-down');
                icon.classList.remove('fa-chevron-up');
            }
        });
    });
});
</script>
@endpush
@endsection
