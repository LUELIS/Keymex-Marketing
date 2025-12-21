<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configuration Commandes</h1>
            <p class="text-sm text-gray-500 mt-1">
                Gerez les types de support, formats et categories pour les commandes
            </p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="rounded-lg bg-green-50 p-4 border border-green-200">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex gap-6" aria-label="Tabs">
            <button
                wire:click="setTab('support_types')"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'support_types' ? 'border-keymex-red text-keymex-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Types de support
                <span class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-xs">
                    {{ $supportTypes->count() }}
                </span>
            </button>
            <button
                wire:click="setTab('formats')"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'formats' ? 'border-keymex-red text-keymex-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Formats
                <span class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-xs">
                    {{ $formats->count() }}
                </span>
            </button>
            <button
                wire:click="setTab('categories')"
                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'categories' ? 'border-keymex-red text-keymex-red' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Categories
                <span class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-xs">
                    {{ $categories->count() }}
                </span>
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    @if($activeTab === 'support_types')
        {{-- Support Types --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Types de support</h2>
                <button
                    wire:click="openSupportTypeModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-keymex-red hover:bg-keymex-red-hover text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter
                </button>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($supportTypes as $supportType)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center justify-center h-10 w-10 rounded-lg bg-red-100 text-keymex-red font-semibold text-sm">
                                {{ $supportType->formats->count() }}
                            </span>
                            <div>
                                <p class="font-medium text-gray-900">{{ $supportType->name }}</p>
                                <p class="text-sm text-gray-500">{{ $supportType->formats->count() }} format(s)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button
                                wire:click="toggleSupportTypeActive({{ $supportType->id }})"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $supportType->is_active ? 'bg-keymex-red' : 'bg-gray-200' }}"
                                title="{{ $supportType->is_active ? 'Actif' : 'Inactif' }}"
                            >
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $supportType->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            <button
                                wire:click="openSupportTypeModal({{ $supportType->id }})"
                                class="p-2 text-gray-400 hover:text-keymex-red transition-colors"
                                title="Modifier"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button
                                wire:click="confirmDelete('support_type', {{ $supportType->id }})"
                                class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                title="Supprimer"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Aucun type de support. Cliquez sur "Ajouter" pour en creer un.
                    </div>
                @endforelse
            </div>
        </div>
    @elseif($activeTab === 'formats')
        {{-- Formats --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Formats</h2>
                <button
                    wire:click="openFormatModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-keymex-red hover:bg-keymex-red-hover text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter
                </button>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($formats as $format)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $format->name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $format->supportType?->name ?? 'Sans type' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $format->supportType?->is_active ? 'bg-red-100 text-keymex-red' : 'bg-gray-100 text-gray-700' }}">
                                {{ $format->supportType?->name ?? '-' }}
                            </span>
                            <button
                                wire:click="toggleFormatActive({{ $format->id }})"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $format->is_active ? 'bg-keymex-red' : 'bg-gray-200' }}"
                                title="{{ $format->is_active ? 'Actif' : 'Inactif' }}"
                            >
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $format->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            <button
                                wire:click="openFormatModal({{ $format->id }})"
                                class="p-2 text-gray-400 hover:text-keymex-red transition-colors"
                                title="Modifier"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button
                                wire:click="confirmDelete('format', {{ $format->id }})"
                                class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                title="Supprimer"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Aucun format. Cliquez sur "Ajouter" pour en creer un.
                    </div>
                @endforelse
            </div>
        </div>
    @else
        {{-- Categories --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Categories</h2>
                <button
                    wire:click="openCategoryModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-keymex-red hover:bg-keymex-red-hover text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter
                </button>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($categories as $category)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <p class="font-medium text-gray-900">{{ $category->name }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button
                                wire:click="toggleCategoryActive({{ $category->id }})"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $category->is_active ? 'bg-keymex-red' : 'bg-gray-200' }}"
                                title="{{ $category->is_active ? 'Actif' : 'Inactif' }}"
                            >
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $category->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                            <button
                                wire:click="openCategoryModal({{ $category->id }})"
                                class="p-2 text-gray-400 hover:text-keymex-red transition-colors"
                                title="Modifier"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button
                                wire:click="confirmDelete('category', {{ $category->id }})"
                                class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                title="Supprimer"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Aucune categorie. Cliquez sur "Ajouter" pour en creer une.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- Modal Support Type --}}
    @if($showSupportTypeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div wire:click="closeSupportTypeModal" class="absolute inset-0 bg-gray-900/50"></div>

            {{-- Modal Content --}}
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ $editingSupportTypeId ? 'Modifier le type de support' : 'Nouveau type de support' }}
                </h3>

                <div class="space-y-4">
                    <div>
                        <label for="supportTypeName" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input
                            type="text"
                            wire:model="supportTypeName"
                            id="supportTypeName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                            placeholder="Ex: Panneau"
                        >
                        @error('supportTypeName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            wire:click="$toggle('supportTypeActive')"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $supportTypeActive ? 'bg-keymex-red' : 'bg-gray-200' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $supportTypeActive ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                        <span class="text-sm text-gray-700">Actif</span>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button
                        wire:click="closeSupportTypeModal"
                        type="button"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Annuler
                    </button>
                    <button
                        wire:click="saveSupportType"
                        type="button"
                        class="flex-1 px-4 py-2 bg-keymex-red text-white rounded-lg hover:bg-keymex-red-hover transition-colors"
                    >
                        {{ $editingSupportTypeId ? 'Modifier' : 'Creer' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Format --}}
    @if($showFormatModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div wire:click="closeFormatModal" class="absolute inset-0 bg-gray-900/50"></div>

            {{-- Modal Content --}}
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ $editingFormatId ? 'Modifier le format' : 'Nouveau format' }}
                </h3>

                <div class="space-y-4">
                    <div>
                        <label for="formatName" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input
                            type="text"
                            wire:model="formatName"
                            id="formatName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                            placeholder="Ex: 4x3"
                        >
                        @error('formatName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="formatSupportTypeId" class="block text-sm font-medium text-gray-700 mb-1">Type de support</label>
                        <select
                            wire:model="formatSupportTypeId"
                            id="formatSupportTypeId"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                        >
                            <option value="">Selectionnez un type</option>
                            @foreach($supportTypesForSelect as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('formatSupportTypeId')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            wire:click="$toggle('formatActive')"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $formatActive ? 'bg-keymex-red' : 'bg-gray-200' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $formatActive ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                        <span class="text-sm text-gray-700">Actif</span>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button
                        wire:click="closeFormatModal"
                        type="button"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Annuler
                    </button>
                    <button
                        wire:click="saveFormat"
                        type="button"
                        class="flex-1 px-4 py-2 bg-keymex-red text-white rounded-lg hover:bg-keymex-red-hover transition-colors"
                    >
                        {{ $editingFormatId ? 'Modifier' : 'Creer' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Category --}}
    @if($showCategoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div wire:click="closeCategoryModal" class="absolute inset-0 bg-gray-900/50"></div>

            {{-- Modal Content --}}
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ $editingCategoryId ? 'Modifier la categorie' : 'Nouvelle categorie' }}
                </h3>

                <div class="space-y-4">
                    <div>
                        <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input
                            type="text"
                            wire:model="categoryName"
                            id="categoryName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                            placeholder="Ex: Immobilier"
                        >
                        @error('categoryName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            wire:click="$toggle('categoryActive')"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $categoryActive ? 'bg-keymex-red' : 'bg-gray-200' }}"
                        >
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $categoryActive ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                        <span class="text-sm text-gray-700">Actif</span>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button
                        wire:click="closeCategoryModal"
                        type="button"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Annuler
                    </button>
                    <button
                        wire:click="saveCategory"
                        type="button"
                        class="flex-1 px-4 py-2 bg-keymex-red text-white rounded-lg hover:bg-keymex-red-hover transition-colors"
                    >
                        {{ $editingCategoryId ? 'Modifier' : 'Creer' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div wire:click="closeDeleteModal" class="absolute inset-0 bg-gray-900/50"></div>

            {{-- Modal Content --}}
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Confirmer la suppression
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Etes-vous sur de vouloir supprimer cet element ? Cette action est irreversible.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button
                        wire:click="closeDeleteModal"
                        type="button"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Annuler
                    </button>
                    <button
                        wire:click="delete"
                        type="button"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
