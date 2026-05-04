@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold">{{ $pocket->name }}</h2>
            <p class="text-gray-500">Saldo: <span class="font-bold {{ $pocket->balance >= 0 ? 'text-green-600' : 'text-red-500' }}">Rp {{ number_format($pocket->balance, 0, ',', '.') }}</span></p>
        </div>
        <a href="{{ route('pockets.transactions.create', $pocket) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">+ Transaksi</a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow p-4">
        <form method="GET" action="{{ route('pockets.transactions.index', $pocket) }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="mt-1 rounded-lg border-gray-300 shadow-sm px-3 py-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="mt-1 rounded-lg border-gray-300 shadow-sm px-3 py-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="type" class="mt-1 rounded-lg border-gray-300 shadow-sm px-3 py-2 border">
                    <option value="">Semua</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Filter</button>
            <a href="{{ route('pockets.transactions.index', $pocket) }}" class="text-gray-500 hover:text-gray-700 px-4 py-2">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500">
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Keterangan</th>
                    <th class="px-6 py-3">Tipe</th>
                    <th class="px-6 py-3 text-right">Jumlah</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $t->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">{{ $t->description ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $t->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $t->type === 'income' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right font-medium {{ $t->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <a href="{{ route('pockets.transactions.edit', [$pocket, $t]) }}" class="text-indigo-500 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('pockets.transactions.destroy', [$pocket, $t]) }}" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $transactions->withQueryString()->links() }}</div>

    <a href="{{ route('pockets.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali ke Pockets</a>
</div>
@endsection
