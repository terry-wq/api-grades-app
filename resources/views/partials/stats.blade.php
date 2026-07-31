<div class="space-y-6">
  
  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total de Alumnos</span>
        <div class="p-2 bg-purple-500/20 text-purple-300 rounded-xl">
          <i data-lucide="users" class="w-5 h-5"></i>
        </div>
      </div>
      <div class="text-3xl font-black text-white mt-2">{{ ($rankedStudents ?? collect([]))->count() }}</div>
      <div class="text-xs text-slate-400 mt-1">Registrados en {{ ($currentGroup ?? null)?->name }}</div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Promedio General</span>
        <div class="p-2 bg-emerald-500/20 text-emerald-300 rounded-xl">
          <i data-lucide="trending-up" class="w-5 h-5"></i>
        </div>
      </div>
      @php
        $avgFinal = ($rankedStudents ?? collect([]))->avg('finalScore') ?? 0;
      @endphp
      <div class="text-3xl font-black text-emerald-400 mt-2">{{ number_format($avgFinal, 1) }}</div>
      <div class="text-xs text-slate-400 mt-1">Escala de 0 a 10+ pts</div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Incidencias Positivas</span>
        <div class="p-2 bg-teal-500/20 text-teal-300 rounded-xl">
          <i data-lucide="sparkles" class="w-5 h-5"></i>
        </div>
      </div>
      @php
        $totalPos = ($rankedStudents ?? collect([]))->sum('totalPositivePoints');
      @endphp
      <div class="text-3xl font-black text-teal-400 mt-2">+{{ number_format($totalPos, 1) }}</div>
      <div class="text-xs text-slate-400 mt-1">Puntos acumulados a favor</div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Incidencias Negativas</span>
        <div class="p-2 bg-rose-500/20 text-rose-300 rounded-xl">
          <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        </div>
      </div>
      @php
        $totalNeg = ($rankedStudents ?? collect([]))->sum('totalNegativePoints');
      @endphp
      <div class="text-3xl font-black text-rose-400 mt-2">-{{ number_format($totalNeg, 1) }}</div>
      <div class="text-xs text-slate-400 mt-1">Puntos restados por falta</div>
    </div>
  </div>

</div>
