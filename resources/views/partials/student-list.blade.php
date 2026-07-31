<div class="space-y-4">
  
  <!-- Header bar -->
  <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-900 border border-slate-800 p-4 rounded-2xl">
    <div>
      <h3 class="text-lg font-black text-white flex items-center gap-2">
        <i data-lucide="users" class="w-5 h-5 text-purple-400"></i>
        <span>Lista de Alumnos del Grupo</span>
      </h3>
      <p class="text-xs text-slate-400">
        Gestión completa de calificaciones base, registros de incidencias y edición individual.
      </p>
    </div>

    <button
      @click="showAddStudentModal = true"
      class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs rounded-xl shadow transition cursor-pointer flex items-center gap-1.5"
    >
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Agregar Alumno</span>
    </button>
  </div>

  <!-- Student Table -->
  <div class="bg-slate-900/90 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs sm:text-sm text-slate-300">
        <thead class="bg-slate-950 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-800">
          <tr>
            <th class="p-3.5">Posición</th>
            <th class="p-3.5">Alumno</th>
            <th class="p-3.5 text-center">Calificación Base</th>
            <th class="p-3.5 text-center">Incidencias (+)</th>
            <th class="p-3.5 text-center">Incidencias (-)</th>
            <th class="p-3.5 text-center">Puntos Netos</th>
            <th class="p-3.5 text-center">Calificación Final</th>
            <th class="p-3.5 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/80">
          @foreach($rankedStudents ?? [] as $item)
            <tr class="hover:bg-slate-800/50 transition">
              <td class="p-3.5 font-black text-purple-400 text-center w-12">
                #{{ $item['rank'] }}
              </td>
              <td class="p-3.5">
                <div class="flex items-center gap-3">
                  <img src="{{ $item['student']->avatar }}" alt="{{ $item['student']->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-700" />
                  <div>
                    <div class="font-bold text-white text-xs sm:text-sm">{{ $item['student']->name }}</div>
                    <div class="text-[10px] text-slate-400">ID: {{ $item['student']->id }}</div>
                  </div>
                </div>
              </td>
              <td class="p-3.5 text-center font-bold text-slate-200">
                {{ number_format($item['computedBaseGrade'], 1) }}
              </td>
              <td class="p-3.5 text-center font-bold text-emerald-400">
                +{{ number_format($item['totalPositivePoints'], 1) }}
              </td>
              <td class="p-3.5 text-center font-bold text-rose-400">
                -{{ number_format($item['totalNegativePoints'], 1) }}
              </td>
              <td class="p-3.5 text-center font-bold">
                @if($item['netIncidencePoints'] >= 0)
                  <span class="text-emerald-400">+{{ number_format($item['netIncidencePoints'], 1) }}</span>
                @else
                  <span class="text-rose-400">{{ number_format($item['netIncidencePoints'], 1) }}</span>
                @endif
              </td>
              <td class="p-3.5 text-center font-black text-amber-300 text-base">
                {{ number_format($item['finalScore'], 1) }}
              </td>
              <td class="p-3.5 text-right space-x-1">
                <button type="button" @click="selectedStudentDetail = {{ json_encode($item) }}; $nextTick(() => { const el = document.querySelector('[x-data]'); if (el) { el._x_dataStack[0].modalTab = 'incidences'; } })" class="p-1.5 text-purple-400 hover:bg-purple-500/20 rounded-lg transition" title="Ver detalles e incidencias">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
                <button type="button" @click="selectedStudentDetail = {{ json_encode($item) }}; $nextTick(() => { const el = document.querySelector('[x-data]'); if (el) { el._x_dataStack[0].modalTab = 'edit'; } })" class="p-1.5 text-indigo-400 hover:bg-indigo-500/20 rounded-lg transition" title="Editar datos del alumno">
                  <i data-lucide="pencil" class="w-4 h-4"></i>
                </button>
                <form method="POST" action="{{ route('students.destroy', $item['student']->id) }}" class="inline" onsubmit="return confirm('¿Seguro de eliminar este alumno?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-1.5 text-rose-400 hover:bg-rose-500/20 rounded-lg transition" title="Eliminar alumno">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
