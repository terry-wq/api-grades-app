<!-- Add Student Modal -->
<div x-show="showAddStudentModal"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="showAddStudentModal = false" class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="user-plus" class="w-5 h-5 text-purple-400"></i>
        <span>Agregar Nuevo Alumno</span>
      </h3>
      <button @click="showAddStudentModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('students.store') }}" class="space-y-4">
      @csrf
      <input type="hidden" name="class_group_id" value="{{ ($currentGroup ?? null)?->id }}">

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Nombre Completo</label>
        <input type="text" name="name" required placeholder="Ej. Sofía Hernández" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Calificación Base (0 - 10)</label>
          <input type="number" step="0.1" min="0" max="10" name="base_grade" value="8.0" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-amber-300 font-bold focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Género</label>
          <select name="gender" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="O">Otro</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">URL Avatar (Opcional)</label>
        <input type="url" name="avatar" placeholder="https://..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
        <button type="button" @click="showAddStudentModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
          Cancelar
        </button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-600/30">
          Guardar Alumno
        </button>
      </div>
    </form>
  </div>
</div>
