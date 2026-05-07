@extends('layouts.guest')

@section('content')
<div class="bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 border">
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Password Baru</label>
            <input type="password" name="password" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 border">
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 border">
        </div>

        @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
            <p class="text-red-500 text-sm">{{ $errors->first() }}</p>
        @endif

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 font-medium">Reset Password</button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">← Kembali ke Login</a>
    </p>
</div>
@endsection
