<div>
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Statistiques</h1>
            <p class="mt-1 text-sm text-gray-500">Vue d'ensemble de l'activite marketing</p>
        </div>
    </div>

    {{-- Filtres de dates --}}
    <div class="mt-6 bg-white shadow rounded-lg p-4">
        <div class="flex flex-wrap items-end gap-4">
            {{-- Presets rapides --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select wire:model.live="period"
                        class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm">
                    <option value="today">Aujourd'hui</option>
                    <option value="yesterday">Hier</option>
                    <option value="week">Cette semaine</option>
                    <option value="last_week">Semaine derniere</option>
                    <option value="month">Ce mois</option>
                    <option value="last_month">Mois dernier</option>
                    <option value="quarter">Ce trimestre</option>
                    <option value="year">Cette annee</option>
                    <option value="last_year">Annee derniere</option>
                    <option value="custom">Personnalise</option>
                </select>
            </div>

            {{-- Date de debut --}}
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Du</label>
                <input type="date"
                       wire:model.live="dateFrom"
                       wire:change="applyCustomDates"
                       class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm" />
            </div>

            {{-- Date de fin --}}
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                <input type="date"
                       wire:model.live="dateTo"
                       wire:change="applyCustomDates"
                       class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm" />
            </div>
        </div>

        @if($dateFrom && $dateTo)
            <p class="mt-2 text-xs text-gray-500">
                Periode selectionnee : du {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
            </p>
        @endif
    </div>

    {{-- KPIs principaux --}}
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Commandes --}}
        <a href="{{ route('orders.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Commandes</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $totalOrders }}</dd>
                            <dd class="text-xs text-gray-400">{{ $ordersCompleted }} terminees</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </a>

        {{-- BAT envoyes --}}
        <a href="{{ route('standalone-bats.index') }}" class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">BAT envoyes</dt>
                            <dd class="text-2xl font-semibold text-indigo-600">{{ $totalBatsSent }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </a>

        {{-- BAT en attente --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">BAT en attente</dt>
                            <dd class="text-2xl font-semibold text-yellow-600">{{ $batsPending }}</dd>
                            <dd class="text-xs text-gray-400">En cours de validation</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Taux de validation --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Taux de validation</dt>
                            <dd class="text-2xl font-semibold {{ $validationRate >= 80 ? 'text-green-600' : ($validationRate >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $validationRate }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques BAT detaillees --}}
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        {{-- Valides --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">BAT valides</p>
                        <p class="text-2xl font-semibold text-green-600">{{ $batsValidated }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-green-600">{{ $validationRate }}%</p>
                        <p class="text-xs text-gray-400">du total</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modifications demandees --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-orange-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Modifications demandees</p>
                        <p class="text-2xl font-semibold text-orange-600">{{ $batsModifications }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-orange-600">{{ $modificationsRate }}%</p>
                        <p class="text-xs text-gray-400">du total</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Refuses --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">BAT refuses</p>
                        <p class="text-2xl font-semibold text-red-600">{{ $batsRefused }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-red-600">{{ $refusalRate }}%</p>
                        <p class="text-xs text-gray-400">du total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableaux de repartition --}}
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Par type de support --}}
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Par type de support</h3>
                <p class="mt-1 text-sm text-gray-500">Repartition des commandes et BAT</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de support</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Commandes</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantite</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">BAT</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($supportTypeStats as $stat)
                            @if($stat['orders_count'] > 0 || $stat['bats_count'] > 0)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $stat['name'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                    @if($stat['orders_count'] > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $stat['orders_count'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-600">
                                    @if($stat['orders_quantity'] > 0)
                                        {{ number_format($stat['orders_quantity'], 0, ',', ' ') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                    @if($stat['bats_count'] > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $stat['bats_count'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Aucune donnee pour cette periode
                                </td>
                            </tr>
                        @endforelse
                        @php
                            $totalOrders = collect($supportTypeStats)->sum('orders_count');
                            $totalQuantity = collect($supportTypeStats)->sum('orders_quantity');
                            $totalBats = collect($supportTypeStats)->sum('bats_count');
                        @endphp
                        @if($totalOrders > 0 || $totalBats > 0)
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Total</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-blue-700">{{ $totalOrders }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-700">{{ number_format($totalQuantity, 0, ',', ' ') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-indigo-700">{{ $totalBats }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Par categorie --}}
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Par categorie</h3>
                <p class="mt-1 text-sm text-gray-500">Repartition des commandes et BAT</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categorie</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Commandes</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">BAT</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categoryStats as $stat)
                            @if($stat['orders_count'] > 0 || $stat['bats_count'] > 0)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $stat['name'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                    @if($stat['orders_count'] > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $stat['orders_count'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                    @if($stat['bats_count'] > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $stat['bats_count'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Aucune donnee pour cette periode
                                </td>
                            </tr>
                        @endforelse
                        @php
                            $totalCatOrders = collect($categoryStats)->sum('orders_count');
                            $totalCatBats = collect($categoryStats)->sum('bats_count');
                        @endphp
                        @if($totalCatOrders > 0 || $totalCatBats > 0)
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Total</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-blue-700">{{ $totalCatOrders }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-indigo-700">{{ $totalCatBats }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Statuts detailles --}}
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Statuts des commandes --}}
        @if(count($ordersByStatus) > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Statuts des commandes</h3>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @php
                        $statusLabels = [
                            'pending' => ['label' => 'En attente', 'color' => 'gray'],
                            'in_progress' => ['label' => 'En cours', 'color' => 'blue'],
                            'bat_sent' => ['label' => 'BAT envoye', 'color' => 'yellow'],
                            'validated' => ['label' => 'Valide', 'color' => 'green'],
                            'refused' => ['label' => 'Refuse', 'color' => 'red'],
                            'modifications_requested' => ['label' => 'Modifications', 'color' => 'orange'],
                            'completed' => ['label' => 'Termine', 'color' => 'emerald'],
                        ];
                        $totalStatusOrders = array_sum($ordersByStatus);
                    @endphp
                    @foreach($ordersByStatus as $status => $count)
                        @php
                            $info = $statusLabels[$status] ?? ['label' => $status, 'color' => 'gray'];
                            $percent = $totalStatusOrders > 0 ? round(($count / $totalStatusOrders) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-gray-700">{{ $info['label'] }}</span>
                                <span class="text-gray-500">{{ $count }} ({{ $percent }}%)</span>
                            </div>
                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-{{ $info['color'] }}-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Statuts des BAT --}}
        @if(count($batsByStatus) > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Statuts des BAT</h3>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @php
                        $batStatusLabels = [
                            'draft' => ['label' => 'Brouillon', 'color' => 'gray'],
                            'sent' => ['label' => 'Envoye', 'color' => 'yellow'],
                            'validated' => ['label' => 'Valide', 'color' => 'green'],
                            'refused' => ['label' => 'Refuse', 'color' => 'red'],
                            'modifications_requested' => ['label' => 'Modifications', 'color' => 'orange'],
                            'converted' => ['label' => 'Converti', 'color' => 'violet'],
                        ];
                        $totalStatusBats = array_sum($batsByStatus);
                    @endphp
                    @foreach($batsByStatus as $status => $count)
                        @php
                            $info = $batStatusLabels[$status] ?? ['label' => $status, 'color' => 'gray'];
                            $percent = $totalStatusBats > 0 ? round(($count / $totalStatusBats) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-gray-700">{{ $info['label'] }}</span>
                                <span class="text-gray-500">{{ $count }} ({{ $percent }}%)</span>
                            </div>
                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-{{ $info['color'] }}-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
