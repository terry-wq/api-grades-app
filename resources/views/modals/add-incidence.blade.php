@php
  $predefinedData = (isset($predefinedIncidences) && count($predefinedIncidences) > 0)
    ? $predefinedIncidences
    : [
        ['title' => 'Ganador de Olimpiada Académica', 'type' => 'positive', 'points' => 2.0, 'category' => 'Olimpiada Académica'],
        ['title' => 'Proyecto Voluntario / Modelado', 'type' => 'positive', 'points' => 1.2, 'category' => 'Proyectos'],
        ['title' => 'Asesoría Tutórica a Compañeros', 'type' => 'positive', 'points' => 1.0, 'category' => 'Apoyo Académico'],
        ['title' => 'Resolución de Desafío en Pizarrón', 'type' => 'positive', 'points' => 0.8, 'category' => 'Participación'],
        ['title' => 'Entrega Puntual y Bitácora Limpia', 'type' => 'positive', 'points' => 0.5, 'category' => 'Puntualidad y Orden'],
        ['title' => 'Entrega Tardía de Tarea', 'type' => 'negative', 'points' => 0.5, 'category' => 'Cumplimiento'],
        ['title' => 'Falta de Material en Laboratorio/Clase', 'type' => 'negative', 'points' => 0.5, 'category' => 'Responsabilidad'],
        ['title' => 'Conducta Inadecuada / Distracción', 'type' => 'negative', 'points' => 0.8, 'category' => 'Conducta'],
    ];
@endphp

<!-- Add Incidence Modal -->
<div x-show="showAddIncidenceModal"
     x-transition
     x-data="{
       selectedPredefined: '',
       type: 'positive',
       points: 1.0,
       title: '',
       category: 'Participación',
       showCreateTemplate: false,
       predefinedList: {{ json_encode($predefinedData) }},
       selectPredefined(idx) {
         if (idx === '') return;
         const item = this.predefinedList[idx];
         if (item) {
           this.title = item.title;
           this.type = item.type;
           this.points = item.points;
           this.category = item.category;
         }
       }
     }"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
     style="display: none;">
  <div @click.away="showAddIncidenceModal = false" class="bg-slate-900 border border-emerald-500/30 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="sparkles" class="w-5 h-5 text-emerald-400"></i>
        <span>Registrar Nueva Incidencia</span>
      </h3>
      <button @click="showAddIncidenceModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('incidences.store') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Seleccionar Alumno</label>
        <select name="student_id" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
          @foreach($rankedStudents ?? [] as $item)
            <option value="{{ $item['student']->id }}">{{ $item['student']->name }} (Base: {{ number_format($item['student']->base_grade, 1) }})</option>
          @endforeach
        </select>
      </div>

      <div class="bg-purple-950/20 border border-purple-500/30 p-3 rounded-2xl space-y-2">
        <div class="flex items-center justify-between">
          <label class="block text-xs font-bold text-purple-300">Cargar Incidencia Predefinida</label>
          <button type="button" @click="showCreateTemplate = !showCreateTemplate" class="text-[11px] font-bold text-amber-400 hover:underline">
            <span x-text="showCreateTemplate ? '✖ Cerrar creador' : '➕ Crear nuevo tipo predefinido'"></span>
          </button>
        </div>

        <select x-model="selectedPredefined" @change="selectPredefined($event.target.value)" class="w-full bg-slate-950 border border-purple-500/30 rounded-xl px-3.5 py-2 text-xs text-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-500">
          <option value="">-- Seleccionar de plantilla guardada --</option>
          <template x-for="(item, idx) in predefinedList" :key="idx">
            <option :value="idx" x-text="(item.type === 'positive' ? '🟢 +' : '🔴 -') + item.points + ' pts - ' + item.title + ' (' + item.category + ')'"></option>
          </template>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Tipo de Incidencia</label>
          <select name="type" x-model="type" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="positive">Positiva (+Puntos)</option>
            <option value="negative">Negativa (-Puntos)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Puntos (Valor decimal)</label>
          <input type="number" step="0.1" min="0.1" name="points" x-model="points" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-emerald-400 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500" />
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Título de la Incidencia</label>
        <input type="text" name="title" x-model="title" required placeholder="Ej. Participación activa en clase" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-300 mb-1">Categoría</label>
        <input type="text" name="category" x-model="category" placeholder="Ej. Participación, Proyectos, Conducta" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
        <button type="button" @click="showAddIncidenceModal = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
          Cancelar
        </button>
        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30">
          Aplicar Incidencia
        </button>
      </div>
    </form>

    <!-- Sub-Form to save a NEW predefined template -->
    <div x-show="showCreateTemplate" x-transition class="mt-4 pt-4 border-t border-purple-500/30 bg-purple-950/30 p-4 rounded-2xl space-y-3">
      <h4 class="text-xs font-black text-amber-300 uppercase">Guardar Nueva Plantilla de Incidencia Predefinida</h4>
      <form method="POST" action="{{ route('predefined-incidences.store') }}" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-[11px] font-bold text-slate-300 mb-1">Tipo</label>
            <select name="type" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white">
              <option value="positive">Positiva (+)</option>
              <option value="negative">Negativa (-)</option>
            </select>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-300 mb-1">Puntos</label>
            <input type="number" step="0.1" min="0.1" value="1.0" name="points" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-amber-400 font-bold" />
          </div>
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-300 mb-1">Título de la plantilla</label>
          <input type="text" name="title" required placeholder="Ej. Mentoría Especial" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white" />
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-300 mb-1">Categoría</label>
          <input type="text" name="category" required value="General" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white" />
        </div>
        <div class="flex justify-end">
          <button type="submit" class="px-4 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow">
            Guardar Plantilla Predefinida
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
