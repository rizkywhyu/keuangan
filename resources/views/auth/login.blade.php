@extends('layouts.guest')
@section('content')
<div class="bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 border">
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-indigo-600">
            <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
        </div>
        @if($errors->any())
            <p class="text-red-500 text-sm">{{ $errors->first() }}</p>
        @endif
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 font-medium">Login</button>
    </form>
    <p class="mt-4 text-center text-sm text-gray-600">
        Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register</a>
    </p>
    <p class="mt-2 text-center text-sm text-gray-600">
        <a href="{{ route('password.reset') }}" class="text-indigo-600 hover:underline">Lupa password?</a>
    </p>
</div>
@endsection
