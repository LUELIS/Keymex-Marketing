<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nouvelle commande</h1>
            <p class="mt-1 text-sm text-gray-500">Créer une commande de supports print pour un conseiller</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                <svg class="mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                </svg>
                Retour aux commandes
            </a>
        </div>
    </div>

    <form wire:submit="save" class="mt-6 space-y-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-semibold text-gray-900">Conseiller</h3>
                <p class="mt-1 text-sm text-gray-500">Recherchez et sélectionnez le conseiller pour cette commande</p>

                <div class="mt-4 max-w-xl">
                    @if($selectedAdvisor)
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 bg-gray-50 px-4 py-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-keymex-red/10">
                                        <span class="text-sm font-medium text-keymex-red">
                                            {{ substr($selectedAdvisor['firstname'], 0, 1) }}{{ substr($selectedAdvisor['lastname'], 0, 1) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $selectedAdvisor['fullname'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedAdvisor['email'] }}</p>
                                    @if($selectedAdvisor['agency'])
                                        <p class="text-xs text-gray-400">{{ $selectedAdvisor['agency'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <button wire:click="clearAdvisor" type="button" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="advisorSearch"
                                   wire:focus="$set('showAdvisorDropdown', true)"
                                   type="text"
                                   class="block w-full rounded-md border-0 py-2 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-keymex-red sm:text-sm"
                                   placeholder="Rechercher un conseiller par nom ou email..."
                                   autocomplete="off">

                            @if($showAdvisorDropdown && count($advisorResults) > 0)
                                <ul class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                    @foreach($advisorResults as $index => $advisor)
                                        <li wire:click="selectAdvisor({{ $index }})"
                                            class="relative cursor-pointer select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-keymex-red hover:text-white group">
                                            <div class="flex items-center">
                                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 group-hover:bg-keymex-red-hover">
                                                    <span class="text-xs font-medium text-gray-600 group-hover:text-white">
                                                        {{ substr($advisor['firstname'], 0, 1) }}{{ substr($advisor['lastname'], 0, 1) }}
                                                    </span>
                                                </span>
                                                <span class="ml-3 truncate">
                                                    <span class="font-medium">{{ $advisor['fullname'] }}</span>
                                                    <span class="text-gray-500 group-hover:text-white/70 ml-2">{{ $advisor['email'] }}</span>
                                                </span>
                                            </div>
                                            @if($advisor['agency'])
                                                <span class="ml-11 text-xs text-gray-400 group-hover:text-white/70">{{ $advisor['agency'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif($showAdvisorDropdown && strlen($advisorSearch) >= 2 && count($advisorResults) === 0)
                                <div class="absolute z-10 mt-1 w-full rounded-md bg-white py-4 text-center text-sm text-gray-500 shadow-lg ring-1 ring-black/5">
                                    Aucun conseiller trouvé
                                </div>
                            @endif
                        </div>
                    @endif

                    @error('selectedAdvisor')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Articles</h3>
                        <p class="mt-1 text-sm text-gray-500">Ajoutez les supports print à commander</p>
                    </div>
                    <button wire:click="addItem" type="button"
                            class="inline-flex items-center rounded-md bg-keymex-red/10 px-3 py-2 text-sm font-semibold text-keymex-red hover:bg-keymex-red/20">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Ajouter un article
                    </button>
                </div>

                @error('items')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-6 space-y-4">
                    @foreach($items as $index => $item)
                        <div class="relative rounded-lg border border-gray-200 bg-gray-50 p-4" wire:key="item-{{ $index }}">
                            @if(count($items) > 1)
                                <button wire:click="removeItem({{ $index }})" type="button"
                                        class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.519.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                                <div class="sm:col-span-4">
                                    <label for="items-{{ $index }}-support" class="block text-sm font-medium text-gray-700">
                                        Type de support <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model.live="items.{{ $index }}.support_type_id"
                                            id="items-{{ $index }}-support"
                                            class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm">
                                        <option value="">Sélectionner...</option>
                                        @foreach($supportTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error("items.{$index}.support_type_id")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="items-{{ $index }}-format" class="block text-sm font-medium text-gray-700">Format</label>
                                    <select wire:model="items.{{ $index }}.format_id"
                                            id="items-{{ $index }}-format"
                                            class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm"
                                            @if(empty($item['available_formats'])) disabled @endif>
                                        <option value="">{{ empty($item['available_formats']) ? 'N/A' : 'Sélectionner...' }}</option>
                                        @foreach($item['available_formats'] ?? [] as $format)
                                            <option value="{{ $format['id'] }}">{{ $format['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="items-{{ $index }}-category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                                    <select wire:model="items.{{ $index }}.category_id"
                                            id="items-{{ $index }}-category"
                                            class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm">
                                        <option value="">Aucune</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="items-{{ $index }}-quantity" class="block text-sm font-medium text-gray-700">
                                        Quantité <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="items.{{ $index }}.quantity"
                                           type="number"
                                           min="1"
                                           id="items-{{ $index }}-quantity"
                                           class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm">
                                    @error("items.{$index}.quantity")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="items-{{ $index }}-notes" class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                                <input wire:model="items.{{ $index }}.notes"
                                       type="text"
                                       id="items-{{ $index }}-notes"
                                       class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm"
                                       placeholder="Instructions spécifiques pour cet article...">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-semibold text-gray-900">Notes générales</h3>
                <p class="mt-1 text-sm text-gray-500">Instructions ou commentaires pour cette commande</p>

                <div class="mt-4">
                    <textarea wire:model="notes"
                              rows="3"
                              class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-keymex-red sm:text-sm"
                              placeholder="Ajoutez des instructions ou commentaires..."></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-4">
            <a href="{{ route('orders.index') }}"
               class="text-sm font-semibold text-gray-900 hover:text-gray-700">
                Annuler
            </a>
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-keymex-red px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-keymex-red-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-keymex-red"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                <span wire:loading.remove wire:target="save">Créer la commande</span>
                <span wire:loading wire:target="save">
                    <svg class="animate-spin -ml-0.5 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Création...
                </span>
            </button>
        </div>
    </form>
</div>
