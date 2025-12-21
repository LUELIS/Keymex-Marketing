<div class="space-y-6">
    {{-- Header --}}
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-900">Validation du BAT</h1>
        <p class="text-gray-500 mt-2">Bon a Tirer</p>
    </div>

        @if($alreadyResponded)
            {{-- Already responded state --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="flex justify-center mb-4">
                    @if($bat->status === 'validated')
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @elseif($bat->status === 'refused')
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    @elseif($bat->status === 'modifications_requested')
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-orange-100">
                            <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                            <svg class="h-8 w-8 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    @if($bat->status === 'validated')
                        BAT Valide
                    @elseif($bat->status === 'refused')
                        BAT Refuse
                    @elseif($bat->status === 'modifications_requested')
                        Modifications demandees
                    @else
                        Reponse enregistree
                    @endif
                </h2>

                <p class="text-gray-500 mb-4">
                    Votre reponse a ete enregistree le {{ $bat->responded_at?->format('d/m/Y a H:i') }}.
                </p>

                @if($bat->client_comment)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg text-left">
                        <p class="text-sm font-medium text-gray-700 mb-1">Votre commentaire :</p>
                        <p class="text-gray-600">{{ $bat->client_comment }}</p>
                    </div>
                @endif
            </div>
        @elseif(!$tokenValid)
            {{-- Token expired or invalid --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-semibold text-gray-900 mb-2">Lien expire</h2>
                <p class="text-gray-500">
                    Ce lien de validation a expire. Veuillez contacter votre conseiller pour obtenir un nouveau lien.
                </p>
            </div>
        @else
            {{-- BAT Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div>
                            @if($bat->title)
                                <h2 class="text-xl font-semibold text-gray-900">{{ $bat->title }}</h2>
                            @else
                                <h2 class="text-xl font-semibold text-gray-900">BAT #{{ $bat->id }}</h2>
                            @endif
                            <p class="text-sm text-gray-500 mt-1">
                                De : {{ $bat->advisor_name }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                            En attente
                        </span>
                    </div>

                    @if($bat->description)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">{{ $bat->description }}</p>
                        </div>
                    @endif
                </div>

                {{-- File Preview --}}
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Document</h3>

                    @if(str_starts_with($bat->file_mime, 'image/'))
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <img
                                src="{{ asset('storage/' . $bat->file_path) }}"
                                alt="{{ $bat->file_name }}"
                                class="w-full h-auto"
                            >
                        </div>
                    @else
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-red-100">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $bat->file_name }}</p>
                                    <p class="text-sm text-gray-500">Document PDF</p>
                                </div>
                            </div>
                            <a
                                href="{{ asset('storage/' . $bat->file_path) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-keymex-red text-white rounded-lg hover:bg-keymex-red-hover transition-colors"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Votre decision</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <button
                        wire:click="openConfirm('validate')"
                        class="flex flex-col items-center justify-center p-6 border-2 border-green-200 rounded-xl hover:bg-green-50 hover:border-green-400 transition-colors group"
                    >
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 group-hover:bg-green-200 transition-colors mb-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-green-700">Valider</span>
                        <span class="text-xs text-gray-500 mt-1">J'approuve ce BAT</span>
                    </button>

                    <button
                        wire:click="openConfirm('modifications')"
                        class="flex flex-col items-center justify-center p-6 border-2 border-orange-200 rounded-xl hover:bg-orange-50 hover:border-orange-400 transition-colors group"
                    >
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 group-hover:bg-orange-200 transition-colors mb-3">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-orange-700">Modifications</span>
                        <span class="text-xs text-gray-500 mt-1">Des changements sont necessaires</span>
                    </button>

                    <button
                        wire:click="openConfirm('refuse')"
                        class="flex flex-col items-center justify-center p-6 border-2 border-red-200 rounded-xl hover:bg-red-50 hover:border-red-400 transition-colors group"
                    >
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 group-hover:bg-red-200 transition-colors mb-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-red-700">Refuser</span>
                        <span class="text-xs text-gray-500 mt-1">Je refuse ce BAT</span>
                    </button>
                </div>
            </div>

            {{-- Expiration notice --}}
            <p class="text-center text-sm text-gray-400 mt-6">
                Ce lien expire le {{ $bat->token_expires_at?->format('d/m/Y') }}
            </p>
        @endif
    </div>

    {{-- Confirmation Modal --}}
    @if($showConfirmModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div wire:click="closeConfirm" class="absolute inset-0 bg-gray-900/50"></div>

            {{-- Modal Content --}}
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    @if($action === 'validate')
                        Confirmer la validation
                    @elseif($action === 'modifications')
                        Demander des modifications
                    @else
                        Confirmer le refus
                    @endif
                </h3>

                <div class="space-y-4">
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                            Commentaire
                            @if($action !== 'validate')
                                <span class="text-red-500">*</span>
                            @else
                                (optionnel)
                            @endif
                        </label>
                        <textarea
                            wire:model="comment"
                            id="comment"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-keymex-red focus:border-keymex-red"
                            placeholder="@if($action === 'validate')Ajoutez un commentaire si necessaire...@elseif($action === 'modifications')Decrivez les modifications souhaitees...@else Indiquez la raison du refus...@endif"
                        ></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button
                        wire:click="closeConfirm"
                        type="button"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Annuler
                    </button>
                    <button
                        wire:click="confirm"
                        type="button"
                        class="flex-1 px-4 py-2 text-white rounded-lg transition-colors
                            @if($action === 'validate') bg-green-600 hover:bg-green-700
                            @elseif($action === 'modifications') bg-orange-600 hover:bg-orange-700
                            @else bg-red-600 hover:bg-red-700 @endif"
                    >
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
