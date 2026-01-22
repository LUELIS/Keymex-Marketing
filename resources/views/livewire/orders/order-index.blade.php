<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Commandes</h1>
            <p class="text-sm text-gray-500 mt-1">
                Gestion des commandes de supports print
            </p>
        </div>
        <a href="{{ route('orders.create') }}"
           wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-keymex-red to-red-600 hover:from-keymex-red-hover hover:to-red-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle commande
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher par conseiller, email, agence..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm placeholder-gray-400 focus:bg-white focus:border-keymex-red focus:ring-2 focus:ring-keymex-red/20 transition-all duration-200"
                >
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-4 w-4 text-keymex-red animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>

            {{-- Status Filter (Custom Dropdown) --}}
            @php
                $statusOptions = [
                    '' => ['label' => 'Tous les statuts', 'dot' => 'bg-gray-400'],
                    'pending' => ['label' => 'En attente', 'dot' => 'bg-gray-400'],
                    'in_progress' => ['label' => 'En cours', 'dot' => 'bg-blue-500'],
                    'bat_sent' => ['label' => 'BAT envoye', 'dot' => 'bg-amber-500'],
                    'validated' => ['label' => 'Valide', 'dot' => 'bg-emerald-500'],
                    'refused' => ['label' => 'Refuse', 'dot' => 'bg-rose-500'],
                    'modifications_requested' => ['label' => 'Modifications', 'dot' => 'bg-orange-500'],
                    'completed' => ['label' => 'Termine', 'dot' => 'bg-teal-500'],
                ];
                $currentStatus = $statusOptions[$status] ?? $statusOptions[''];
            @endphp
            <div class="sm:w-52 relative" x-data="{ open: false }" @click.away="open = false">
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center gap-2 pl-3 pr-10 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700 hover:bg-white hover:border-gray-300 focus:bg-white focus:border-keymex-red focus:ring-2 focus:ring-keymex-red/20 transition-all duration-200 cursor-pointer text-left"
                >
                    <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex items-center gap-2 flex-1">
                        <span class="h-2 w-2 rounded-full {{ $currentStatus['dot'] }}"></span>
                        <span>{{ $currentStatus['label'] }}</span>
                    </span>
                </button>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-lg border border-gray-200 py-1 overflow-hidden"
                    style="display: none;"
                >
                    @foreach($statusOptions as $value => $option)
                        <button
                            type="button"
                            wire:click="$set('status', '{{ $value }}')"
                            @click="open = false"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors {{ $status === $value ? 'bg-red-50' : '' }}"
                        >
                            <span class="h-2.5 w-2.5 rounded-full {{ $option['dot'] }}"></span>
                            <span class="flex-1 text-left {{ $status === $value ? 'font-medium text-keymex-red' : 'text-gray-700' }}">
                                {{ $option['label'] }}
                            </span>
                            @if($status === $value)
                                <svg class="h-4 w-4 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Support Type Filter (Custom Dropdown) --}}
            <div class="sm:w-52 relative" x-data="{ open: false }" @click.away="open = false">
                @php
                    $currentSupportType = $supportTypes->firstWhere('id', $supportType);
                @endphp
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center gap-2 pl-3 pr-10 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700 hover:bg-white hover:border-gray-300 focus:bg-white focus:border-keymex-red focus:ring-2 focus:ring-keymex-red/20 transition-all duration-200 cursor-pointer text-left"
                >
                    <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="flex-1 truncate">{{ $currentSupportType?->name ?? 'Tous les supports' }}</span>
                </button>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-lg border border-gray-200 py-1 overflow-hidden max-h-64 overflow-y-auto"
                    style="display: none;"
                >
                    <button
                        type="button"
                        wire:click="$set('supportType', '')"
                        @click="open = false"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors {{ $supportType === '' ? 'bg-red-50' : '' }}"
                    >
                        <span class="flex-1 text-left {{ $supportType === '' ? 'font-medium text-keymex-red' : 'text-gray-700' }}">
                            Tous les supports
                        </span>
                        @if($supportType === '')
                            <svg class="h-4 w-4 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @endif
                    </button>
                    @foreach($supportTypes as $type)
                        <button
                            type="button"
                            wire:click="$set('supportType', '{{ $type->id }}')"
                            @click="open = false"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors {{ $supportType == $type->id ? 'bg-red-50' : '' }}"
                        >
                            <span class="flex-1 text-left {{ $supportType == $type->id ? 'font-medium text-keymex-red' : 'text-gray-700' }}">
                                {{ $type->name }}
                            </span>
                            @if($supportType == $type->id)
                                <svg class="h-4 w-4 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Clear Filters --}}
            @if($search || $status || $supportType)
                <button
                    wire:click="clearFilters"
                    type="button"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-keymex-red transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Effacer
                </button>
            @endif
        </div>
    </div>

    {{-- Orders List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Table Header --}}
        <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-100">
            <div class="grid grid-cols-12 gap-4 px-6 py-4">
                <div class="col-span-4">
                    <button wire:click="sortBy('advisor_name')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 transition-colors">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Conseiller
                        @if($sortField === 'advisor_name')
                            <svg class="h-4 w-4 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($sortDirection === 'asc')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                @endif
                            </svg>
                        @endif
                    </button>
                </div>
                <div class="col-span-3 flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Supports
                </div>
                <div class="col-span-2 flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Statut
                </div>
                <div class="col-span-2">
                    <button wire:click="sortBy('created_at')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700 transition-colors">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Date
                        @if($sortField === 'created_at')
                            <svg class="h-4 w-4 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($sortDirection === 'asc')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                @endif
                            </svg>
                        @endif
                    </button>
                </div>
                <div class="col-span-1 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                    Actions
                </div>
            </div>
        </div>

        {{-- Table Body --}}
        <div class="divide-y divide-gray-50">
            @forelse($orders as $order)
                <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gradient-to-r hover:from-red-50/30 hover:to-transparent transition-all duration-300 group">
                    {{-- Conseiller --}}
                    <div class="col-span-4">
                        <div class="flex items-center gap-4">
                            <div class="relative flex-shrink-0">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-keymex-red/10 to-red-100 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-300">
                                    <span class="text-lg font-bold text-keymex-red">
                                        {{ strtoupper(substr($order->advisor_name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center text-[10px] font-bold bg-white text-gray-500 rounded-full shadow border border-gray-100">
                                    #{{ $order->id }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('orders.show', $order) }}" wire:navigate class="text-sm font-semibold text-gray-900 hover:text-keymex-red transition-colors truncate block group-hover:text-keymex-red">
                                    {{ $order->advisor_name }}
                                </a>
                                <p class="text-xs text-gray-400 truncate mt-0.5 flex items-center gap-1">
                                    <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $order->advisor_agency ?? 'Agence non definie' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Supports --}}
                    <div class="col-span-3">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($order->items->take(3) as $item)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-gray-100 text-xs font-medium text-gray-600">
                                    {{ $item->supportType->name }}
                                    @if($item->quantity > 1)
                                        <span class="text-gray-400 font-normal">x{{ $item->quantity }}</span>
                                    @endif
                                </span>
                            @endforeach
                            @if($order->items->count() > 3)
                                <span class="inline-flex items-center px-2 py-1 rounded-lg bg-keymex-red/10 text-xs font-medium text-keymex-red">
                                    +{{ $order->items->count() - 3 }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Statut --}}
                    <div class="col-span-2">
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'],
                                'in_progress' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
                                'bat_sent' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
                                'validated' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
                                'refused' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'dot' => 'bg-rose-500'],
                                'modifications_requested' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'dot' => 'bg-orange-500'],
                                'completed' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'dot' => 'bg-teal-500'],
                            ];
                            $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $config['dot'] }} animate-pulse"></span>
                            {{ $order->status_label }}
                        </span>
                    </div>

                    {{-- Date --}}
                    <div class="col-span-2">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-700">{{ $order->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="col-span-1">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('orders.show', $order) }}"
                               wire:navigate
                               class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
                               title="Voir les details">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto h-20 w-20 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucune commande</h3>
                    <p class="text-sm text-gray-500 mb-4">Commencez par creer une nouvelle commande</p>
                    <a href="{{ route('orders.create') }}" wire:navigate
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-keymex-red to-red-600 hover:from-keymex-red-hover hover:to-red-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouvelle commande
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-transparent">
                {{ $orders->links() }}
            </div>
        @endif

        {{-- Stats Footer --}}
        @if($orders->count() > 0)
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500">
                <span>{{ $orders->total() }} commande(s) au total</span>
                <span>Page {{ $orders->currentPage() }} sur {{ $orders->lastPage() }}</span>
            </div>
        @endif
    </div>
</div>
