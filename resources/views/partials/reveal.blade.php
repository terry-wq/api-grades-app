<div x-data="{
    step: 0,
    maxSteps: {{ ($rankedStudents ?? collect([]))->count() }},
    students: @js(($rankedStudents ?? collect([]))->values()->all()),
    revealedRanks: [],

    revealNext() {
      if (this.step < this.maxSteps) {
        this.step++;
        const currentRank = this.maxSteps - this.step + 1;
        this.revealedRanks.push(currentRank);
        if (typeof playFanfareSound === 'function') playFanfareSound();
      }
    },

    revealAll() {
      this.step = this.maxSteps;
      this.revealedRanks = Array.from({length: this.maxSteps}, (_, i) => i + 1);
      if (typeof playFanfareSound === 'function') playFanfareSound();
    },

    resetReveal() {
      this.step = 0;
      this.revealedRanks = [];
    }
  }" class="space-y-6">

  <!-- Header Banner -->
  <div class="bg-gradient-to-r from-amber-600/30 via-slate-900 to-pink-600/30 border border-amber-500/30 rounded-3xl p-6 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
    <div>
      <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/30 px-3 py-1 rounded-full text-xs font-black text-amber-300 uppercase tracking-widest mb-2">
        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-400"></i>
        <span>Modo Revelación Animada Kahoot</span>
      </div>
      <h2 class="text-2xl font-black text-white">
        Revelación Progresiva del Ranking
      </h2>
      <p class="text-xs sm:text-sm text-slate-300 mt-1">
        Revela del último lugar al 1er lugar para generar máxima expectación y emoción en el aula.
      </p>
    </div>

    <div class="flex items-center gap-2">
      <button
        @click="resetReveal()"
        class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition cursor-pointer"
      >
        Reiniciar
      </button>

      <button
        @click="revealNext()"
        :disabled="step >= maxSteps"
        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-pink-600 hover:brightness-110 disabled:opacity-50 text-white font-black text-xs sm:text-sm shadow-lg shadow-amber-500/25 flex items-center gap-2 transition cursor-pointer"
      >
        <i data-lucide="play" class="w-4 h-4"></i>
        <span>Revelar Siguiente Posición</span>
      </button>

      <button
        @click="revealAll()"
        class="px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow transition cursor-pointer"
      >
        Revelar Todo
      </button>
    </div>
  </div>

  <!-- Progress Bar -->
  <div class="bg-slate-900 border border-slate-800 rounded-2xl p-3 flex items-center justify-between gap-4">
    <div class="text-xs font-bold text-slate-400">
      Progreso de revelación: <span class="text-amber-400" x-text="step"></span> / <span x-text="maxSteps"></span>
    </div>
    <div class="flex-1 bg-slate-800 h-2.5 rounded-full overflow-hidden">
      <div class="bg-gradient-to-r from-amber-500 to-pink-500 h-full transition-all duration-300" :style="'width: ' + (step / maxSteps * 100) + '%'"></div>
    </div>
  </div>

  <!-- Cards List -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <template x-for="(item, index) in students" :key="item.student.id">
      <div
        class="p-4 rounded-2xl border transition-all duration-500 flex items-center justify-between gap-3 shadow-lg"
        :class="revealedRanks.includes(item.rank) 
          ? (item.rank === 1 ? 'bg-gradient-to-r from-amber-950/80 via-slate-900 to-slate-900 border-amber-400/80 shadow-amber-500/20' : (item.rank === 2 ? 'bg-slate-900 border-slate-400/60' : (item.rank === 3 ? 'bg-slate-900 border-amber-700/60' : 'bg-slate-900/90 border-slate-800')))
          : 'bg-slate-950/60 border-slate-900 opacity-40 blur-[1px]'"
      >
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm"
               :class="item.rank === 1 ? 'bg-amber-400 text-slate-950' : (item.rank === 2 ? 'bg-slate-300 text-slate-950' : (item.rank === 3 ? 'bg-amber-700 text-white' : 'bg-slate-800 text-slate-300'))"
               x-text="'#' + item.rank">
          </div>

          <div class="relative">
            <img :src="item.student.avatar" class="w-12 h-12 rounded-full object-cover border-2 border-slate-700" />
          </div>

          <div>
            <h4 class="font-bold text-sm text-white" x-text="revealedRanks.includes(item.rank) ? item.student.name : '??? Incógnito'"></h4>
            <div class="text-xs text-slate-400" x-show="revealedRanks.includes(item.rank)">
              Base: <span class="text-slate-200 font-bold" x-text="(item.computedBaseGrade || item.student.base_grade).toFixed(1)"></span>
            </div>
          </div>
        </div>

        <div class="text-right" x-show="revealedRanks.includes(item.rank)">
          <div class="text-lg font-black text-amber-300" x-text="item.finalScore.toFixed(1) + ' pts'"></div>
          <span class="text-[10px] text-emerald-400 font-semibold" x-text="(item.netIncidencePoints >= 0 ? '+' : '') + item.netIncidencePoints.toFixed(1) + ' inc.'"></span>
        </div>
      </div>
    </template>
  </div>

</div>
