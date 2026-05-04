@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-6">{{ isset($pocket) ? 'Edit Pocket' : 'Buat Pocket Baru' }}</h2>

    <form method="POST" action="{{ isset($pocket) ? route('pockets.update', $pocket) : route('pockets.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        @if(isset($pocket)) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Pocket</label>
            <input type="text" name="name" value="{{ old('name', $pocket->name ?? '') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-4 py-2 border focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Contoh: Cash, Bank BCA, GoPay">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
            <textarea name="description" rows="2"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-4 py-2 border focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Deskripsi singkat...">{{ old('description', $pocket->description ?? '') }}</textarea>
        </div>
        <div class="flex space-x-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                {{ isset($pocket) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('pockets.index') }}" class="px-6 py-2 rounded-lg border hover:bg-gray-50">Batal</a>
        </div>
    </form>
</div>
@endsection
