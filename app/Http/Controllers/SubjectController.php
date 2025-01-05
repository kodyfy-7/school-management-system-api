<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    public function getAllSubjects(Request $request)
    {
        try {
            $search = $request->input('search');
            $perPage = $request->input('per_page', 25);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');

            $subjects = Subject::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage);

            return response()->json(['success' => true, 'data' => $subjects], 200);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createSubject(CreateSubjectRequest $request)
    {
        try {
            $subject = Subject::create([
                'name' => $request->name,
                'teacher_id' => $request->teacher_id,
                'description' => $request->description,
                'status' => $request->status,
                'created_by' => $request->user()->id,
            ]);

            return response()->json(['success' => true, 'data' => $subject, 'message' => 'Resource created successfully.'], 200);
        } catch (Exception $e) {
            Log::info($e);

            return response()->json([
                'success' => false,
                'message' => 'System optimization in progress, please wait',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateSubject(UpdateSubjectRequest $request, $subjectId)
    {
        try {
            $subject = Subject::where('id', $subjectId)->first();
            if (! $subject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                ], 404);
            }

            $subject->update([
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
