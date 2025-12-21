<div>
    @if(!$token)
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <h2 class="mt-4 text-xl font-semibold text-gray-900">Lien invalide</h2>
            <p class="mt-2 text-gray-500">Ce lien de validation n'existe pas ou a été supprimé.</p>
        </div>
    @elseif($isExpired)
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="mt-4 text-xl font-semibold text-gray-900">Lien expiré</h2>
            <p class="mt-2 text-gray-500">Ce lien de validation a expiré. Veuillez contacter l'équipe marketing pour obtenir un nouveau lien.</p>
        </div>
    @elseif($isUsed && !session('success'))
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="mt-4 text-xl font-semibold text-gray-900">BAT déjà traité</h2>
            <p class="mt-2 text-gray-500">Ce BAT a déjà reçu une réponse.</p>
            @if($batVersion)
                <div class="mt-4">
                    @php
                        $statusLabels = [
                            'validated' => 'Validé',
                            'refused' => 'Refusé',
                            'modifications_requested' => 'Modifications demandées',
                        ];
                        $statusColors = [
                            'validated' => 'bg-green-100 text-green-700',
                            'refused' => 'bg-red-100 text-red-700',
                            'modifications_requested' => 'bg-orange-100 text-orange-700',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$batVersion->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $statusLabels[$batVersion->status] ?? $batVersion->status }}
                    </span>
                </div>
            @endif
        </div>
    @else
        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h1 class="text-lg font-semibold text-gray-900">Validation du BAT</h1>
                <p class="mt-1 text-sm text-gray-500">Version {{ $batVersion->version_number }}</p>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <h2 class="text-sm font-medium text-gray-500 mb-2">Commande pour</h2>
                    <p class="text-lg font-medium text-gray-900">{{ $batVersion->order->advisor_name }}</p>
                    <p class="text-sm text-gray-500">{{ $batVersion->order->advisor_agency }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-sm font-medium text-gray-500 mb-2">Articles concernés</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($batVersion->order->items as $item)
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                                {{ $item->supportType->name }}
                                @if($item->quantity > 1)
                                    <span class="ml-1 text-gray-400">x{{ $item->quantity }}</span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-sm font-medium text-gray-500 mb-3">Aperçu du BAT</h2>
                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                        @if(str_contains($batVersion->file_mime, 'image'))
                            <img src="{{ Storage::url($batVersion->file_path) }}" alt="BAT" class="w-full h-auto">
                        @else
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">{{ $batVersion->file_name }}</p>
                                <a href="{{ Storage::url($batVersion->file_path) }}" target="_blank"
                                   class="mt-3 inline-flex items-center text-sm font-medium text-keymex-red hover:text-keymex-red-hover">
                                    <svg class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                                        <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                                    </svg>
                                    Télécharger le fichier
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if($isValid)
                    <div class="border-t border-gray-200 pt-6">
                        <h2 class="text-sm font-medium text-gray-500 mb-4">Votre décision</h2>

                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700">
                                Commentaire (obligatoire pour refus ou modifications)
                            </label>
                            <textarea wire:model="comment"
                                      id="comment"
                                      rows="3"
                                      class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-keymex-red sm:text-sm"
                                      placeholder="Ajoutez un commentaire si nécessaire..."></textarea>
                            @error('comment')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button wire:click="confirmAction('validate')" type="button"
                                    class="flex-1 inline-flex justify-center items-center rounded-md bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                                <svg class="-ml-0.5 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                Valider le BAT
                            </button>

                            <button wire:click="confirmAction('modifications')" type="button"
                                    class="flex-1 inline-flex justify-center items-center rounded-md bg-orange-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                                <svg class="-ml-0.5 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                </svg>
                                Modifications
                            </button>

                            <button wire:click="confirmAction('refuse')" type="button"
                                    class="flex-1 inline-flex justify-center items-center rounded-md bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                <svg class="-ml-0.5 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                                Refuser
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($showConfirmation)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="cancelAction"></div>

                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        @if($confirmationAction === 'validate')
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg font-semibold text-gray-900">Confirmer la validation</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir valider ce BAT ? Cette action est définitive.
                                </p>
                            </div>
                        @elseif($confirmationAction === 'modifications')
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-orange-100">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg font-semibold text-gray-900">Demander des modifications</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Confirmez-vous la demande de modifications ? Un nouveau BAT vous sera envoyé.
                                </p>
                            </div>
                        @else
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg font-semibold text-gray-900">Confirmer le refus</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir refuser ce BAT ? Cette action est définitive.
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        @if($confirmationAction === 'validate')
                            <button wire:click="validateBat" type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:col-start-2">
                                Confirmer
                            </button>
                        @elseif($confirmationAction === 'modifications')
                            <button wire:click="requestModifications" type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:col-start-2">
                                Confirmer
                            </button>
                        @else
                            <button wire:click="refuse" type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:col-start-2">
                                Confirmer
                            </button>
                        @endif
                        <button wire:click="cancelAction" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
