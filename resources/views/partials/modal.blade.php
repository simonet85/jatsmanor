<div id="{{ $modalId }}" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-red-500 text-xl" onclick="closeModal('{{ $modalId }}')">
            <i class="fas fa-times"></i>
        </button>
        
        <h2 class="text-xl font-bold mb-6">{{ $modalTitle }}</h2>
        
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>