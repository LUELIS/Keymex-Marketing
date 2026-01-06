<div class="max-w-3xl mx-auto py-8">
    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ma Signature Email</h1>
        <p class="mt-2 text-gray-600">Generez votre signature email professionnelle KEYMEX</p>
    </div>

    {{-- Messages flash --}}
    @if (session()->has('error'))
        <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Non authentifie --}}
    @if (!$isAuthenticated)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-keymex-red/10 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-keymex-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>

                <h2 class="text-xl font-semibold text-gray-900 mb-2">Authentification requise</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Connectez-vous avec votre compte Microsoft 365 KEYMEX pour generer votre signature email personnalisee.
                </p>

                <a href="{{ route('signature.auth') }}"
                   class="inline-flex items-center gap-3 px-6 py-3 bg-[#2F2F2F] text-white font-medium rounded-xl hover:bg-[#1F1F1F] transition-colors shadow-lg">
                    {{-- Microsoft Logo --}}
                    <svg class="w-5 h-5" viewBox="0 0 23 23" fill="none">
                        <path fill="#f25022" d="M1 1h10v10H1z"/>
                        <path fill="#00a4ef" d="M1 12h10v10H1z"/>
                        <path fill="#7fba00" d="M12 1h10v10H12z"/>
                        <path fill="#ffb900" d="M12 12h10v10H12z"/>
                    </svg>
                    Se connecter avec Microsoft
                </a>
            </div>
        </div>

    {{-- Authentifie mais conseiller non trouve --}}
    @elseif ($advisorNotFound)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <h2 class="text-xl font-semibold text-gray-900 mb-2">Conseiller non trouve</h2>
                <p class="text-gray-600 mb-4">
                    Connecte en tant que: <strong>{{ $userEmail }}</strong>
                </p>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Votre email n'est pas associe a un conseiller KEYMEX. Si vous pensez qu'il s'agit d'une erreur, contactez le service informatique.
                </p>

                <button wire:click="logout"
                        class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Se deconnecter
                </button>
            </div>
        </div>

    {{-- Authentifie et signature generee --}}
    @else
        <div class="space-y-6">
            {{-- Info utilisateur --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if ($advisor && !empty($advisor['picture']))
                            <img src="{{ $advisor['picture'] }}" alt="Photo" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-keymex-red/10 flex items-center justify-center">
                                <span class="text-keymex-red font-semibold">{{ substr($advisor['firstname'] ?? 'U', 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $advisor['firstname'] ?? '' }} {{ $advisor['lastname'] ?? '' }}</p>
                            <p class="text-sm text-gray-500">{{ $userEmail }}</p>
                        </div>
                    </div>
                    <button wire:click="logout"
                            class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Deconnecter
                    </button>
                </div>
            </div>

            {{-- Erreur si pas de signature --}}
            @if ($error)
                <div class="rounded-lg bg-red-50 p-4 border border-red-200">
                    <p class="text-sm text-red-700">{{ $error }}</p>
                </div>
            @endif

            {{-- Preview signature --}}
            @if ($signatureHtml)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Apercu de votre signature</h2>
                    </div>

                    <div class="p-6">
                        {{-- Signature preview --}}
                        <div id="signature-preview" class="bg-white border border-gray-200 rounded-lg p-4">
                            {!! $signatureHtml !!}
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-wrap gap-3 justify-center">
                        <button onclick="copySignatureHTML()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-keymex-red text-white font-medium rounded-xl hover:bg-keymex-red/90 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                            Copier le HTML
                        </button>

                        <button onclick="downloadSignatureHTML()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Telecharger .html
                        </button>

                        <button onclick="copySignatureRich()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Copier (formatte)
                        </button>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Comment utiliser votre signature
                    </h3>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800">
                        <li>Cliquez sur <strong>"Copier (formatte)"</strong> pour copier la signature avec sa mise en forme</li>
                        <li>Ouvrez les parametres de signature dans votre client mail (Outlook, Gmail, etc.)</li>
                        <li>Collez la signature dans l'editeur</li>
                        <li>Enregistrez vos modifications</li>
                    </ol>
                </div>
            @endif
        </div>
    @endif

    {{-- Notification toast --}}
    <div id="toast" class="fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-3 rounded-lg shadow-lg transform translate-y-full opacity-0 transition-all duration-300">
        <span id="toast-message"></span>
    </div>
</div>

@push('scripts')
<script>
    // Copier le HTML brut
    function copySignatureHTML() {
        const html = document.getElementById('signature-preview').innerHTML;
        navigator.clipboard.writeText(html).then(() => {
            showToast('HTML copie dans le presse-papier !');
        }).catch(err => {
            showToast('Erreur lors de la copie', true);
        });
    }

    // Copier avec formatage (rich text)
    function copySignatureRich() {
        const preview = document.getElementById('signature-preview');
        const range = document.createRange();
        range.selectNodeContents(preview);

        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        try {
            document.execCommand('copy');
            showToast('Signature copiee avec formatage !');
        } catch (err) {
            showToast('Erreur lors de la copie', true);
        }

        selection.removeAllRanges();
    }

    // Telecharger le fichier HTML
    function downloadSignatureHTML() {
        const html = `<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Signature KEYMEX</title>
</head>
<body>
${document.getElementById('signature-preview').innerHTML}
</body>
</html>`;

        const blob = new Blob([html], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'signature-keymex.html';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        showToast('Fichier telecharge !');
    }

    // Afficher une notification toast
    function showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        toastMessage.textContent = message;
        toast.classList.toggle('bg-red-600', isError);
        toast.classList.toggle('bg-gray-900', !isError);

        toast.classList.remove('translate-y-full', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }, 3000);
    }
</script>
@endpush
