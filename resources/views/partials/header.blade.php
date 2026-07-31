<header class="sticky top-0 z-30 backdrop-blur-md transition-colors border-b border-purple-500/20 shadow-xl bg-slate-900/95 text-white">
  <div class="max-w-[1600px] mx-auto px-3 sm:px-6 py-2.5">
    <div class="flex flex-col xl:flex-row items-center justify-between gap-2.5 sm:gap-3">
      
      <!-- Top/Left Section: Group Selector & Subject Card -->
      <div class="flex flex-wrap sm:flex-nowrap items-center justify-center xl:justify-start gap-2 w-full xl:w-auto shrink-0">
        <div class="flex items-center gap-1.5 sm:gap-2 bg-slate-950/80 p-1.5 rounded-2xl border border-slate-800 shadow-inner max-w-full">
          <div class="p-1.5 bg-purple-600/20 text-purple-300 rounded-xl border border-purple-500/30 shrink-0">
            <i data-lucide="layers" class="w-4 h-4 text-purple-400"></i>
          </div>
          <form method="GET" action="{{ route('dashboard') }}" id="groupSelectForm">
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'podium' }}">
            <select
              name="group_id"
              onchange="document.getElementById('groupSelectForm').submit()"
              class="bg-slate-900 text-amber-300 text-xs sm:text-sm font-black rounded-xl px-2.5 sm:px-3 py-1.5 border border-purple-500/40 focus:outline-none focus:ring-2 focus:ring-purple-500 cursor-pointer hover:bg-slate-850 transition max-w-[160px] sm:max-w-xs truncate"
            >
              @foreach($groups ?? [] as $group)
                <option value="{{ $group->id }}" {{ $group->id == ($currentGroup?->id ?? null) ? 'selected' : '' }}>
                  {{ $group->name }} - {{ $group->subject }}
                </option>
              @endforeach
            </select>
          </form>

          @auth
            <button
              @click="showCreateGroupModal = true"
              title="Crear nuevo grupo"
              class="p-1.5 bg-slate-800 hover:bg-purple-600/30 hover:text-purple-300 text-slate-400 rounded-xl transition cursor-pointer shrink-0"
            >
              <i data-lucide="plus-circle" class="w-4 h-4 text-purple-400"></i>
            </button>
            <button
              @click="showManageGroupModal = true"
              title="Administrar información del grupo"
              class="p-1.5 bg-slate-800 hover:bg-purple-600/30 hover:text-purple-300 text-slate-400 rounded-xl transition cursor-pointer shrink-0"
            >
              <i data-lucide="settings-2" class="w-4 h-4"></i>
            </button>
          @endauth
        </div>

        <!-- Subject Badge -->
        @if(isset($currentGroup) && $currentGroup)
          <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-slate-950/80 border border-slate-800 text-xs">
            <i data-lucide="book-open" class="w-3.5 h-3.5 text-indigo-400 shrink-0"></i>
            <span class="text-slate-300 font-semibold truncate">{{ $currentGroup->subject }}</span>
            <span class="text-[10px] bg-indigo-950/80 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded-full font-bold">
              Semana {{ $currentGroup->current_week }}/{{ $currentGroup->total_weeks }}
            </span>
          </div>
        @endif
      </div>

      <!-- Center Navigation Tabs -->
      <div class="grid grid-cols-2 sm:grid-cols-4 xl:flex items-center gap-1 bg-slate-950/90 p-1.5 rounded-2xl border border-slate-800 w-full xl:w-auto justify-center shadow-inner shrink-0">
        <!-- Podium Tab (Public + Auth) -->
        <a
          href="{{ route('dashboard', ['group_id' => ($currentGroup ?? null)?->id, 'tab' => 'podium']) }}"
          @click="playClickSound()"
          class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($activeTab ?? 'podium') === 'podium' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-600/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}"
        >
          <i data-lucide="trophy" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
          <span class="truncate">Podio</span>
        </a>

        <!-- Statistics Tab (Public + Auth) -->
        <a
          href="{{ route('dashboard', ['group_id' => ($currentGroup ?? null)?->id, 'tab' => 'stats']) }}"
          @click="playClickSound()"
          class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($activeTab ?? 'podium') === 'stats' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-600/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}"
        >
          <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-emerald-400 shrink-0"></i>
          <span class="truncate">Estadísticas</span>
        </a>

        @auth
          <!-- Reveal Tab (Auth Only) -->
          <a
            href="{{ route('dashboard', ['group_id' => ($currentGroup ?? null)?->id, 'tab' => 'reveal']) }}"
            @click="playClickSound()"
            class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($activeTab ?? 'podium') === 'reveal' ? 'bg-gradient-to-r from-amber-500 to-pink-600 text-white shadow-md shadow-amber-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}"
          >
            <i data-lucide="play" class="w-3.5 h-3.5 text-amber-300 shrink-0"></i>
            <span class="truncate">Revelación</span>
          </a>

          <!-- Students List Tab (Auth Only) -->
          <a
            href="{{ route('dashboard', ['group_id' => ($currentGroup ?? null)?->id, 'tab' => 'list']) }}"
            @click="playClickSound()"
            class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($activeTab ?? 'podium') === 'list' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-600/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}"
          >
            <i data-lucide="users" class="w-3.5 h-3.5 text-cyan-400 shrink-0"></i>
            <span class="truncate">Alumnos</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold text-slate-500 opacity-60 hover:opacity-100 transition cursor-pointer" title="Inicia sesión para ver la lista de alumnos y evaluaciones">
            <i data-lucide="lock" class="w-3.5 h-3.5 shrink-0"></i>
            <span class="truncate">Gestión (Docente)</span>
          </a>
        @endauth
      </div>

      <!-- Right Action Buttons -->
      <div class="flex flex-wrap sm:flex-nowrap items-center justify-center xl:justify-end gap-1.5 sm:gap-2 w-full xl:w-auto shrink-0">
        <!-- Sound Mute Toggle -->
        <button
          @click="toggleMute()"
          title="Sonido"
          class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition cursor-pointer"
        >
          <template x-if="!isMuted">
            <i data-lucide="volume-2" class="w-4 h-4 text-emerald-400"></i>
          </template>
          <template x-if="isMuted">
            <i data-lucide="volume-x" class="w-4 h-4 text-rose-400"></i>
          </template>
        </button>

        @auth
          <!-- Excel / CSV Export (Auth Only) -->
          <button
            @click="showExcelModal = true"
            title="Cargar o Guardar respaldo completo en Excel y CSV"
            class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-emerald-300 border border-emerald-500/40 text-xs font-bold shadow-md transition cursor-pointer shrink-0 whitespace-nowrap"
          >
            <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-400 shrink-0"></i>
            <span>Excel/CSV</span>
          </button>

          <!-- Add Practice (Auth Only) -->
          <button
            @click="showAddPracticeModal = true"
            class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-bold hover:brightness-110 shadow-md shadow-purple-600/30 transition cursor-pointer shrink-0 whitespace-nowrap"
          >
            <i data-lucide="book-open" class="w-3.5 h-3.5 text-amber-300 shrink-0"></i>
            <span>+ Práctica</span>
          </button>

          <!-- Add Incidence (Auth Only) -->
          <button
            @click="showAddIncidenceModal = true"
            class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-xs font-bold hover:brightness-110 shadow-md shadow-emerald-500/20 transition cursor-pointer shrink-0 whitespace-nowrap"
          >
            <i data-lucide="sparkles" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Incidencia</span>
          </button>

          <!-- Add Student (Auth Only) -->
          <button
            @click="showAddStudentModal = true"
            class="flex items-center justify-center gap-1 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shadow-md shadow-purple-600/20 transition cursor-pointer shrink-0 whitespace-nowrap"
          >
            <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
            <span>Alumno</span>
          </button>

          <!-- User Menu & Logout -->
          <div class="flex items-center gap-2 pl-2 border-l border-slate-800">
            <span class="text-xs font-bold text-purple-300 hidden sm:inline">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" title="Cerrar Sesión" class="p-2 bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 rounded-xl transition cursor-pointer">
                <i data-lucide="log-out" class="w-4 h-4"></i>
              </button>
            </form>
          </div>
        @else
          <!-- Login Button for Guests -->
          <a
            href="{{ route('login') }}"
            class="flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-black hover:brightness-110 shadow-lg shadow-purple-600/30 transition cursor-pointer shrink-0 whitespace-nowrap"
          >
            <i data-lucide="log-in" class="w-4 h-4"></i>
            <span>Acceso Docente</span>
          </a>
        @endauth
      </div>

    </div>
  </div>
</header>
