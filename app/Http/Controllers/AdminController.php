<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display Admin Panel for managing groups, teacher info, avatars, and students
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $groups = ClassGroup::where('is_deleted', false)->get();
        $selectedGroupId = $request->get('group_id', $groups->first()?->id);
        
        $currentGroup = ClassGroup::find($selectedGroupId);

        $students = [];
        if ($currentGroup) {
            $students = Student::where('class_group_id', $currentGroup->id)
                ->where('is_deleted', false)
                ->with(['incidences', 'gradeHistories'])
                ->get();
        }

        return view('admin.index', [
            'teacher' => $teacher,
            'groups' => $groups,
            'currentGroup' => $currentGroup,
            'students' => $students,
        ]);
    }

    /**
     * Update Teacher Name and Profile Details
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Nombre del profesor y datos de perfil actualizados.');
    }

    /**
     * Create a new Class Group
     */
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'total_weeks' => 'required|integer|min:1',
            'current_week' => 'required|integer|min:1',
        ]);

        $group = ClassGroup::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'grade_level' => $request->get('grade_level', 'General'),
            'academic_year' => $request->get('academic_year', '2025-2026'),
            'total_practices' => 5,
            'total_weeks' => $request->total_weeks,
            'current_week' => $request->current_week,
            'week_status' => 'normal',
        ]);

        return redirect()->route('admin.index', ['group_id' => $group->id])
            ->with('success', "Grupo '{$group->name}' creado con éxito.");
    }

    /**
     * Delete a Class Group
     */
    public function destroyGroup($id)
    {
        $group = ClassGroup::findOrFail($id);
        $group->update(['is_deleted' => true]);

        return redirect()->route('admin.index')
            ->with('success', "Grupo '{$group->name}' eliminado correctamente.");
    }
}
