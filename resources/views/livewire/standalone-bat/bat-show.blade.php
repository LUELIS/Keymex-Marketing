<div class="space-y-6">
    {{-- Header --}}
    <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="relative px-6 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    {{-- Icon with gradient --}}
                    <div class="relative flex-shrink-0">
                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-keymex-red/10 to-red-100 flex items-center justify-center shadow-lg">
                            @if(str_contains($bat->file_mime ?? '', 'pdf'))
                                <svg class="h-8 w-8 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            @else
                                <svg class="h-8 w-8 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="absolute -top-1 -right-1 h-7 w-7 flex items-center justify-center text-xs font-bold bg-white text-keymex-red rounded-full shadow-md border-2 border-keymex-red/20">
                            #{{ $bat->id }}
                        </span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $bat->title ?: 'BAT #' . $bat->id }}
                        </h1>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm text-gray-500 flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $bat->created_at->format('d/m/Y a H:i') }}
                            </span>
                            @if($bat->creator)
                                <span class="text-sm text-gray-400">par {{ $bat->creator->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('standalone-bats.index') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm font-medium">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 p-4 border border-emerald-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-2xl bg-gradient-to-r from-rose-50 to-red-50 p-4 border border-rose-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Status Alerts --}}
    @if($bat->status === 'modifications_requested' && $bat->client_comment)
        <div class="rounded-2xl bg-gradient-to-r from-orange-50 via-amber-50 to-orange-50 border-2 border-orange-200 p-6 shadow-sm">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-orange-800 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                        Modifications demandees par le client
                    </h3>
                    <p class="text-sm text-orange-700 mt-2 bg-white/50 rounded-lg p-3 border border-orange-100">{{ $bat->client_comment }}</p>
                    <p class="text-xs text-orange-500 mt-3 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Recu le {{ $bat->responded_at?->format('d/m/Y a H:i') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($bat->status === 'refused' && $bat->client_comment)
        <div class="rounded-2xl bg-gradient-to-r from-rose-50 via-red-50 to-rose-50 border-2 border-rose-200 p-6 shadow-sm">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-rose-800 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                        BAT refuse par le client
                    </h3>
                    <p class="text-sm text-rose-700 mt-2 bg-white/50 rounded-lg p-3 border border-rose-100">{{ $bat->client_comment }}</p>
                    <p class="text-xs text-rose-500 mt-3 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Recu le {{ $bat->responded_at?->format('d/m/Y a H:i') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($bat->status === 'validated')
        <div class="rounded-2xl bg-gradient-to-r from-emerald-50 via-green-50 to-emerald-50 border-2 border-emerald-200 p-6 shadow-sm">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-emerald-800 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        BAT valide par le client
                    </h3>
                    @if($bat->client_comment)
                        <p class="text-sm text-emerald-700 mt-2 bg-white/50 rounded-lg p-3 border border-emerald-100">{{ $bat->client_comment }}</p>
                    @endif
                    <p class="text-xs text-emerald-500 mt-3 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Valide le {{ $bat->responded_at?->format('d/m/Y a H:i') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - File Preview --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Current File --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-keymex-red/10 to-red-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $bat->file_name }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Fichier actuel
                            </p>
                        </div>
                    </div>
                    <a href="{{ $bat->file_url }}" download="{{ $bat->file_name }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-keymex-red to-red-600 rounded-xl hover:from-keymex-red-hover hover:to-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Telecharger
                    </a>
                </div>

                {{-- Preview --}}
                <div class="bg-gradient-to-br from-gray-900 to-gray-800">
                    @if(str_starts_with($bat->file_mime, 'image/'))
                        <div class="flex items-center justify-center p-6 min-h-[450px]">
                            <img src="{{ $bat->file_url }}" alt="{{ $bat->file_name }}"
                                 class="max-w-full h-auto max-h-[500px] rounded-xl shadow-2xl object-contain ring-1 ring-white/10">
                        </div>
                    @else
                        <iframe src="{{ $bat->file_url }}#toolbar=0&navpanes=0&view=FitH"
                                class="w-full h-[500px] border-0"></iframe>
                    @endif
                </div>
            </div>

            {{-- Upload New File --}}
            @if(in_array($bat->status, ['draft', 'sent', 'modifications_requested', 'refused']))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Mettre a jour le fichier</h2>
                        </div>
                        @if(!$showUploadForm)
                            <button type="button" wire:click="toggleUploadForm"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-100 transition-all duration-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Nouveau fichier
                            </button>
                        @endif
                    </div>

                    <div class="p-6">
                        @if($showUploadForm)
                            <form wire:submit="updateFile" class="space-y-4">
                                @if($newFile)
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $newFile->getClientOriginalName() }}</p>
                                                <p class="text-sm text-gray-500">{{ number_format($newFile->getSize() / 1024, 2) }} Ko</p>
                                            </div>
                                        </div>
                                        <button type="button" wire:click="$set('newFile', null)"
                                                class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-200">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <label for="newFile"
                                           class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-keymex-red hover:bg-red-50/30 transition-all duration-300 group">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-12 w-12 rounded-xl bg-gray-100 group-hover:bg-keymex-red/10 flex items-center justify-center mb-3 transition-colors">
                                                <svg class="w-6 h-6 text-gray-400 group-hover:text-keymex-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold text-keymex-red">Cliquez pour selectionner</span> un nouveau fichier
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">PDF, JPG ou PNG (max. 20 Mo)</p>
                                        </div>
                                        <input type="file" wire:model="newFile" id="newFile" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    </label>

                                    <div wire:loading wire:target="newFile" class="text-center py-4">
                                        <div class="inline-flex items-center gap-2 text-sm text-keymex-red bg-red-50 px-4 py-2 rounded-xl">
                                            <svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Telechargement en cours...
                                        </div>
                                    </div>
                                @endif

                                @error('newFile')
                                    <p class="text-sm text-rose-600 bg-rose-50 px-3 py-2 rounded-lg">{{ $message }}</p>
                                @enderror

                                <div class="flex gap-3">
                                    <button type="button" wire:click="toggleUploadForm"
                                            class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-medium">
                                        Annuler
                                    </button>
                                    <button type="submit" wire:loading.attr="disabled"
                                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-keymex-red to-red-600 text-white rounded-xl hover:from-keymex-red-hover hover:to-red-700 transition-all duration-200 font-medium inline-flex items-center justify-center gap-2 shadow-sm">
                                        <span wire:loading.remove wire:target="updateFile">Mettre a jour</span>
                                        <span wire:loading wire:target="updateFile" class="inline-flex items-center gap-2">
                                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Mise a jour...
                                        </span>
                                    </button>
                                </div>

                                <p class="text-xs text-gray-500 text-center bg-gray-50 rounded-lg py-2">
                                    Le fichier actuel sera remplace et le BAT repassera en brouillon.
                                </p>
                            </form>
                        @else
                            <p class="text-sm text-gray-600">
                                Vous pouvez remplacer le fichier actuel par une nouvelle version.
                                @if($bat->status === 'modifications_requested')
                                    <span class="block mt-2 font-medium text-orange-600 bg-orange-50 px-3 py-2 rounded-lg">
                                        Suite aux modifications demandees, mettez a jour le fichier puis renvoyez-le.
                                    </span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- History Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Historique des evenements
                    </h3>
                </div>
                <div class="p-6">
                    @if($bat->logs->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($bat->logs as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-gradient-to-b from-gray-200 to-gray-100" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex items-start space-x-4">
                                                <div>
                                                    @php
                                                        $colors = [
                                                            'gray' => 'bg-gradient-to-br from-gray-100 to-gray-200 text-gray-600 ring-gray-100',
                                                            'blue' => 'bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 ring-blue-100',
                                                            'green' => 'bg-gradient-to-br from-emerald-100 to-green-100 text-emerald-600 ring-emerald-100',
                                                            'red' => 'bg-gradient-to-br from-rose-100 to-red-100 text-rose-600 ring-rose-100',
                                                            'orange' => 'bg-gradient-to-br from-orange-100 to-amber-100 text-orange-600 ring-orange-100',
                                                            'purple' => 'bg-gradient-to-br from-purple-100 to-violet-100 text-purple-600 ring-purple-100',
                                                            'yellow' => 'bg-gradient-to-br from-yellow-100 to-amber-100 text-yellow-600 ring-yellow-100',
                                                            'emerald' => 'bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-600 ring-emerald-100',
                                                        ];
                                                        $colorClass = $colors[$log->event_color] ?? $colors['gray'];
                                                    @endphp
                                                    <span class="h-10 w-10 rounded-xl flex items-center justify-center ring-4 ring-white shadow-sm {{ $colorClass }}">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $log->event_icon }}"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0 bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                                                    <div class="flex items-center justify-between gap-4">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            {{ $log->event_label }}
                                                        </p>
                                                        <time class="text-xs text-gray-500 bg-white px-2 py-1 rounded-lg border border-gray-100 flex-shrink-0">
                                                            {{ $log->created_at->format('d/m/Y H:i') }}
                                                        </time>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        @if($log->actor_type === 'client')
                                                            <span class="inline-flex items-center gap-1 text-amber-600">
                                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                Client : {{ $log->actor_name }}
                                                            </span>
                                                        @elseif($log->actor_type === 'staff')
                                                            <span class="inline-flex items-center gap-1 text-blue-600">
                                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $log->actor_name }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400">Systeme automatique</span>
                                                        @endif
                                                    </p>
                                                    @if($log->comment)
                                                        <div class="mt-3 p-3 bg-white rounded-lg border border-gray-100">
                                                            <p class="text-sm text-gray-700 italic">"{{ $log->comment }}"</p>
                                                        </div>
                                                    @endif
                                                    @if($log->old_file_name && $log->new_file_name)
                                                        <div class="mt-3 flex items-center gap-2 text-xs">
                                                            <span class="text-gray-400 line-through">{{ $log->old_file_name }}</span>
                                                            <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                            </svg>
                                                            <span class="font-medium text-gray-700">{{ $log->new_file_name }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">Aucun historique disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column - Info & Actions --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Statut actuel
                    </h3>
                </div>
                <div class="p-5">
                    @php
                        $statusConfig = [
                            'draft' => ['bg' => 'bg-gradient-to-br from-gray-100 to-gray-200', 'text' => 'text-gray-700', 'badge' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                            'sent' => ['bg' => 'bg-gradient-to-br from-amber-100 to-yellow-200', 'text' => 'text-amber-700', 'badge' => 'bg-amber-50 text-amber-700', 'dot' => 'bg-amber-400', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                            'validated' => ['bg' => 'bg-gradient-to-br from-emerald-100 to-green-200', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-50 text-emerald-700', 'dot' => 'bg-emerald-400', 'icon' => 'M5 13l4 4L19 7'],
                            'refused' => ['bg' => 'bg-gradient-to-br from-rose-100 to-red-200', 'text' => 'text-rose-700', 'badge' => 'bg-rose-50 text-rose-700', 'dot' => 'bg-rose-400', 'icon' => 'M6 18L18 6M6 6l12 12'],
                            'modifications_requested' => ['bg' => 'bg-gradient-to-br from-orange-100 to-amber-200', 'text' => 'text-orange-700', 'badge' => 'bg-orange-50 text-orange-700', 'dot' => 'bg-orange-400', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                            'converted' => ['bg' => 'bg-gradient-to-br from-purple-100 to-violet-200', 'text' => 'text-purple-700', 'badge' => 'bg-purple-50 text-purple-700', 'dot' => 'bg-purple-400', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ];
                        $config = $statusConfig[$bat->status] ?? $statusConfig['draft'];
                    @endphp
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl {{ $config['bg'] }} flex items-center justify-center shadow-sm">
                            <svg class="h-7 w-7 {{ $config['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold {{ $config['badge'] }}">
                                <span class="h-2 w-2 rounded-full {{ $config['dot'] }} animate-pulse"></span>
                                {{ $bat->status_label }}
                            </span>
                            @if($bat->sent_at)
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Envoye le {{ $bat->sent_at->format('d/m/Y a H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Advisor Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Conseiller
                    </h3>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-keymex-red to-red-600 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                            {{ strtoupper(substr($bat->advisor_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-900 truncate">{{ $bat->advisor_name }}</p>
                            <p class="text-sm text-gray-500 truncate flex items-center gap-1 mt-0.5">
                                <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $bat->advisor_email }}
                            </p>
                            @if($bat->advisor_agency)
                                <p class="text-xs text-gray-400 truncate flex items-center gap-1 mt-1">
                                    <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $bat->advisor_agency }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Print Details Card --}}
            @if($bat->supportType || $bat->format || $bat->category || $bat->grammage || $bat->price || $bat->delivery_time || $bat->quantity)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Informations d'impression
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @if($bat->supportType)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-keymex-red"></span>
                                    Type de support
                                </span>
                                <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded-lg">{{ $bat->supportType->name }}</span>
                            </div>
                        @endif
                        @if($bat->format)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    Format
                                </span>
                                <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded-lg">{{ $bat->format->name }}</span>
                            </div>
                        @endif
                        @if($bat->category)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Categorie
                                </span>
                                <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded-lg">{{ $bat->category->name }}</span>
                            </div>
                        @endif
                        @if($bat->grammage)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    Grammage
                                </span>
                                <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded-lg">{{ $bat->grammage }}</span>
                            </div>
                        @endif
                        @if($bat->quantity)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                    Quantite
                                </span>
                                <span class="text-sm font-semibold text-gray-900 bg-indigo-50 text-indigo-700 px-2 py-1 rounded-lg">{{ number_format($bat->quantity, 0, ',', ' ') }}</span>
                            </div>
                        @endif
                        @if($bat->price)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-keymex-red"></span>
                                    Prix HT
                                </span>
                                <span class="text-sm font-bold text-keymex-red bg-red-50 px-3 py-1 rounded-lg">{{ number_format($bat->price, 2, ',', ' ') }} EUR</span>
                            </div>
                        @endif
                        @if($bat->delivery_time)
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                                    Delai
                                </span>
                                <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded-lg">{{ $bat->delivery_time }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Description --}}
            @if($bat->description)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Description
                        </h3>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $bat->description }}</p>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Actions rapides
                    </h3>
                </div>
                <div class="p-5 space-y-3">
                    @if($bat->status === 'draft')
                        <button type="button" wire:click="sendBat" wire:loading.attr="disabled"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Envoyer pour validation
                        </button>
                    @endif

                    @if(in_array($bat->status, ['sent', 'validated', 'refused', 'modifications_requested']))
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $bat->validation_url }}').then(() => alert('Lien copie!'))"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                            Copier le lien de validation
                        </button>

                        @if(in_array($bat->status, ['sent', 'refused', 'modifications_requested']))
                            <button type="button" wire:click="resendEmail" wire:loading.attr="disabled" wire:target="resendEmail"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-amber-400 to-orange-500 text-white rounded-xl hover:from-amber-500 hover:to-orange-600 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                <svg wire:loading.remove wire:target="resendEmail" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <svg wire:loading wire:target="resendEmail" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="resendEmail">Renvoyer l'email</span>
                                <span wire:loading wire:target="resendEmail">Envoi...</span>
                            </button>
                        @endif

                        <button type="button" wire:click="regenerateToken" wire:loading.attr="disabled"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 font-medium">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Regenerer le lien
                        </button>
                    @endif

                    @if($bat->canBeConvertedToOrder())
                        <button type="button" wire:click="openConvertModal"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-purple-500 to-violet-600 text-white rounded-xl hover:from-purple-600 hover:to-violet-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Convertir en commande
                        </button>
                    @endif

                    @if($bat->order_id)
                        <a href="{{ route('orders.show', $bat->order_id) }}" wire:navigate
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-keymex-red to-red-600 text-white rounded-xl hover:from-keymex-red-hover hover:to-red-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Voir la commande #{{ $bat->order_id }}
                        </a>
                    @endif

                    @if($bat->token_expires_at)
                        <div class="pt-2 text-center">
                            <p class="text-xs text-gray-400 flex items-center justify-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Lien expire le {{ $bat->token_expires_at->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Convert to Order Modal --}}
    @if($showConvertModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" wire:click="closeConvertModal"></div>

                {{-- Modal panel --}}
                <div class="relative z-10 bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full mx-4 sm:mx-0">
                    <form wire:submit="convertToOrder">
                        <div class="bg-white px-6 pt-6 pb-4">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex-shrink-0 h-14 w-14 rounded-2xl bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center shadow-lg">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                                        Convertir en commande
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        Ce BAT sera transforme en commande
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                {{-- Order Date --}}
                                <div>
                                    <label for="orderedAt" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Date de commande <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="orderedAt" wire:model="orderedAt"
                                           class="w-full rounded-xl border-gray-200 shadow-sm focus:border-keymex-red focus:ring-keymex-red">
                                    @error('orderedAt')
                                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Expected Delivery Date --}}
                                <div>
                                    <label for="expectedDeliveryAt" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Date de livraison prevue
                                    </label>
                                    <input type="date" id="expectedDeliveryAt" wire:model="expectedDeliveryAt"
                                           class="w-full rounded-xl border-gray-200 shadow-sm focus:border-keymex-red focus:ring-keymex-red">
                                    @error('expectedDeliveryAt')
                                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Quantity --}}
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Quantite <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" id="quantity" wire:model="quantity" min="1"
                                           class="w-full rounded-xl border-gray-200 shadow-sm focus:border-keymex-red focus:ring-keymex-red"
                                           placeholder="Ex: 100">
                                    @error('quantity')
                                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Price HT --}}
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Prix HT (EUR)
                                    </label>
                                    <input type="number" id="price" wire:model="price" step="0.01" min="0"
                                           class="w-full rounded-xl border-gray-200 shadow-sm focus:border-keymex-red focus:ring-keymex-red"
                                           placeholder="Ex: 150.00">
                                    @error('price')
                                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Delivery Time --}}
                                <div>
                                    <label for="deliveryTime" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Delai de livraison
                                    </label>
                                    <input type="text" id="deliveryTime" wire:model="deliveryTime"
                                           class="w-full rounded-xl border-gray-200 shadow-sm focus:border-keymex-red focus:ring-keymex-red"
                                           placeholder="Ex: 5 jours ouvres">
                                    @error('deliveryTime')
                                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Summary --}}
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl p-4 space-y-2 border border-gray-100">
                                    <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Resume du BAT
                                    </h4>
                                    <div class="text-sm text-gray-600 space-y-1.5">
                                        <p class="flex items-center gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-keymex-red"></span>
                                            <span class="font-medium">Conseiller :</span> {{ $bat->advisor_name }}
                                        </p>
                                        @if($bat->supportType)
                                            <p class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                                <span class="font-medium">Support :</span> {{ $bat->supportType->name }}
                                            </p>
                                        @endif
                                        @if($bat->format)
                                            <p class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                <span class="font-medium">Format :</span> {{ $bat->format->name }}
                                            </p>
                                        @endif
                                        @if($bat->title)
                                            <p class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                                                <span class="font-medium">Titre :</span> {{ $bat->title }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex gap-3 border-t border-gray-100">
                            <button type="button" wire:click="closeConvertModal"
                                    class="flex-1 px-4 py-2.5 border-2 border-gray-200 text-gray-700 rounded-xl hover:bg-gray-100 transition-all duration-200 font-medium">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-purple-500 to-violet-600 text-white rounded-xl hover:from-purple-600 hover:to-violet-700 transition-all duration-200 font-medium inline-flex items-center justify-center gap-2 shadow-sm">
                                <span wire:loading.remove wire:target="convertToOrder">Creer la commande</span>
                                <span wire:loading wire:target="convertToOrder" class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Creation...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
