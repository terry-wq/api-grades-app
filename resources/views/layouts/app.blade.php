<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Calificaciones & Podio Kahoot</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Vite / CDN Fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            fontFamily: {
              sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
      html, body {
        overflow-x: hidden;
        max-width: 100vw;
      }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen selection:bg-purple-500 selection:text-white"
      x-data="kahootApp()"
      x-init="initAudio()">

    <!-- Header component -->
    @include('partials.header')

    <!-- Main Container -->
    <main class="max-w-[1600px] mx-auto px-3 sm:px-6 py-6">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 font-bold text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Global Modals -->
    @include('modals.add-student')
    @include('modals.add-incidence')
    @include('modals.add-practice')
    @include('modals.excel-csv')
    @include('modals.app-settings')
    @include('modals.manage-group')
    @include('modals.create-group')
    @include('modals.student-detail')

    <script>
      function kahootApp() {
        return {
          isMuted: false,
          activeTab: '{{ $activeTab ?? "podium" }}',
          
          // Modals state
          showAddStudentModal: false,
          showAddIncidenceModal: false,
          showAddPracticeModal: false,
          showExcelModal: false,
          showAppSettingsModal: false,
          showManageGroupModal: false,
          showCreateGroupModal: false,
          selectedStudentDetail: null,

          // Audio Synthesizer Context for Kahoot Effects
          audioCtx: null,

          initAudio() {
            lucide.createIcons();
          },

          playClickSound() {
            if (this.isMuted) return;
            try {
              if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
              const osc = this.audioCtx.createOscillator();
              const gain = this.audioCtx.createGain();
              osc.type = 'sine';
              osc.frequency.setValueAtTime(520, this.audioCtx.currentTime);
              osc.frequency.exponentialRampToValueAtTime(880, this.audioCtx.currentTime + 0.08);
              gain.gain.setValueAtTime(0.15, this.audioCtx.currentTime);
              gain.gain.exponentialRampToValueAtTime(0.01, this.audioCtx.currentTime + 0.08);
              osc.connect(gain);
              gain.connect(this.audioCtx.destination);
              osc.start();
              osc.stop(this.audioCtx.currentTime + 0.08);
            } catch(e) {}
          },

          playFanfareSound() {
            if (this.isMuted) return;
            try {
              if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
              const notes = [523.25, 659.25, 783.99, 1046.50];
              notes.forEach((freq, idx) => {
                const osc = this.audioCtx.createOscillator();
                const gain = this.audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.2, this.audioCtx.currentTime + idx * 0.1);
                gain.gain.exponentialRampToValueAtTime(0.01, this.audioCtx.currentTime + idx * 0.1 + 0.3);
                osc.connect(gain);
                gain.connect(this.audioCtx.destination);
                osc.start(this.audioCtx.currentTime + idx * 0.1);
                osc.stop(this.audioCtx.currentTime + idx * 0.1 + 0.3);
              });
            } catch(e) {}
          },

          toggleMute() {
            this.isMuted = !this.isMuted;
          }
        }
      }

      document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
      });
    </script>
</body>
</html>
