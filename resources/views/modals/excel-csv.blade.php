<!-- Excel CSV Modal -->
<div x-show="showExcelModal"
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="showExcelModal = false" class="bg-slate-900 border border-emerald-500/30 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="file-spreadsheet" class="w-5 h-5 text-emerald-400"></i>
        <span>Importar / Exportar Excel & CSV</span>
      </h3>
      <button @click="showExcelModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <p class="text-xs text-slate-300">
      Descarga la lista de alumnos con sus calificaciones base, incidencias positivas y negativas, puntos netos y nota final en formato CSV compatible con Microsoft Excel.
    </p>

    <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl flex items-center justify-between gap-4">
      <div>
        <div class="font-bold text-sm text-white">{{ ($currentGroup ?? null)?->name }}</div>
        <div class="text-xs text-slate-400">{{ ($currentGroup ?? null)?->subject }}</div>
      </div>

      <a href="{{ route('excel.export', ($currentGroup ?? null)?->id ?? 1) }}"
         class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow flex items-center gap-2 transition">
        <i data-lucide="download" class="w-4 h-4"></i>
        <span>Descargar CSV</span>
      </a>
    </div>

    <div class="flex items-center justify-end pt-2">
      <button type="button" @click="showExcelModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
        Cerrar
      </button>
    </div>
  </div>
</div>
