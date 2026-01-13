<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configuration Stockage</h1>
            <p class="text-sm text-gray-500 mt-1">
                Configurez le stockage des fichiers (BAT, documents) sur S3 Scaleway
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600">S3 actif</span>
            <button
                wire:click="toggleActive"
                type="button"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $is_active ? 'bg-green-500' : 'bg-gray-200' }}"
            >
                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
            </button>
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

    @if (session()->has('error'))
        <div class="rounded-lg bg-red-50 p-4 border border-red-200">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Current Status --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center gap-4">
            @if($is_active && $driver === 's3')
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-700">Stockage S3 actif</p>
                    <p class="text-sm text-gray-500">Les fichiers sont stockes sur Scaleway Object Storage</p>
                </div>
            @else
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gray-100">
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Stockage local actif</p>
                    <p class="text-sm text-gray-500">Les fichiers sont stockes sur le serveur (non persistant avec Dokploy)</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Main Form --}}
    <form wire:submit="save" class="space-y-6">
        {{-- Driver Selection --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                    Type de stockage
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none {{ $driver === 'local' ? 'border-keymex-red bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="driver" value="local" class="sr-only">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg {{ $driver === 'local' ? 'bg-keymex-red/20' : 'bg-gray-100' }}">
                                <svg class="h-5 w-5 {{ $driver === 'local' ? 'text-keymex-red' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium {{ $driver === 'local' ? 'text-keymex-red' : 'text-gray-900' }}">Local</p>
                                <p class="text-xs text-gray-500">Stockage sur le serveur</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none {{ $driver === 's3' ? 'border-keymex-red bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="driver" value="s3" class="sr-only">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg {{ $driver === 's3' ? 'bg-keymex-red/20' : 'bg-gray-100' }}">
                                <svg class="h-5 w-5 {{ $driver === 's3' ? 'text-keymex-red' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium {{ $driver === 's3' ? 'text-keymex-red' : 'text-gray-900' }}">S3 (Scaleway)</p>
                                <p class="text-xs text-gray-500">Object Storage cloud</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- S3 Configuration --}}
        @if($driver === 's3')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Credentials Scaleway
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Access Key --}}
                        <div>
                            <label for="s3_key" class="block text-sm font-medium text-gray-700 mb-1">Access Key ID</label>
                            <input
                                type="text"
                                wire:model="s3_key"
                                id="s3_key"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red font-mono text-sm"
                                placeholder="SCWXXXXXXXXXXXXXXXXX"
                            >
                            @error('s3_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Secret Key --}}
                        <div>
                            <label for="s3_secret" class="block text-sm font-medium text-gray-700 mb-1">Secret Access Key</label>
                            <div class="relative">
                                <input
                                    type="{{ $showSecret ? 'text' : 'password' }}"
                                    wire:model="s3_secret"
                                    id="s3_secret"
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red font-mono text-sm"
                                    placeholder="Laisser vide pour ne pas modifier"
                                >
                                <button
                                    type="button"
                                    wire:click="toggleSecret"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                                >
                                    @if($showSecret)
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    @endif
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Laissez vide pour conserver la cle actuelle</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bucket Configuration --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        Configuration du bucket
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Region --}}
                        <div>
                            <label for="s3_region" class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                            <select
                                wire:model.live="s3_region"
                                id="s3_region"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                            >
                                @foreach($this->getScalewayRegions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('s3_region')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bucket --}}
                        <div>
                            <label for="s3_bucket" class="block text-sm font-medium text-gray-700 mb-1">Nom du bucket</label>
                            <input
                                type="text"
                                wire:model="s3_bucket"
                                id="s3_bucket"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                                placeholder="keymex-marketing-files"
                            >
                            @error('s3_bucket')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Endpoint --}}
                        <div>
                            <label for="s3_endpoint" class="block text-sm font-medium text-gray-700 mb-1">Endpoint</label>
                            <input
                                type="text"
                                wire:model="s3_endpoint"
                                id="s3_endpoint"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red bg-gray-50"
                                placeholder="https://s3.fr-par.scw.cloud"
                                readonly
                            >
                            <p class="mt-1 text-xs text-gray-500">Auto-configure selon la region</p>
                        </div>

                        {{-- Public URL --}}
                        <div>
                            <label for="s3_url" class="block text-sm font-medium text-gray-700 mb-1">URL publique (optionnel)</label>
                            <input
                                type="text"
                                wire:model="s3_url"
                                id="s3_url"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                                placeholder="https://keymex-marketing-files.s3.fr-par.scw.cloud"
                            >
                            <p class="mt-1 text-xs text-gray-500">URL pour acceder aux fichiers publics</p>
                        </div>
                    </div>

                    {{-- Path Style --}}
                    <div class="flex items-center gap-3 pt-2">
                        <input
                            type="checkbox"
                            wire:model="s3_path_style"
                            id="s3_path_style"
                            class="h-4 w-4 rounded border-gray-300 text-keymex-red focus:ring-keymex-red"
                        >
                        <label for="s3_path_style" class="text-sm text-gray-700">
                            Utiliser le path-style endpoint (recommande pour Scaleway)
                        </label>
                    </div>
                </div>
            </div>

            {{-- Test Connection --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tester la connexion
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        Testez la connexion au bucket S3 pour verifier que les credentials sont corrects.
                    </p>
                    <button
                        type="button"
                        wire:click="testConnection"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        wire:target="testConnection"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors"
                    >
                        <svg wire:loading wire:target="testConnection" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg wire:loading.remove wire:target="testConnection" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span wire:loading.remove wire:target="testConnection">Tester la connexion S3</span>
                        <span wire:loading wire:target="testConnection">Test en cours...</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-between">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-keymex-red hover:bg-keymex-red-hover text-white text-sm font-medium rounded-lg transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer les parametres
            </button>
        </div>
    </form>

    {{-- Info Box --}}
    <div class="rounded-lg bg-blue-50 p-4 border border-blue-200">
        <div class="flex gap-3">
            <svg class="h-5 w-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Pour creer un bucket Scaleway :</p>
                <ol class="list-decimal list-inside space-y-1 text-blue-600">
                    <li>Connectez-vous a la <a href="https://console.scaleway.com" target="_blank" class="underline hover:text-blue-800">console Scaleway</a></li>
                    <li>Allez dans Object Storage > Buckets</li>
                    <li>Creez un nouveau bucket (visibility: private ou public selon vos besoins)</li>
                    <li>Allez dans IAM > API Keys pour creer vos credentials</li>
                    <li>Copiez l'Access Key et le Secret Key</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Warning Box --}}
    @if(!$is_active || $driver !== 's3')
        <div class="rounded-lg bg-amber-50 p-4 border border-amber-200">
            <div class="flex gap-3">
                <svg class="h-5 w-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="text-sm text-amber-700">
                    <p class="font-medium">Attention : Stockage local actif</p>
                    <p class="mt-1">Les fichiers stockes localement seront <strong>supprimes</strong> a chaque redeploiement du container Dokploy. Activez le stockage S3 pour persister vos fichiers.</p>
                </div>
            </div>
        </div>
    @endif
</div>
