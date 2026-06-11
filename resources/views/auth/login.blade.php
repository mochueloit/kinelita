@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="max-w-md mx-auto mt-8">
    <div class="wc-card p-8">
        <div class="text-center mb-6">
            <x-logo size="lg" class="justify-center mb-4" />
            <h1 class="wc-title-dark">Acceso Administrador</h1>
            <p class="wc-subtitle-dark text-sm mt-1">Mundial FIFA 2026</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="wc-label">Correo</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="wc-input">
            </div>

            <div>
                <label for="password" class="wc-label">Contraseña</label>
                <input type="password" id="password" name="password" required class="wc-input">
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-500">
                <input type="checkbox" name="remember" class="rounded border-sky-300 text-sky-500">
                Recordarme
            </label>

            <button type="submit" class="wc-btn-gold w-full py-3 text-lg">
                Ingresar
            </button>
        </form>
    </div>
</div>
@endsection
