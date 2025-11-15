{{-- 1. Gunakan layout dari 'resources/views/layout/home.blade.php' --}}
@extends('layout.home')

{{-- 2. Definisikan konten untuk @yield('content') yang ada di layout --}}
@section('content')

    {{-- 3. Masukkan komponen hero dari 'resources/views/component/hero.blade.php' --}}
    @include('component.hero')

    
@endsection