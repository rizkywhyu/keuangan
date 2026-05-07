@extends('layouts.app')
@section('content')
<div class="space-y-6">
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Total Saldo</p>
            <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Total Pengeluaran ({{ $startDate && $endDate ? "$startDate - $endDate" : $year }})</p>
            <p class="text-2xl font-bold text-red-500">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Jumlah Pocket</p>
            <p class="text-2xl font-bold text-green-600">{{ $pockets->count() }}</p>
        </div>
    </div>

    {{-- Date Filter --}}
    <div class="bg-white rounded-xl shadow p-6">
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tahun</label>
                <select name="year" class="mt-1 rounded-lg border-gray-300 shadow-sm px-3 py-2 border">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="mt-1 rounded-lg border-gray-300 shadow-sm px-3 py-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="mt-1 rounded-lg border-gray-300 shadow-sm px-3 py-2 border">
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Filter</button>
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2">Reset</a>
        </form>
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Grafik Pemasukan & Pengeluaran Bulanan</h3>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    {{-- Pocket Balances --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Saldo per Pocket</h3>
            <a href="{{ route('pockets.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">+ Pocket Baru</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($pockets as $pocket)
                <a href="{{ route('pockets.transactions.index', $pocket) }}" class="block p-4 border rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition">
                    <p class="font-medium">{{ $pocket->name }}</p>
                    <p class="text-lg font-bold {{ $pocket->balance >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        Rp {{ number_format($pocket->balance, 0, ',', '.') }}
                    </p>
                    @if($pocket->description)
                        <p class="text-xs text-gray-400 mt-1">{{ $pocket->description }}</p>
                    @endif
                </a>
            @empty
                <p class="text-gray-400 col-span-3">Belum ada pocket. Buat pocket pertamamu!</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Transaksi Terakhir</h3>
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-end gap-3 mb-4">
            <input type="hidden" name="year" value="{{ $year }}">
            @if($startDate) <input type="hidden" name="start_date" value="{{ $startDate }}"> @endif
            @if($endDate) <input type="hidden" name="end_date" value="{{ $endDate }}"> @endif
            <div>
                <label class="block text-xs text-gray-500">Pocket</label>
                <select name="filter_pocket" class="rounded-lg border-gray-300 shadow-sm px-3 py-1.5 border text-sm">
                    <option value="">Semua</option>
                    @foreach($pockets as $pocket)
                        <option value="{{ $pocket->id }}" {{ request('filter_pocket') == $pocket->id ? 'selected' : '' }}>{{ $pocket->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Tipe</label>
                <select name="filter_type" class="rounded-lg border-gray-300 shadow-sm px-3 py-1.5 border text-sm">
                    <option value="">Semua</option>
                    <option value="income" {{ request('filter_type') == 'income' ? 'selected' : '' }}>Masuk</option>
                    <option value="expense" {{ request('filter_type') == 'expense' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Dari</label>
                <input type="date" name="filter_start" value="{{ request('filter_start') }}" class="rounded-lg border-gray-300 shadow-sm px-3 py-1.5 border text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500">Sampai</label>
                <input type="date" name="filter_end" value="{{ request('filter_end') }}" class="rounded-lg border-gray-300 shadow-sm px-3 py-1.5 border text-sm">
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
            <a href="{{ route('dashboard', ['year' => $year, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="text-gray-500 hover:text-gray-700 text-sm">Reset</a>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="pb-2">Tanggal</th>
                        <th class="pb-2">Pocket</th>
                        <th class="pb-2">Keterangan</th>
                        <th class="pb-2">Tipe</th>
                        <th class="pb-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $t)
                        <tr class="border-b">
                            <td class="py-2">{{ $t->date->format('d/m/Y') }}</td>
                            <td>{{ $t->pocket->name }}</td>
                            <td>{{ $t->description ?? '-' }}</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs {{ $t->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $t->type === 'income' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="text-right font-medium {{ $t->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                                {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $recentTransactions->links() }}
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [
            {
                label: 'Pemasukan',
                data: @json($incomeData),
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderRadius: 4,
            },
            {
                label: 'Pengeluaran',
                data: @json($expenseData),
                backgroundColor: 'rgba(239, 68, 68, 0.7)',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + v.toLocaleString('id-ID')
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: ctx => ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>
@endsection
