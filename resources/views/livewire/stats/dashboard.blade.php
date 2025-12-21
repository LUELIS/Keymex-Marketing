<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="mt-1 text-sm text-gray-500">Vue d'ensemble de l'activité marketing</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <select wire:model.live="period"
                    class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-keymex-red sm:text-sm">
                <option value="week">Cette semaine</option>
                <option value="month">Ce mois</option>
                <option value="quarter">Ce trimestre</option>
                <option value="year">Cette année</option>
            </select>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Commandes totales</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalOrders }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-gray-500">{{ $pendingOrders }} en attente</span>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">BAT envoyés</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $batsSent }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-green-600">{{ $batsValidated }} validés</span>
                    <span class="text-gray-400 mx-1">·</span>
                    <span class="text-red-600">{{ $batsRefused }} refusés</span>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Taux de validation</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold {{ $validationRate >= 80 ? 'text-green-600' : ($validationRate >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $validationRate }}%</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-gray-500">{{ $batsModifications }} demandes de modifications</span>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Temps moyen validation</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    @if($avgValidationTime)
                                        {{ $avgValidationTime }}h
                                    @else
                                        -
                                    @endif
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm text-gray-500">
                    Entre envoi et réponse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Commandes par type de support</h3>
            </div>
            <div class="p-4 sm:p-6">
                @if($ordersBySupport->count() > 0)
                    <div class="space-y-4">
                        @php
                            $maxTotal = $ordersBySupport->max('total');
                        @endphp
                        @foreach($ordersBySupport as $support)
                            <div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ $support->name }}</span>
                                    <span class="font-medium text-gray-900">{{ number_format($support->total) }}</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-keymex-red h-2 rounded-full" style="width: {{ ($support->total / $maxTotal) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Aucune donnée disponible</p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Répartition des commandes</h3>
            </div>
            <div class="p-4 sm:p-6">
                @if(count($ordersByStatus) > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $statusLabels = [
                                'pending' => ['label' => 'En attente', 'color' => 'bg-gray-500'],
                                'in_progress' => ['label' => 'En cours', 'color' => 'bg-blue-500'],
                                'bat_sent' => ['label' => 'BAT envoyé', 'color' => 'bg-yellow-500'],
                                'validated' => ['label' => 'Validé', 'color' => 'bg-green-500'],
                                'refused' => ['label' => 'Refusé', 'color' => 'bg-red-500'],
                                'modifications_requested' => ['label' => 'Modifications', 'color' => 'bg-orange-500'],
                                'completed' => ['label' => 'Terminé', 'color' => 'bg-emerald-500'],
                            ];
                        @endphp
                        @foreach($ordersByStatus as $status => $count)
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full {{ $statusLabels[$status]['color'] ?? 'bg-gray-400' }} mr-2"></span>
                                <span class="text-sm text-gray-600">{{ $statusLabels[$status]['label'] ?? $status }}</span>
                                <span class="ml-auto text-sm font-medium text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Aucune commande</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Dernières commandes</h3>
                <a href="{{ route('orders.index') }}" class="text-sm font-medium text-keymex-red hover:text-keymex-red-hover">
                    Voir tout
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="block px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="truncate">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $order->advisor_name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $order->items->pluck('supportType.name')->join(', ') }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'bat_sent' => 'bg-yellow-100 text-yellow-700',
                                        'validated' => 'bg-green-100 text-green-700',
                                        'completed' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">
                            {{ $order->created_at->diffForHumans() }}
                        </p>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        Aucune commande récente
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">BAT en attente de validation</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pendingBats as $bat)
                    <a href="{{ route('orders.show', $bat->order) }}" class="block px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="truncate">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $bat->order->advisor_name }}</p>
                                <p class="text-sm text-gray-500">
                                    Version {{ $bat->version_number }} - {{ $bat->file_name }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700">
                                    En attente
                                </span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">
                            Envoyé {{ $bat->sent_at->diffForHumans() }}
                        </p>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        Aucun BAT en attente
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
