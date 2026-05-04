@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-6">{{ isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi' }} - {{ $pocket->name }}</h2>

    <form method="POST"
        action="{{ isset($transaction) ? route('pockets.transactions.update', [$pocket, $transaction]) : route('pockets.transactions.store', $pocket) }}"
        class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        @if(isset($transaction)) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700">Tipe</label>
            <select name="type" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-4 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
                <option value="income" {{ old('type', $transaction->type ?? '') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ old('type', $transaction->type ?? '') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount', $transaction->amount ?? '') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-4 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" name="date" value="{{ old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d')) }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-4 py-2 border focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan (opsional)</label>
            <input type="text" name="description" value="{{ old('description', $transaction->description ?? '') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-4 py-2 border focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Contoh: Makan siang, Gaji, dll">
        </div>
        <div class="flex space-x-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                {{ isset($transaction) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('pockets.transactions.index', $pocket) }}" class="px-6 py-2 rounded-lg border hover:bg-gray-50">Batal</a>
        </div>
    </form>
</div>
@endsection
