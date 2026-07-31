<div class="space-y-8" x-data="{ revealAll: true }">
  
  <!-- Banner Top -->
  <div class="bg-gradient-to-r from-purple-900/60 via-slate-900 to-indigo-900/60 border border-purple-500/30 rounded-3xl p-4 sm:p-6 shadow-2xl relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative z-10 text-center sm:text-left">
      <div class="inline-flex items-center gap-2 bg-purple-500/20 border border-purple-400/30 px-3 py-1 rounded-full text-xs font-black text-purple-300 uppercase tracking-widest mb-2">
        <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-400"></i>
        <span>Podio</span>
      </div>
      <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
        Ranking de Calificaciones - {{ ($currentGroup ?? null)?->name }}
      </h2>
      <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
        Calificaciones base calculadas a escala de 10 puntos + suma neta de incidencias positivas y negativas en tiempo real.
      </p>
    </div>

    
  </div>

  @php
    $rankedStudents = $rankedStudents ?? collect([]);
    $top1 = $rankedStudents->firstWhere('rank', 1);
    $top2 = $rankedStudents->firstWhere('rank', 2);
    $top3 = $rankedStudents->firstWhere('rank', 3);
    $rest = $rankedStudents->where('rank', '>', 3);
  @endphp

  <!-- PODIUM TOP 3 STAGE -->
  @if($rankedStudents->count() >= 1)
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-end max-w-4xl mx-auto pt-6 sm:pt-12 pb-4">
    
    <!-- 2nd Place -->
    <div class="order-2 sm:order-1 flex flex-col items-center cursor-pointer" @click="selectedStudentDetail = {{ json_encode($top2) }}">
      @if($top2)
        <div class="flex flex-col items-center mb-3">
          <div class="relative">
            <img src="{{ $top2['student']->avatar }}" alt="{{ $top2['student']->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-4 border-slate-300 shadow-xl" />
            <div class="absolute -bottom-2 -right-2 bg-slate-300 text-slate-950 font-black text-xs w-7 h-7 rounded-full flex items-center justify-center border-2 border-slate-900 shadow">
              2º
            </div>
          </div>
          <h3 class="font-black text-sm sm:text-base text-white text-center mt-3 truncate max-w-[150px]">
            {{ $top2['student']->name }}
          </h3>
          <div class="text-xs text-slate-400 font-bold">
            Base: {{ number_format($top2['computedBaseGrade'], 1) }}
          </div>
          <div class="text-lg font-black text-slate-200 mt-0.5">
            {{ number_format($top2['finalScore'], 1) }} <span class="text-xs font-semibold text-slate-400">pts</span>
          </div>
        </div>
        <div class="w-full bg-gradient-to-t from-slate-800 to-slate-700/80 rounded-t-3xl border-t-4 border-slate-300 h-32 sm:h-40 flex items-center justify-center shadow-2xl">
          <span class="text-4xl font-black text-slate-400/40">2</span>
        </div>
      @endif
    </div>

    <!-- 1st Place -->
    <div class="order-1 sm:order-2 flex flex-col items-center cursor-pointer" @click="selectedStudentDetail = {{ json_encode($top1) }}">
      @if($top1)
        <div class="flex flex-col items-center mb-4">
          <div class="relative">
          <div class="absolute -top-8 left-1/2 -translate-x-1/2">
    <i data-lucide="crown" class="w-8 h-8 text-amber-400 fill-amber-400 animate-bounce"></i>
