@extends('layouts.app')

@section('title', 'Nuevo Participante')

@section('content')
<h1 class="wc-title !text-2xl md:!text-3xl mb-6">Nuevo Participante</h1>

<div class="wc-card p-6">
    <form method="POST" action="{{ route('admin.participants.store') }}">
        @csrf
        @include('admin.participants._form', ['participant' => null, 'predictions' => collect()])

        <div class="flex gap-4 mt-6">
            <button type="submit" class="wc-btn-gold">Guardar participante</button>
            <a href="{{ route('admin.participants.index') }}" class="wc-btn-green">Cancelar</a>
        </div>
    </form>
</div>
@endsection
