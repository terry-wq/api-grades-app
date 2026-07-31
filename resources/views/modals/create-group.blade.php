<!-- Create Group Modal -->
<div x-show="showCreateGroupModal"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="showCreateGroupModal = false" class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="plus-circle" class="w-5 h-5 text-purple-400"></i>
        <span>Crear Nuevo Grupo Escolar</span>
      </h3>
      <button @click="showCreateGroupModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('groups.store') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Nombre del Grupo</label>
        <input type="text" name="name" placeholder="Ej: 3º A de Secundaria, 1º Bachillerato" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Materia / Asignatura</label>
        <input type="text" name="subject" placeholder="Ej: Matemáticas, Biología, Historia" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Nivel Escolar</label>
          <input type="text" name="grade_level" value="Secundaria" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Ciclo Lectivo</label>
          <input type="text" name="academic_year" value="2025-2026" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Semana Actual</label>
          <input type="number" name="current_week" value="1" min="1" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Total Semanas</label>
          <input type="number" name="total_weeks" value="12" min="1" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
        <button type="button" @click="showCreateGroupModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
          Cancelar
        </button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-600/30">
          + Crear Grupo
        </button>
      </div>
    </form>
  </div>
</div>
