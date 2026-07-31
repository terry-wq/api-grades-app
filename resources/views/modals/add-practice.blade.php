<!-- Add Practice Modal -->
<div x-show="showAddPracticeModal"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="showAddPracticeModal = false" class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="book-open" class="w-5 h-5 text-indigo-400"></i>
        <span>Añadir Nueva Práctica o Evaluación</span>
      </h3>
      <button @click="showAddPracticeModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('practices.store') }}" class="space-y-4">
      @csrf
      <input type="hidden" name="class_group_id" value="{{ ($currentGroup ?? null)?->id }}">
      <input type="hidden" name="weight" value="1.0">

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Nombre de la Práctica / Evaluación</label>
        <input type="text" name="name" required placeholder="Ej. Práctica 6 - Laravel Controllers" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
        <button type="button" @click="showAddPracticeModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
          Cancelar
        </button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-600/30">
          Guardar Práctica
        </button>
      </div>
    </form>
  </div>
</div>
