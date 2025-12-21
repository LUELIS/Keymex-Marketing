<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Commandes</h1>
            <p class="mt-1 text-sm text-gray-500">Gestion des commandes de supports print</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('orders.create') }}"
               class="inline-flex items-center rounded-md bg-keymex-red px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-keymex-red-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-keymex-red">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Nouvelle commande
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="p-4 border-b border-gray-200">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="sm:col-span-2">
                    <label for="search" class="sr-only">Rechercher</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search"
                               type="search"
                               id="search"
                               class="block w-full rounded-md border-0 py-2 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-keymex-red sm:text-sm"
                               placeholder="Rechercher un conseiller...">
                    </div>
                </div>

                <div>
                    <label for="status" class="sr-only">Statut</label>
                    <select wire:model.live="status"
                            id="status"
                            class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="supportType" class="sr-only">Type de support</label>
                    <select wire:model.live="supportType"
                            id="supportType"
                            class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm">
                        <option value="">Tous les supports</option>
                        @foreach($supportTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($search || $status || $supportType)
                <div class="mt-3">
                    <button wire:click="clearFilters" type="button" class="text-sm text-keymex-red hover:text-keymex-red-hover">
                        Effacer les filtres
                    </button>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            <button wire:click="sortBy('advisor_name')" class="group inline-flex items-center">
                                Conseiller
                                @if($sortField === 'advisor_name')
                                    <span class="ml-2 flex-none rounded text-gray-400">
                                        @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                                        @else
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </span>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Supports</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Statut</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                            <button wire:click="sortBy('created_at')" class="group inline-flex items-center">
                                Date
                                @if($sortField === 'created_at')
                                    <span class="ml-2 flex-none rounded text-gray-400">
                                        @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                                        @else
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </span>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                                <div class="flex items-center">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $order->advisor_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->advisor_agency }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($order->items as $item)
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                            {{ $item->supportType->name }}
                                            @if($item->quantity > 1)
                                                <span class="ml-1 text-gray-400">x{{ $item->quantity }}</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'bat_sent' => 'bg-yellow-100 text-yellow-700',
                                        'validated' => 'bg-green-100 text-green-700',
                                        'refused' => 'bg-red-100 text-red-700',
                                        'modifications_requested' => 'bg-orange-100 text-orange-700',
                                        'completed' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y') }}
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('orders.show', $order) }}" class="text-keymex-red hover:text-keymex-red-hover">
                                    Voir<span class="sr-only">, {{ $order->advisor_name }}</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune commande</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle commande.</p>
                                <div class="mt-6">
                                    <a href="{{ route('orders.create') }}" class="inline-flex items-center rounded-md bg-keymex-red px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-keymex-red-hover">
                                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                        </svg>
                                        Nouvelle commande
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
