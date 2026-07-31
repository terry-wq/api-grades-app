<!-- Manage Group Modal -->
<div x-show="showManageGroupModal"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="showManageGroupModal = false" class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="settings-2" class="w-5 h-5 text-purple-400"></i>
        <span>Configuración del Grupo</span>
      </h3>
      <button @click="showManageGroupModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('groups.update', ($currentGroup ?? null)?->id ?? 1) }}" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Nombre del Grupo</label>
        <input type="text" name="name" value="{{ ($currentGroup ?? null)?->name }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Materia / Asignatura</label>
        <input type="text" name="subject" value="{{ ($currentGroup ?? null)?->subject }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Nivel Escolar</label>
          <input type="text" name="grade_level" value="{{ ($currentGroup ?? null)?->grade_level ?? 'Secundaria' }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Ciclo Lectivo</label>
          <input type="text" name="academic_year" value="{{ ($currentGroup ?? null)?->academic_year ?? '2025-2026' }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Semana Actual</label>
          <input type="number" name="current_week" value="{{ ($currentGroup ?? null)?->current_week }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Total Semanas</label>
          <input type="number" name="total_weeks" value="{{ ($currentGroup ?? null)?->total_weeks }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
        <button type="button" @click="showManageGroupModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
          Cancelar
        </button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-600/30">
          Guardar Cambios
        </button>
      </div>
    </form>
  </div>
</div>
