<!-- Student Detail Modal -->
<div x-show="selectedStudentDetail"
     x-transition
     x-data="{
       modalTab: 'incidences',
       groupPractices: {{ json_encode(($practices ?? collect())->map(fn($p) => ['id' => $p->id, 'key' => 'pr-'.$p->id, 'name' => $p->name])->values()->toArray()) }},
       getPracticeScore(detail, key) {
         if (!detail) return 0;
         const evals = detail.student?.evaluation_grades || detail.evaluation_grades || {};
         return evals[key] !== undefined ? Number(evals[key]) : 0;
       }
     }"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="selectedStudentDetail = null" class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 w-full max-w-2xl shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
    
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <div class="flex items-center gap-3">
        <img :src="selectedStudentDetail?.student?.avatar || selectedStudentDetail?.avatar" class="w-12 h-12 rounded-full object-cover border-2 border-purple-500" />
        <div>
          <h3 class="text-lg font-black text-white" x-text="selectedStudentDetail?.student?.name || selectedStudentDetail?.name"></h3>
          <div class="text-xs text-slate-400 flex items-center gap-2">
            <span>Género: <strong x-text="selectedStudentDetail?.student?.gender || selectedStudentDetail?.gender"></strong></span>
            <span>•</span>
            <span>ID: #<span x-text="selectedStudentDetail?.student?.id || selectedStudentDetail?.id"></span></span>
          </div>
        </div>
      </div>
      <button @click="selectedStudentDetail = null" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <!-- Stats Summary Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
      <div class="p-3 bg-slate-950 border border-slate-800 rounded-2xl">
        <div class="text-[10px] text-slate-400 font-bold uppercase">Nota Base</div>
        <div class="text-lg font-black text-amber-300" x-text="Number(selectedStudentDetail?.computedBaseGrade || selectedStudentDetail?.student?.base_grade || selectedStudentDetail?.base_grade || 0).toFixed(1)"></div>
      </div>
      <div class="p-3 bg-slate-950 border border-slate-800 rounded-2xl">
        <div class="text-[10px] text-slate-400 font-bold uppercase">Incidencias (+)</div>
        <div class="text-lg font-black text-emerald-400" x-text="'+' + Number(selectedStudentDetail?.totalPositivePoints || 0).toFixed(1)"></div>
      </div>
      <div class="p-3 bg-slate-950 border border-slate-800 rounded-2xl">
        <div class="text-[10px] text-slate-400 font-bold uppercase">Incidencias (-)</div>
        <div class="text-lg font-black text-rose-400" x-text="'-' + Number(selectedStudentDetail?.totalNegativePoints || 0).toFixed(1)"></div>
      </div>
      <div class="p-3 bg-slate-950 border border-purple-500/40 rounded-2xl bg-purple-950/30">
        <div class="text-[10px] text-purple-300 font-bold uppercase">Nota Final</div>
        <div class="text-xl font-black text-white" x-text="Number(selectedStudentDetail?.finalScore || selectedStudentDetail?.student?.base_grade || 0).toFixed(1)"></div>
      </div>
    </div>

    <!-- Modal Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-800 pb-2">
      <button type="button" @click="modalTab = 'incidences'" :class="modalTab === 'incidences' ? 'bg-purple-600 text-white shadow' : 'bg-slate-950 text-slate-400 hover:text-white'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition">
        Historial de Incidencias
      </button>
      <button type="button" @click="modalTab = 'evaluations'" :class="modalTab === 'evaluations' ? 'bg-amber-500 text-slate-950 shadow font-black' : 'bg-slate-950 text-slate-400 hover:text-white'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition">
        Prácticas y Examen (Calificaciones)
      </button>
      <button type="button" @click="modalTab = 'edit'" :class="modalTab === 'edit' ? 'bg-indigo-600 text-white shadow font-black' : 'bg-slate-950 text-slate-400 hover:text-white'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition">
        Editar Información
      </button>
    </div>

    <!-- Tab 1: Incidences List -->
    <div x-show="modalTab === 'incidences'" class="space-y-3 pt-2">
      <template x-if="(selectedStudentDetail?.student?.incidences || selectedStudentDetail?.incidences || []).length === 0">
        <div class="p-4 bg-slate-950/60 rounded-2xl border border-slate-800 text-center text-xs text-slate-400">
          Sin incidencias registradas para este alumno.
        </div>
      </template>

      <div class="space-y-2 max-h-56 overflow-y-auto">
        <template x-for="inc in (selectedStudentDetail?.student?.incidences || selectedStudentDetail?.incidences || [])" :key="inc.id">
          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 flex items-center justify-between text-xs">
            <div>
              <div class="font-bold text-white" x-text="inc.title"></div>
              <div class="text-[10px] text-slate-400" x-text="(inc.category || 'General') + ' • ' + (inc.date ? new Date(inc.date).toLocaleDateString() : 'Reciente')"></div>
            </div>
            <div class="flex items-center gap-2">
              <span :class="inc.type === 'positive' ? 'text-emerald-400 bg-emerald-950/80 border-emerald-500/30' : 'text-rose-400 bg-rose-950/80 border-rose-500/30'"
                    class="font-black px-2.5 py-1 rounded-lg border text-xs"
                    x-text="(inc.type === 'positive' ? '+' : '-') + Number(inc.points).toFixed(1) + ' pts'"></span>
            </div>
          </div>
        </template>
      </div>
    </div>

    <!-- Tab 2: Evaluations / Practice Scores -->
    <div x-show="modalTab === 'evaluations'" class="space-y-4 pt-2">
      <div class="text-[11px] text-purple-300/90 bg-purple-950/40 p-3 rounded-2xl border border-purple-500/30 flex items-center gap-2">
        <i data-lucide="calculator" class="w-4 h-4 text-amber-400 shrink-0"></i>
        <span>Fórmula: <strong>Promedio de Prácticas</strong> y <strong>Examen</strong> se promedian entre sí para obtener la <strong>Nota Base</strong>. Las incidencias (+/-) se aplican posteriormente.</span>
      </div>

      @if($isAuthenticated ?? auth()->check())
        <form method="POST" :action="'/students/' + (selectedStudentDetail?.student?.id || selectedStudentDetail?.id) + '/evaluations'" class="space-y-4">
          @csrf
          @method('PUT')

          <div class="bg-slate-950 p-3 rounded-2xl border border-slate-800 flex items-center justify-between gap-3">
            <div>
              <label class="block text-xs font-bold text-amber-300">Examen Teórico / Evaluación Final (0.0 - 10.0)</label>
              <p class="text-[10px] text-slate-400">Calificación obtenida en el examen del alumno</p>
            </div>
            <input type="number" step="0.1" min="0" max="10" name="exam_grade"
                   :value="selectedStudentDetail?.student?.exam_grade ?? selectedStudentDetail?.examGrade ?? 0"
                   class="w-24 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-sm font-black text-amber-300 text-right focus:outline-none focus:ring-2 focus:ring-amber-500" />
          </div>

          <div class="space-y-2">
            <h5 class="text-xs font-bold text-purple-300 uppercase">Prácticas de la Materia</h5>

            <template x-if="!groupPractices || groupPractices.length === 0">
              <div class="p-4 bg-slate-950 rounded-xl border border-slate-800 text-center text-xs text-slate-400 italic">
                No hay prácticas registradas aún en esta materia. Puedes agregar nuevas prácticas en el panel principal.
              </div>
            </template>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-56 overflow-y-auto">
              <template x-for="pr in groupPractices" :key="pr.id">
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex items-center justify-between gap-2">
                  <span class="text-xs font-bold text-slate-200 truncate" x-text="pr.name"></span>
                  <input type="number" step="0.1" min="0" max="10"
                         :name="'evaluation_grades[' + pr.key + ']'"
                         :value="getPracticeScore(selectedStudentDetail, pr.key)"
                         class="w-20 bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs font-black text-emerald-400 text-right focus:outline-none focus:ring-2 focus:ring-purple-500" />
                </div>
              </template>
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-purple-600/30 transition">
              Guardar Calificaciones del Alumno
            </button>
          </div>
        </form>
      @else
        <!-- Guest View of Practices -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-60 overflow-y-auto">
          <template x-for="pr in groupPractices" :key="pr.id">
            <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex items-center justify-between gap-2">
              <span class="text-xs font-bold text-slate-200 truncate" x-text="pr.name"></span>
              <span class="text-xs font-black text-emerald-400 bg-emerald-950/80 px-2.5 py-1 rounded-lg border border-emerald-500/30" x-text="getPracticeScore(selectedStudentDetail, pr.key).toFixed(1) + ' / 10.0'"></span>
            </div>
          </template>
        </div>
      @endif
    </div>

    <!-- Tab 3: Edit Student Profile / Information -->
    <div x-show="modalTab === 'edit'" class="space-y-4 pt-2">
      <form method="POST" :action="'/students/' + (selectedStudentDetail?.student?.id || selectedStudentDetail?.id)" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Nombre Completo del Alumno</label>
          <input type="text" name="name" required
                 :value="selectedStudentDetail?.student?.name || selectedStudentDetail?.name || ''"
                 class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white font-bold focus:outline-none focus:ring-2 focus:ring-purple-500" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Género</label>
            <select name="gender"
                    :value="selectedStudentDetail?.student?.gender || selectedStudentDetail?.gender || 'M'"
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
              <option value="O">Otro</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Nota Base Inicial (0.0 - 10.0)</label>
            <input type="number" step="0.1" min="0" max="10" name="base_grade"
                   :value="selectedStudentDetail?.student?.base_grade ?? selectedStudentDetail?.base_grade ?? 8.0"
                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-amber-300 font-bold focus:outline-none focus:ring-2 focus:ring-purple-500" />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">URL de Foto / Avatar</label>
          <div class="flex items-center gap-2">
            <input type="url" name="avatar" id="editStudentAvatarInput"
                   :value="selectedStudentDetail?.student?.avatar || selectedStudentDetail?.avatar || ''"
                   placeholder="https://..."
                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
          </div>
          <p class="text-[10px] text-slate-400 mt-1">Puedes personalizar el enlace o usar imágenes en línea (DiceBear, UI Avatars, etc.).</p>
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-slate-800">
          <button type="button"
                  @click="if(confirm('¿Seguro de eliminar este alumno?')) { $refs.deleteStudentForm.submit(); }"
                  class="px-3.5 py-2 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 font-bold text-xs border border-rose-500/30 transition flex items-center gap-1.5">
            <i data-lucide="trash-2" class="w-4 h-4 text-rose-400"></i>
            <span>Eliminar Alumno</span>
          </button>

          <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition flex items-center gap-1.5">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Guardar Perfil de Alumno</span>
          </button>
        </div>
      </form>

      <form x-ref="deleteStudentForm" method="POST" :action="'/students/' + (selectedStudentDetail?.student?.id || selectedStudentDetail?.id)" class="hidden">
        @csrf
        @method('DELETE')
      </form>
    </div>

    <div class="flex items-center justify-end pt-3 border-t border-slate-800">
      <button @click="selectedStudentDetail = null" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold">
        Cerrar
      </button>
    </div>
  </div>
</div>
