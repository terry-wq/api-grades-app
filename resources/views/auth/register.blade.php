@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center p-3 bg-purple-600/20 text-purple-400 rounded-2xl border border-purple-500/30 mb-2">
                <i data-lucide="user-plus" class="w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-black text-white tracking-tight">Registro de Docente</h2>
            <p class="text-xs text-slate-400">
                Crea una cuenta para administrar grupos y registros de alumnos.
            </p>
        </div>

        @if ($errors->any())
            <div class="p-3 bg-rose-500/20 border border-rose-500/40 rounded-xl text-rose-300 text-xs font-bold space-y-1">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="Prof. Roberto Gómez"
                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="profesor@escuela.edu"
                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black text-sm shadow-lg shadow-purple-600/30 transition cursor-pointer flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Crear Cuenta</span>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-800 text-xs text-slate-400">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-purple-400 font-bold hover:underline">Inicia sesión</a>
        </div>

    </div>
</div>
@endsection
