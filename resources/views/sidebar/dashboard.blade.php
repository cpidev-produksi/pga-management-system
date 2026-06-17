@extends('layouts.app')

@section('content')
{{-- Load Libraries --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Global Variables untuk akses Chart via Alpine --}}
<script>
    let trafficChartInstance = null;
    let deptChartInstance = null;
</script>

<div class="p-4 sm:p-6 lg:p-8" 
     x-data="{ 
        activeTab: 'traffic', 
        currentYear: '{{ $selectedYear }}', 
        currentPeriod: '{{ $period }}',
        isLoading: false,

        async updateFilter(newPeriod, newYear) {
            // 1. Update State Lokal
            if(newPeriod) this.currentPeriod = newPeriod;
            if(newYear) this.currentYear = newYear;
            
            // 2. Tampilkan Loading
            this.isLoading = true;

            try {
                // 3. Request Data ke Server (AJAX)
                // Pastikan route 'dashboard' di web.php mengarah ke DashboardController@index
                const url = `{{ route('dashboard') }}?period=${this.currentPeriod}&year=${this.currentYear}`;
                
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json();

                // 4. Update Chart Traffic
                if (trafficChartInstance) {
                    trafficChartInstance.data.labels = data.labels;
                    trafficChartInstance.data.datasets[0].data = data.guestData;
                    trafficChartInstance.data.datasets[1].data = data.contractorData;
                    trafficChartInstance.data.datasets[2].data = data.vipData;
                    trafficChartInstance.update();
                }

                // 5. Update Chart Dept
                if (deptChartInstance) {
                    deptChartInstance.data.labels = data.deptLabels;
                    deptChartInstance.data.datasets[0].data = data.deptValues;
                    deptChartInstance.update();
                    
                    // Update Judul Dept secara manual jika elemen ada
                    const titleEl = document.getElementById('deptTitle');
                    if(titleEl) titleEl.innerText = 'Top Departments (' + data.periodTitle + ')';
                }

            } catch (error) {
                console.error('Gagal mengambil data:', error);
            } finally {
                // 6. Matikan Loading setelah 500ms agar transisi halus
                setTimeout(() => { this.isLoading = false; }, 300);
            }
        }
     }">
    
    {{-- HEADER --}}
    <header class="flex items-center justify-between pb-6 border-b border-gray-200">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Visitor Dashboard</h1>
        </div>
    </header>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mt-8">
            {{-- KOLOM KIRI --}}
            <div class="xl:col-span-2">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    {{-- Total Pengunjung (BISA DIKLIK) --}}
                    <a href="{{ route('dashboard.detail.today') }}" class="block group">
                        <div id="tour-card-total" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group-hover:shadow-md group-hover:border-blue-200 transition-all duration-300 h-full cursor-pointer">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Pengunjung</p>
                                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalToday }}</h3>
                                </div>
                                <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors"><i class="fa-solid fa-users text-blue-600 text-xl"></i></div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <span class="{{ $trend == 'up' ? 'text-green-500 bg-green-50' : ($trend == 'down' ? 'text-red-500 bg-red-50' : 'text-gray-500 bg-gray-50') }} px-2 py-0.5 rounded-full font-medium text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-{{ $trend == 'up' ? 'up' : ($trend == 'down' ? 'down' : 'right') }}"></i> {{ abs($percentage) }}%
                                </span>
                                <span class="text-gray-400 ml-2">vs kemarin</span>
                            </div>
                        </div>
                    </a>

                    {{-- Sedang Di Lokasi (BISA DIKLIK) --}}
                    <a href="{{ route('dashboard.detail.onsite') }}" class="block group">
                        <div id="tour-card-onsite" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group-hover:shadow-md group-hover:border-green-200 transition-all duration-300 h-full cursor-pointer">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Sedang Di Lokasi</p>
                                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $onSiteCount }}</h3>
                                </div>
                                <div class="p-3 bg-green-50 rounded-xl group-hover:bg-green-100 transition-colors"><i class="fa-solid fa-id-card-clip text-green-600 text-xl"></i></div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                <span class="text-gray-500">{{ $guestsOnSite }} Tamu, {{ $contractorsOnSite }} Kontraktor</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('dashboard.detail.expected') }}" class="block group h-full">
                        <div id="tour-card-expected" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group-hover:shadow-md group-hover:border-orange-200 transition-all duration-300 h-full cursor-pointer relative overflow-hidden">
                            
                            {{-- Bagian Atas: Label & Icon --}}
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Akan Datang</p>
                                    
                                    <div class="flex items-center gap-3 mt-2">
                                        {{-- 1. Jumlah Total (Sisa + Telat) --}}
                                        <h3 class="text-3xl font-bold text-gray-800">{{ $expectedCount }}</h3>

                                        {{-- 2. Indikator TELAT (Hanya muncul jika ada yang telat) --}}
                                        @if(isset($lateCount) && $lateCount > 0)
                                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-red-50 border border-red-100 rounded-full animate-pulse">
                                                <i class="fa-solid fa-circle-exclamation text-red-500 text-[10px]"></i>
                                                <span class="text-xs font-bold text-red-600">Telat: {{ $lateCount }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition-colors">
                                    <i class="fa-regular fa-clock text-orange-600 text-xl"></i>
                                </div>
                            </div>

                            {{-- Bagian Bawah: Info Tamu Berikutnya --}}
                            <div class="mt-4 pt-3 border-t border-gray-50 text-sm text-gray-500 truncate">
                                @if($nextVisitor)
                                    {{-- Skenario A: Ada tamu masa depan --}}
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-orange-400 bg-orange-50 px-1.5 py-0.5 rounded font-bold">
                                            {{ $nextVisitor->visit_datetime->format('H:i') }}
                                        </span>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i> 
                                        <span class="font-medium text-gray-700 truncate" title="{{ $nextVisitor->company_name ?? $nextVisitor->name }}">
                                            {{ $nextVisitor->company_name ?? $nextVisitor->name }} 
                                        </span>
                                    </div>

                                @elseif(isset($lateCount) && $lateCount > 0)
                                    {{-- Skenario B: Tidak ada tamu masa depan, TAPI ada yang telat --}}
                                    <div class="flex items-center gap-2 text-red-500">
                                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                        <span class="italic text-xs">Menunggu {{ $lateCount }} tamu check-in.</span>
                                    </div>

                                @else
                                    {{-- Skenario C: Selesai --}}
                                    <div class="flex items-center gap-2 text-gray-400 italic">
                                        <i class="fa-solid fa-check-circle text-xs"></i>
                                        <span>Tidak ada jadwal lagi hari ini</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>

                {{-- MAIN CONTENT (Tabs & Charts) --}}
                <div id="tour-main-chart" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-8 min-h-[480px] relative">
                    
                    {{-- LOADING OVERLAY --}}
                    <div x-show="isLoading" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 bg-white/80 z-20 flex items-center justify-center rounded-2xl backdrop-blur-[1px]" 
                         style="display: none;">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-circle-notch fa-spin text-3xl text-red-500"></i>
                            <p class="text-xs text-gray-500 mt-2 font-medium">Updating data...</p>
                        </div>
                    </div>

                    {{-- TOOLBAR --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        
                        {{-- 1. TAB SWITCHER (Traffic / Logs / Dept) --}}
                        <div class="flex bg-gray-100 p-1 rounded-lg self-start">
                            <button @click="activeTab = 'traffic'" 
                                    :class="activeTab === 'traffic' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700'" 
                                    class="px-4 py-1.5 text-sm font-medium rounded-md transition-all">Traffic</button>
                            <button @click="activeTab = 'logs'" 
                                    :class="activeTab === 'logs' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700'" 
                                    class="px-4 py-1.5 text-sm font-medium rounded-md transition-all">Logs</button>
                            <button @click="activeTab = 'dept'" 
                                    :class="activeTab === 'dept' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700'" 
                                    class="px-4 py-1.5 text-sm font-medium rounded-md transition-all">Dept</button>
                        </div>

                        {{-- 2. FILTER CONTROLS (Hanya muncul di Traffic & Dept) --}}
                        <div x-show="activeTab === 'traffic' || activeTab === 'dept'" class="flex flex-wrap items-center gap-2">
                            
                            {{-- Dropdown Tahun (Fixed Style) --}}
                            <div class="relative">
                                <select x-model="currentYear" @change="updateFilter(null, $event.target.value)" 
                                        class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-xs font-medium rounded-lg focus:ring-red-500 focus:border-red-500 pl-3 pr-8 py-1.5 cursor-pointer hover:bg-gray-100 transition-colors focus:outline-none"
                                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;">
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>

                            {{-- Filter Periode (Style seperti Tab) --}}
                            <div class="flex bg-gray-100 p-1 rounded-lg">
                                <button @click="updateFilter('daily', null)" 
                                        :class="currentPeriod === 'daily' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700'" 
                                        class="px-3 py-1 text-xs font-medium rounded-md transition-all">Daily</button>
                                <button @click="updateFilter('monthly', null)" 
                                        :class="currentPeriod === 'monthly' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700'" 
                                        class="px-3 py-1 text-xs font-medium rounded-md transition-all">Monthly</button>
                                <button @click="updateFilter('yearly', null)" 
                                        :class="currentPeriod === 'yearly' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500 hover:text-gray-700'" 
                                        class="px-3 py-1 text-xs font-medium rounded-md transition-all">Yearly</button>
                            </div>
                        </div>
                    </div>

                    {{-- Legend (Traffic Only) --}}
                    <div x-show="activeTab === 'traffic'" class="flex items-center justify-end gap-4 text-xs font-medium mb-2">
                        <span class="flex items-center gap-1.5 text-gray-600"><span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> Guest</span>
                        <span class="flex items-center gap-1.5 text-gray-600"><span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> Contractor</span>
                        <span class="flex items-center gap-1.5 text-gray-600"><span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span> VIP</span>
                    </div>

                    {{-- TAB 1: TRAFFIC CHART --}}
                    <div x-show="activeTab === 'traffic'" class="relative h-80 w-full">
                        <canvas id="trafficChart"></canvas>
                    </div>

                    {{-- TAB 2: LOGS TABLE --}}
                    <div x-show="activeTab === 'logs'" style="display: none;">
                        <div class="overflow-x-auto border rounded-lg border-gray-100">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50">Time</th>
                                        <th class="px-4 py-3 bg-gray-50">Visitor</th>
                                        <th class="px-4 py-3 bg-gray-50">Host</th>
                                        <th class="px-4 py-3 bg-gray-50">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLogs as $log)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $log->updated_at->format('H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-800">{{ $log->name }}</div>
                                            <span class="px-2 py-0.5 text-[10px] rounded-full {{ strtolower($log->visit_type) == 'contractor' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">{{ $log->visit_type }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $log->intended_employee }}</td>
                                        <td class="px-4 py-3">
                                            @if($log->status)
                                                <span class="text-green-600 text-xs font-medium bg-green-50 px-2 py-1 rounded"><i class="fa-solid fa-check"></i> On-Site</span>
                                            @else
                                                <span class="text-gray-400 text-xs"><i class="fa-regular fa-clock"></i> Scheduled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($recentLogs->isEmpty())
                                <div class="p-8 text-center text-gray-400">No logs data found.</div>
                            @endif
                        </div>
                    </div>

                    {{-- TAB 3: DEPT CHART --}}
                    <div x-show="activeTab === 'dept'" style="display: none;" class="flex flex-col items-center justify-center h-full">
                         @if(empty($deptLabels))
                            <div class="text-center py-12">
                                <i class="fa-solid fa-chart-pie text-gray-200 text-5xl mb-4"></i>
                                <p class="text-gray-400">No department data for {{ $selectedYear }}.</p>
                            </div>
                         @else
                            <h4 id="deptTitle" class="text-sm font-semibold text-gray-600 mb-4 self-start w-full px-4">Top Departments ({{ ucfirst($period) }} {{ $selectedYear }})</h4>
                            <div class="relative w-full max-w-sm h-72">
                                <canvas id="deptChart"></canvas>
                            </div>
                         @endif
                    </div>

                </div>
            </div>

            {{-- SIDEBAR KANAN --}}
            <div class="space-y-8">
                {{-- JAM DIGITAL --}}
                <div id="tour-card-time" class="bg-white p-6 rounded-2xl shadow-sm text-center">
                    @php $nowInWIB = now('Asia/Jakarta'); @endphp
                    <p class="text-sm font-semibold text-gray-500">WAKTU LOKAL (WIB)</p>
                    <p id="clock-time" class="text-5xl font-bold text-gray-800 my-2">{{ $nowInWIB->format('H:i:s') }}</p>
                    <p id="clock-date" class="text-gray-500">{{ $nowInWIB->translatedFormat('l, d F Y') }}</p>
                    <div class="grid grid-cols-2 gap-4 mt-6 text-sm">
                        <div class="bg-gray-50 p-3 rounded-lg transition-colors duration-300 flex flex-col items-center justify-center text-center"
                            x-data="{ isOnline: navigator.onLine }"
                            @online.window="isOnline = true" 
                            @offline.window="isOnline = false"
                            :class="isOnline ? 'bg-green-50' : 'bg-red-50'">
                            
                            {{-- Status Text & Icon --}}
                            <p class="font-semibold flex items-center justify-center gap-2 transition-colors duration-300 w-full" 
                            :class="isOnline ? 'text-green-700' : 'text-red-600'">
                                
                                {{-- Ikon --}}
                                <i class="fa-solid text-sm" 
                                :class="isOnline ? 'fa-wifi' : 'fa-triangle-exclamation'"></i>
                                
                                {{-- Teks --}}
                                <span x-text="isOnline ? 'Online' : 'Offline'"></span>
                            </p>

                            <p class="text-xs mt-1 transition-colors duration-300" 
                            :class="isOnline ? 'text-green-600/70' : 'text-red-600/70'">
                                System Status
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="font-semibold text-gray-700">UTC+07</p>
                            <p class="text-gray-500">Time Zone</p>
                        </div>
                    </div>
                </div>

                {{-- QUICK ACCESS --}}
                <div id="tour-quick-access" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">Quick Access</h3>
                    
                    <div class="flex flex-col gap-3">
                        {{-- Tombol 1 --}}
                        @can('scan_qr')
                            <a href="{{ route('visitor.scanner') }}" class="group flex items-center justify-between p-4 bg-gray-50 hover:bg-red-50 rounded-xl border border-transparent hover:border-red-100 transition-all duration-200">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm text-gray-400 group-hover:text-red-500">
                                        <i class="fa-solid fa-qrcode text-lg"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-bold text-gray-700 group-hover:text-red-700">Scan QR Code</p>
                                        <p class="text-[10px] text-gray-400 group-hover:text-red-400">Open camera scanner</p>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-red-400 text-xs"></i>
                            </a>
                        @endcan

                        {{-- Tombol 2 --}}
                        <a href="{{ route('reservasi.create') }}" target="_blank" class="group flex items-center justify-between p-4 bg-gray-50 hover:bg-blue-50 rounded-xl border border-transparent hover:border-blue-100 transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm text-gray-400 group-hover:text-blue-500">
                                    <i class="fa-solid fa-link text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-gray-700 group-hover:text-blue-700">Visitor Link</p>
                                    <p class="text-[10px] text-gray-400 group-hover:text-blue-400">Open reservation form</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-gray-300 group-hover:text-blue-400 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. JAM DIGITAL
        const timeElement = document.getElementById('clock-time');
        const dateElement = document.getElementById('clock-date');
        setInterval(() => {
            const now = new Date();
            const timeStr = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).format(now);
            if(timeElement) timeElement.innerText = timeStr.replace(/\./g, ':');
        }, 1000);

        // 2. CHART TRAFFIC
        const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
        
        let gradientRed = ctxTraffic.createLinearGradient(0, 0, 0, 400);
        gradientRed.addColorStop(0, 'rgba(239, 68, 68, 0.5)'); gradientRed.addColorStop(1, 'rgba(239, 68, 68, 0.0)');
        let gradientBlue = ctxTraffic.createLinearGradient(0, 0, 0, 400);
        gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        // Initialize Global Variable
        trafficChartInstance = new Chart(ctxTraffic, {
            type: 'line',
            data: {
                labels: @json($labels ?? []),
                datasets: [
                    { label: 'Guest', data: @json($guestData ?? []), borderColor: '#ef4444', backgroundColor: gradientRed, borderWidth: 2, tension: 0.4, fill: true, pointRadius: 2 },
                    { label: 'Contractor', data: @json($contractorData ?? []), borderColor: '#3b82f6', backgroundColor: gradientBlue, borderWidth: 2, tension: 0.4, fill: true, pointRadius: 2 },
                    { label: 'VIP', data: @json($vipData ?? []), borderColor: '#22c55e', backgroundColor: 'rgba(34, 197, 94, 0.1)', borderWidth: 2, tension: 0.4, fill: true, pointRadius: 2 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6', borderDash: [5, 5] }, ticks: { stepSize: 1 }, border: { display: false } },
                    x: { grid: { display: false }, ticks: { maxTicksLimit: 12 }, border: { display: false } }
                }
            }
        });

        // 3. CHART DEPT
        const deptCanvas = document.getElementById('deptChart');
        if (deptCanvas) {
            // Initialize Global Variable
            deptChartInstance = new Chart(deptCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($deptLabels ?? []),
                    datasets: [{
                        data: @json($deptValues ?? []),
                        backgroundColor: ['#ef4444', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#6b7280'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    layout: { padding: 20 },
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } }
                    },
                    cutout: '65%'
                }
            });
        }

        // ==========================================
        // 4. TUTORIAL / GUIDED TOUR (DYNAMIC ROLE)
        // ==========================================
        
        window.startDashboardTour = function() {
            if (typeof window.driver === 'undefined') {
                console.error("Driver.js belum terload.");
                return;
            }

            // 1. Buat Array Kosong untuk menampung langkah-langkah
            let tourSteps = [];

            // =================================================
            // BAGIAN 1: SIDEBAR (Standard & Dynamic)
            // =================================================

            // Step 1: Sidebar Toggle (Semua User)
            tourSteps.push({ 
                element: '#tour-sidebar-toggle', 
                popover: { title: 'Minimize Sidebar', description: 'Klik tombol ini untuk memperkecil tampilan sidebar.', side: "right", align: 'start' }
            });

            // Step 2: Menu Dashboard (Semua User)
            tourSteps.push({ 
                element: '#tour-menu-dashboard', 
                popover: { title: 'Menu Dashboard', description: 'Kembali ke halaman dashboard ini kapan saja.', side: "right", align: 'center' }
            });

            // --- DYNAMIC: MENU MASTER DATA ---
            // Hanya dirender jika user punya permission 'view_master_data'
            @can('view_master_data')
            tourSteps.push({ 
                element: '#tour-menu-master', 
                popover: { 
                    title: 'Menu Master Data', 
                    description: 'Kelola data user dan departemen dari sini.', 
                    side: "right", 
                    align: 'start' 
                },
                onHighlightStarted: (element) => {
                    // Logic Auto-Open Dropdown
                    const submenu = document.getElementById('submenu-master-list');
                    const btn = document.getElementById('btn-master-toggle');
                    if (submenu && btn && window.getComputedStyle(submenu).display === 'none') {
                        btn.click();
                    }
                },
                onDeselected: (element) => {
                    // Logic Auto-Close Dropdown
                    const submenu = document.getElementById('submenu-master-list');
                    const btn = document.getElementById('btn-master-toggle');
                    if (submenu && btn && window.getComputedStyle(submenu).display !== 'none') {
                        btn.click();
                    }
                }
            });
            @endcan

            // --- DYNAMIC: MENU ROLE ACCESS (Khusus Admin) ---
            @role('Admin')
            tourSteps.push({ 
                element: '#tour-menu-roleaccess', 
                popover: { title: 'Menu Role & Access', description: 'Atur peran dan hak akses user disini.', side: "right", align: 'center' }
            });
            @endrole

            // --- DYNAMIC: MENU MONITORING ---
            @can('view_system_logs')
            tourSteps.push({ 
                element: '#tour-menu-monitoring', 
                popover: { 
                    title: 'Menu Monitoring', 
                    description: 'Pantau aktivitas login dan statistik reservasi sistem.', 
                    side: "right", 
                    align: 'start' 
                },
                onHighlightStarted: (element) => {
                    const submenu = document.getElementById('submenu-monitoring-list');
                    const btn = document.getElementById('btn-monitoring-toggle');
                    if (submenu && btn && window.getComputedStyle(submenu).display === 'none') {
                        btn.click();
                    }
                },
                onDeselected: (element) => {
                    const submenu = document.getElementById('submenu-monitoring-list');
                    const btn = document.getElementById('btn-monitoring-toggle');
                    if (submenu && btn && window.getComputedStyle(submenu).display !== 'none') {
                        btn.click();
                    }
                }
            });
            @endcan

            // Step: Menu Visitor (Semua User)
            tourSteps.push({ 
                element: '#tour-menu-visitor', 
                popover: { title: 'Menu Visitor', description: 'Akses halaman utama pengelolaan data pengunjung.', side: "right", align: 'center' }
            });

            // =================================================
            // BAGIAN 2: HEADER PROFILE (Optional)
            // =================================================
            // Jika Anda sudah menerapkan fix CSS z-index tadi, aktifkan bagian ini.
            // Jika belum/masih error shadow, bisa di-comment dulu.
            tourSteps.push({ 
                element: '#tour-profile-dropdown', 
                popover: { title: 'Profil & Akun', description: 'Klik foto profil untuk opsi logout.', side: "left", align: 'start' }
                // Tambahkan logic click jika sudah fix CSS-nya
            });

            // =================================================
            // BAGIAN 3: WIDGET DASHBOARD (Semua User)
            // =================================================
            
            // Kita push sekaligus banyak menggunakan spread operator (...) atau push satu-satu
            tourSteps.push(
                { element: '#tour-card-total', popover: { title: 'Total Pengunjung', description: 'Lihat jumlah total tamu hari ini disini.', side: "bottom", align: 'start' }},
                { element: '#tour-card-onsite', popover: { title: 'Sedang Di Lokasi', description: 'Pantau tamu yang belum check-out.', side: "bottom", align: 'start' }},
                { element: '#tour-card-expected', popover: { title: 'Akan Datang', description: 'Jadwal tamu yang akan segera tiba.', side: "bottom", align: 'start' }},
                { element: '#tour-main-chart', popover: { title: 'Statistik', description: 'Grafik kepadatan pengunjung per jam.', side: "top", align: 'center' }},
                { element: '#tour-card-time', popover: { title: 'Waktu & Status Sistem', description: 'Menampilkan jam server (WIB) dan status koneksi.', side: "left", align: 'center' }},
                { element: '#tour-quick-access', popover: { title: 'Akses Cepat', description: 'Menu pintas untuk scan QR (Khusus Security) atau link pintas visitor.', side: "left", align: 'center' }}
            );

            // 2. Jalankan Driver dengan Array yang sudah difilter
            const driverObj = window.driver({
                showProgress: true,
                animate: true,
                allowClose: true,
                doneBtnText: 'Selesai',
                nextBtnText: 'Lanjut',
                prevBtnText: 'Kembali',
                steps: tourSteps // <--- Array dinamis kita masuk disini
            });

            driverObj.drive();
        };
    });
</script>
@endsection