</div>

            <img src="{{ $top1['student']->avatar }}" alt="{{ $top1['student']->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-4 border-amber-400 shadow-2xl ring-4 ring-amber-400/20" />
            <div class="absolute -bottom-2 -right-2 bg-amber-400 text-slate-950 font-black text-sm w-8 h-8 rounded-full flex items-center justify-center border-2 border-slate-900 shadow">
              1º
            </div>
          </div>
          <h3 class="font-black text-base sm:text-lg text-amber-300 text-center mt-3 truncate max-w-[180px]">
            {{ $top1['student']->name }}
          </h3>
          <div class="text-xs text-slate-400 font-bold">
            Base: {{ number_format($top1['computedBaseGrade'], 1) }}
          </div>
          <div class="text-xl sm:text-2xl font-black text-amber-400 mt-0.5">
            {{ number_format($top1['finalScore'], 1) }} <span class="text-xs font-semibold text-slate-400">pts</span>
          </div>
          @if($top1['badgeText'])
            <span class="mt-1 text-[10px] font-bold text-amber-300 bg-amber-950/80 px-2.5 py-0.5 rounded-full border border-amber-500/40">
              {{ $top1['badgeText'] }}
            </span>
          @endif
        </div>
        <div class="w-full bg-gradient-to-t from-amber-600 to-amber-500/90 rounded-t-3xl border-t-4 border-amber-300 h-44 sm:h-52 flex items-center justify-center shadow-2xl">
          <span class="text-5xl font-black text-amber-950/40">1</span>
        </div>
      @endif
    </div>

    <!-- 3rd Place -->
    <div class="order-3 flex flex-col items-center cursor-pointer" @click="selectedStudentDetail = {{ json_encode($top3) }}">
      @if($top3)
        <div class="flex flex-col items-center mb-3">
          <div class="relative">
            <img src="{{ $top3['student']->avatar }}" alt="{{ $top3['student']->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-4 border-amber-700 shadow-xl" />
            <div class="absolute -bottom-2 -right-2 bg-amber-700 text-amber-100 font-black text-xs w-7 h-7 rounded-full flex items-center justify-center border-2 border-slate-900 shadow">
              3º
            </div>
          </div>
          <h3 class="font-black text-sm sm:text-base text-white text-center mt-3 truncate max-w-[150px]">
            {{ $top3['student']->name }}
          </h3>
          <div class="text-xs text-slate-400 font-bold">
            Base: {{ number_format($top3['computedBaseGrade'], 1) }}
          </div>
          <div class="text-lg font-black text-amber-200 mt-0.5">
            {{ number_format($top3['finalScore'], 1) }} <span class="text-xs font-semibold text-slate-400">pts</span>
          </div>
        </div>
        <div class="w-full bg-gradient-to-t from-amber-900/90 to-amber-800/80 rounded-t-3xl border-t-4 border-amber-600 h-24 sm:h-32 flex items-center justify-center shadow-2xl">
          <span class="text-4xl font-black text-amber-950/40">3</span>
        </div>
      @endif
    </div>

  </div>
  @endif

  <!-- REST OF THE CLASS (4th Place Onwards) -->
  <div class="space-y-3 pt-6">
    <h3 class="text-sm font-black text-slate-400 uppercase tracking-wider flex items-center gap-2">
      <i data-lucide="list-ordered" class="w-4 h-4 text-purple-400"></i>
      <span>Tabla General de Posiciones</span>
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      @foreach($rankedStudents as $item)
        <div @click="selectedStudentDetail = {{ json_encode($item) }}" class="bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-purple-500/40 rounded-2xl p-3 sm:p-3.5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 sm:gap-3 shadow-md transition group cursor-pointer">
          <!-- Left: Rank & Avatar & Name -->
          <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-xs sm:text-sm font-black text-slate-300 group-hover:bg-purple-600 group-hover:text-white transition shrink-0">
              #{{ $item['rank'] }}
            </div>

            <img
              src="{{ $item['student']->avatar }}"
              alt="{{ $item['student']->name }}"
              class="w-10 h-10 sm:w-11 sm:h-11 rounded-full object-cover border-2 border-slate-700 group-hover:border-purple-400 transition shrink-0"
            />

            <div class="min-w-0 flex-1">
              <h4 class="font-bold text-xs sm:text-sm text-white truncate group-hover:text-purple-300 transition">
                {{ $item['student']->name }}
              </h4>
              <div class="flex items-center gap-1.5 sm:gap-2 mt-0.5 text-[11px] sm:text-xs text-slate-400">
                <span>Base: <strong class="text-slate-200">{{ number_format($item['computedBaseGrade'], 1) }}</strong></span>
                <span>•</span>
                @if($item['netIncidencePoints'] >= 0)
                  <span class="text-emerald-400 font-semibold">+{{ number_format($item['netIncidencePoints'], 1) }} pts inc.</span>
                @else
                  <span class="text-rose-400 font-semibold">{{ number_format($item['netIncidencePoints'], 1) }} pts inc.</span>
                @endif
              </div>
            </div>
          </div>

          <!-- Right: Final Score -->
          <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 w-full sm:w-auto pt-2 sm:pt-0 border-t border-slate-800/80 sm:border-0">
            <div class="text-left sm:text-right">
              <div class="text-base sm:text-lg font-black text-white">
                {{ number_format($item['finalScore'], 1) }} <span class="text-xs font-normal text-slate-400">pts</span>
              </div>
              @if($item['badgeText'])
                <span class="text-[9px] sm:text-[10px] font-semibold text-amber-300 bg-amber-950/60 px-2 py-0.5 rounded-full border border-amber-500/30">
                  {{ $item['badgeText'] }}
                </span>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>
