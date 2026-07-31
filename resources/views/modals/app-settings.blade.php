<!-- App Settings / Teacher Profile Modal -->
<div x-show="showAppSettingsModal"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="showAppSettingsModal = false" class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="user-cog" class="w-5 h-5 text-purple-400"></i>
        <span>Editar Perfil y Nombre del Profesor</span>
      </h3>
      <button @click="showAppSettingsModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    @auth
      <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Nombre Completo del Profesor(a)</label>
          <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Correo Electrónico</label>
          <input type="email" name="email" value="{{ Auth::user()->email }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
          <button type="button" @click="showAppSettingsModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
            Cancelar
          </button>
          <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-600/30">
            Actualizar Perfil
          </button>
        </div>
      </form>
    @else
      <div class="text-center py-4 space-y-3">
        <p class="text-xs text-slate-300">Debes iniciar sesión como docente para editar tu perfil.</p>
        <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl">Iniciar Sesión</a>
      </div>
    @endauth
  </div>
</div>
