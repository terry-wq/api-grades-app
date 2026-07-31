@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{
    activeAdminSection: 'students',
    editingAvatarStudent: null,
    avatarUrl: ''
}">

    <!-- Top Admin Header Banner -->
    <div class="bg-gradient-to-r from-purple-900/80 via-slate-900 to-indigo-900/80 border border-purple-500/30 rounded-3xl p-6 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-purple-600/30 border border-purple-500/50 flex items-center justify-center text-purple-300 font-black text-2xl shadow-inner">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider text-purple-400 bg-purple-950/80 px-2.5 py-0.5 rounded-full border border-purple-500/30">
                        Panel Docente
                    </span>
                    <span class="text-xs text-slate-400">{{ $teacher->email }}</span>
                </div>
                <h1 class="text-2xl font-black text-white mt-1">Profesor(a): {{ $teacher->name }}</h1>
                <p class="text-xs text-slate-300 mt-0.5">Gestión de datos de grupos, cambio de avatar, carga de calificaciones e incidencias.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button @click="showAppSettingsModal = true" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition flex items-center gap-2 cursor-pointer">
                <i data-lucide="user-cog" class="w-4 h-4 text-purple-400"></i>
                <span>Editar Nombre Docente</span>
            </button>
            <a href="{{ route('dashboard') }}" target="_blank" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shadow-lg shadow-purple-600/30 transition flex items-center gap-2">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Ver Podio Público (React API)</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs for Admin Sections -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-800 pb-3">
        <button @click="activeAdminSection = 'students'" :class="activeAdminSection === 'students' ? 'bg-purple-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition cursor-pointer">
            <i data-lucide="users" class="w-4 h-4"></i>
            <span>Alumnos y Avatares</span>
        </button>
        <button @click="activeAdminSection = 'groups'" :class="activeAdminSection === 'groups' ? 'bg-purple-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition cursor-pointer">
            <i data-lucide="layers" class="w-4 h-4"></i>
            <span>Gestión de Grupos</span>
        </button>
        <button @click="activeAdminSection = 'incidences'" :class="activeAdminSection === 'incidences' ? 'bg-purple-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition cursor-pointer">
            <i data-lucide="sparkles" class="w-4 h-4"></i>
            <span>Incidencias</span>
        </button>
    </div>

    <!-- SECTION 1: STUDENTS & AVATARS -->
    <div x-show="activeAdminSection === 'students'" class="space-y-4">
        <div class="flex items-center justify-between bg-slate-900 p-4 rounded-2xl border border-slate-800">
            <div>
                <h3 class="font-black text-white text-lg">Alumnos del Grupo: {{ $currentGroup?->name }}</h3>
                <p class="text-xs text-slate-400">Modifica nombres, cambia avatares, ajusta notas base o elimina registros.</p>
            </div>
            <button @click="showAddStudentModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl shadow flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>+ Nuevo Alumno</span>
            </button>
        </div>

        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="p-3.5">Avatar</th>
                            <th class="p-3.5">Alumno</th>
                            <th class="p-3.5 text-center">Calificación Base</th>
                            <th class="p-3.5 text-center">Incidencias (+)</th>
                            <th class="p-3.5 text-center">Incidencias (-)</th>
                            <th class="p-3.5 text-center">Puntos Netos</th>
                            <th class="p-3.5 text-center">Nota Final</th>
                            <th class="p-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($students as $student)
                            <tr class="hover:bg-slate-800/50 transition">
                                <td class="p-3.5">
                                    <div class="relative group cursor-pointer" @click="editingAvatarStudent = {{ json_encode($student) }}; avatarUrl = '{{ $student->avatar }}'">
                                        <img src="{{ $student->avatar }}" alt="{{ $student->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-purple-500/40 group-hover:border-purple-400 transition" />
                                        <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <i data-lucide="camera" class="w-4 h-4 text-white"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-bold text-white text-sm">{{ $student->name }}</div>
                                    <div class="text-[10px] text-slate-400">Género: {{ $student->gender }}</div>
                                </td>
                                <td class="p-3.5 text-center font-bold text-amber-300">
                                    <form method="POST" action="{{ route('students.update-grade', $student->id) }}" class="inline-flex items-center gap-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" step="0.1" min="0" max="10" name="base_grade" value="{{ number_format($student->base_grade, 1) }}" class="w-16 bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-center text-xs font-bold text-amber-300 focus:ring-1 focus:ring-purple-500" />
                                        <button type="submit" title="Guardar nota" class="p-1 text-purple-400 hover:text-purple-300">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5 text-center font-bold text-emerald-400">
                                    +{{ number_format($student->total_positive_points, 1) }}
                                </td>
                                <td class="p-3.5 text-center font-bold text-rose-400">
                                    -{{ number_format($student->total_negative_points, 1) }}
                                </td>
                                <td class="p-3.5 text-center font-bold">
                                    @if($student->net_incidence_points >= 0)
                                        <span class="text-emerald-400">+{{ number_format($student->net_incidence_points, 1) }}</span>
                                    @else
                                        <span class="text-rose-400">{{ number_format($student->net_incidence_points, 1) }}</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-center font-black text-amber-300 text-base">
                                    {{ number_format($student->final_score, 1) }}
                                </td>
                                <td class="p-3.5 text-right space-x-1">
                                    <form method="POST" action="{{ route('students.destroy', $student->id) }}" class="inline" onsubmit="return confirm('¿Eliminar a este alumno?')">
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

    <!-- SECTION 2: GROUP MANAGEMENT -->
    <div x-show="activeAdminSection === 'groups'" class="space-y-4">
        <div class="flex items-center justify-between bg-slate-900 p-4 rounded-2xl border border-slate-800">
            <div>
                <h3 class="font-black text-white text-lg">Grupos y Asignaturas</h3>
                <p class="text-xs text-slate-400">Crea nuevos grupos escolares o elimina grupos existentes.</p>
            </div>
            <button @click="showCreateGroupModal = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl shadow flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>+ Crear Nuevo Grupo</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($groups as $group)
                <div class="bg-slate-900 border border-slate-800 hover:border-purple-500/40 rounded-2xl p-5 shadow-xl space-y-3 relative group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-400 bg-purple-950/80 px-2.5 py-1 rounded-lg border border-purple-500/30">
                            {{ $group->subject }}
                        </span>
                        <div class="flex items-center gap-1">
                            <button @click="showManageGroupModal = true" title="Configurar grupo" class="text-slate-400 hover:text-purple-300 p-1 rounded-lg">
                                <i data-lucide="settings-2" class="w-4 h-4"></i>
                            </button>
                            <form method="POST" action="{{ route('groups.destroy', $group->id) }}" onsubmit="return confirm('¿Seguro de eliminar este grupo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 p-1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-black text-lg text-white">{{ $group->name }}</h4>
                        <div class="text-xs text-slate-400 mt-1">Semana {{ $group->current_week }} de {{ $group->total_weeks }}</div>
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Ciclo {{ $group->academic_year }}</span>
                        <a href="{{ route('admin.index', ['group_id' => $group->id]) }}" class="text-xs font-bold text-purple-400 hover:underline flex items-center gap-1">
                            <span>Gestionar</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- SECTION 3: INCIDENCES LOG -->
    <div x-show="activeAdminSection === 'incidences'" class="space-y-4">
        <div class="flex items-center justify-between bg-slate-900 p-4 rounded-2xl border border-slate-800">
            <div>
                <h3 class="font-black text-white text-lg">Registro de Incidencias</h3>
                <p class="text-xs text-slate-400">Puntos a favor o en contra aplicados a los alumnos.</p>
            </div>
            <button @click="showAddIncidenceModal = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <span>+ Registrar Incidencia</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($students as $std)
                @foreach($std->incidences as $inc)
                    <div class="p-3.5 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between gap-3 shadow-md">
                        <div class="flex items-center gap-3">
                            <img src="{{ $std->avatar }}" class="w-9 h-9 rounded-full object-cover border border-slate-700" />
                            <div>
                                <div class="font-bold text-white text-xs sm:text-sm">{{ $std->name }}</div>
                                <div class="text-xs text-slate-400">{{ $inc->title }} ({{ $inc->category }})</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($inc->type === 'positive')
                                <span class="font-black text-xs text-emerald-400 bg-emerald-950/80 px-2.5 py-1 rounded-lg border border-emerald-500/30">+{{ number_format($inc->points, 1) }} pts</span>
                            @else
                                <span class="font-black text-xs text-rose-400 bg-rose-950/80 px-2.5 py-1 rounded-lg border border-rose-500/30">-{{ number_format($inc->points, 1) }} pts</span>
                            @endif

                            <form method="POST" action="{{ route('incidences.destroy', $inc->id) }}" onsubmit="return confirm('¿Eliminar esta incidencia?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-500 hover:text-rose-400 p-1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- MODAL: CHANGE STUDENT AVATAR -->
    <div x-show="editingAvatarStudent" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
        <div @click.away="editingAvatarStudent = null" class="bg-slate-900 border border-purple-500/30 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="text-lg font-black text-white flex items-center gap-2">
                    <i data-lucide="camera" class="w-5 h-5 text-purple-400"></i>
                    <span>Cambiar Avatar de Alumno</span>
                </h3>
                <button @click="editingAvatarStudent = null" class="text-slate-400 hover:text-white p-1 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <template x-if="editingAvatarStudent">
                <form method="POST" :action="'/students/' + editingAvatarStudent.id + '/avatar'" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col items-center justify-center gap-2">
                        <img :src="avatarUrl || editingAvatarStudent.avatar" class="w-20 h-20 rounded-full object-cover border-4 border-purple-500 shadow-xl" />
                        <span class="text-sm font-bold text-white" x-text="editingAvatarStudent.name"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">URL de la Imagen / Avatar</label>
                        <input type="url" name="avatar" x-model="avatarUrl" required placeholder="https://..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
                        <button type="button" @click="editingAvatarStudent = null" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold">Cancelar</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold shadow">Guardar Avatar</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
