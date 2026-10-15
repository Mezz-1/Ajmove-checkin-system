<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Dashboard — Forum Ben M'sik</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-indigo-600 tracking-wider uppercase">AJMOVE — Pôle Digitalisation</span>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard Check-in & Pointage</h1>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <button onclick="openScanner()" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=" "></path></svg>
                    Scanner QR Code
                </button>
            </div>
        </div>

        <!-- Notification Banner -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 font-medium text-sm rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pré-inscrits</p>
                <p class="text-3xl font-black text-slate-900 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Présents</p>
                <p class="text-3xl font-black text-emerald-600 mt-1">{{ $stats['present'] }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">En Attente / Absents</p>
                <p class="text-3xl font-black text-amber-600 mt-1">{{ $stats['absent'] }}</p>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.checkin.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher par Nom, Prénom, École ou Code ID..." 
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                <div class="w-full sm:w-48">
                    <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Présents uniquement</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Absents uniquement</option>
                    </select>
                </div>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-medium px-6 py-2.5 rounded-lg text-sm transition">
                    Filtrer
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500">
                            <th class="py-3.5 px-4">Code / ID</th>
                            <th class="py-3.5 px-4">Représentant(e)</th>
                            <th class="py-3.5 px-4">Établissement</th>
                            <th class="py-3.5 px-4">Filière</th>
                            <th class="py-3.5 px-4 text-center">Statut</th>
                            <th class="py-3.5 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($registrations as $row)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                                    #CONV-{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ strtoupper($row->nom) }} {{ ucfirst($row->prenom) }}</div>
                                    <div class="text-xs text-slate-500">{{ $row->email }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    {{ $row->ecole }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $row->filiere ?? 'N/A' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($row->is_present)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            Présent
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                            Absent
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <form method="POST" action="{{ route('admin.checkin.toggle', $row->id) }}" class="inline">
                                        @csrf
                                        @if($row->is_present)
                                            <button type="submit" class="text-xs bg-slate-200 hover:bg-slate-300 text-slate-800 px-3 py-1.5 rounded font-medium transition">
                                                Marquer Absent
                                            </button>
                                        @else
                                            <button type="submit" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded font-medium transition">
                                                Valider Présence
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500 text-sm">
                                    Aucun représentant trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-200">
                {{ $registrations->links() }}
            </div>
        </div>

    </div>

    <!-- QR Code Camera Modal -->
    <div id="scannerModal" class="fixed inset-0 bg-slate-900/80 hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 text-center shadow-2xl relative">
            <h3 class="text-lg font-bold text-slate-900 mb-2">Scannez le QR Code de Convocation</h3>
            <p class="text-xs text-slate-500 mb-4">Pointez la caméra vers le pass PDF sur le téléphone du représentant.</p>
            
            <div id="reader" class="overflow-hidden rounded-xl bg-black border border-slate-200"></div>

            <button onclick="closeScanner()" class="mt-4 w-full bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold py-2 rounded-lg text-sm transition">
                Fermer le scanner
            </button>
        </div>
    </div>

    <script>
        let html5QrcodeScanner = null;

        function openScanner() {
            document.getElementById('scannerModal').classList.remove('hidden');
            document.getElementById('scannerModal').classList.add('flex');

            html5QrcodeScanner = new Html5Qrcode("reader");
            html5QrcodeScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    // QR Code successfully scanned -> Redirect to scan URL!
                    window.location.href = decodedText;
                },
                (errorMessage) => {
                    // Scanning...
                }
            );
        }

        function closeScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('scannerModal').classList.add('hidden');
                    document.getElementById('scannerModal').classList.remove('flex');
                });
            } else {
                document.getElementById('scannerModal').classList.add('hidden');
                document.getElementById('scannerModal').classList.remove('flex');
            }
        }
    </script>
</body>
</html>