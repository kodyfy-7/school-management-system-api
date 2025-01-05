<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Grade;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    public function getAllGrades(Request $request)
    {
        try {
            $search = $request->input('search');
            $perPage = $request->input('per_page', 25);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');

            $grades = Grade::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage);

            return response()->json(['success' => true, 'data' => $grades], 200);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createGrade(CreateGradeRequest $request)
    {
        try {
            $grade = Grade::create([
                'name' => $request->name,
                'teacher_id' => $request->teacher_id,
                'description' => $request->description,
                'status' => $request->status,
                'created_by' => $request->user()->id,
            ]);

            return response()->json(['success' => true, 'data' => $grade, 'message' => 'Resource created successfully.'], 200);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateGrade(UpdateGradeRequest $request, $gradeId)
    {
        try {
            $grade = Grade::where('id', $gradeId)->first();
            if (! $grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                ], 404);
            }

            $grade->update([
                'name' => $request->name,
                'teacher_id' => $request->teacher_id,
                'description' => $request->description,
                'status' => $request->status,
                'updated_by' => $request->user()->id,
            ]);

            return response()->json(['success' => true, 'message' => 'Resource updated successfully.'], 200);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
