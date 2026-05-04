@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Pockets</h2>
    <a href="{{ route('pockets.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">+ Pocket Baru</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($pockets as $pocket)
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold">{{ $pocket->name }}</h3>
                    @if($pocket->description)
                        <p class="text-sm text-gray-400">{{ $pocket->description }}</p>
                    @endif
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('pockets.edit', $pocket) }}" class="text-indigo-500 hover:text-indigo-700 text-sm">Edit</a>
                    <form method="POST" action="{{ route('pockets.destroy', $pocket) }}" onsubmit="return confirm('Hapus pocket ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                    </form>
                </div>
            </div>
            <p class="text-2xl font-bold mt-3 {{ $pocket->balance >= 0 ? 'text-green-600' : 'text-red-500' }}">
                Rp {{ number_format($pocket->balance, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ $pocket->transactions_count }} transaksi</p>
            <a href="{{ route('pockets.transactions.index', $pocket) }}"
                class="mt-4 inline-block text-sm text-indigo-600 hover:underline">Lihat Transaksi →</a>
        </div>
    @empty
        <div class="col-span-3 text-center py-12 text-gray-400">
            <p class="text-lg">Belum ada pocket.</p>
            <a href="{{ route('pockets.create') }}" class="text-indigo-600 hover:underline">Buat pocket pertamamu</a>
        </div>
    @endforelse
</div>
@endsection
