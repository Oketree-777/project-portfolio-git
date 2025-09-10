<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PortfolioApiController extends Controller
{
    /**
     * Display a listing of portfolios
     */
    public function index(Request $request): JsonResponse
    {
        $query = PersonalInfo::with(['user'])
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc');

        // Filter by faculty
        if ($request->has('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        // Filter by major
        if ($request->has('major')) {
            $query->where('major', $request->major);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_th', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $portfolios = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $portfolios,
            'message' => 'Portfolios retrieved successfully'
        ]);
    }

    /**
     * Display the specified portfolio
     */
    public function show(PersonalInfo $portfolio): JsonResponse
    {
        if (!$portfolio->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $portfolio->load(['user']),
            'message' => 'Portfolio retrieved successfully'
        ]);
    }

    /**
     * Get portfolios by faculty
     */
    public function byFaculty(string $faculty): JsonResponse
    {
        $portfolios = PersonalInfo::with(['user'])
            ->where('faculty', $faculty)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $portfolios,
            'message' => "Portfolios for faculty {$faculty} retrieved successfully"
        ]);
    }

    /**
     * Get portfolios by major
     */
    public function byMajor(string $major): JsonResponse
    {
        $portfolios = PersonalInfo::with(['user'])
            ->where('major', $major)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $portfolios,
            'message' => "Portfolios for major {$major} retrieved successfully"
        ]);
    }

    /**
     * Search portfolios
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $search = $request->q;
        $portfolios = PersonalInfo::with(['user'])
            ->where('is_approved', true)
            ->where(function($query) use ($search) {
                $query->where('title_th', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('faculty', 'like', "%{$search}%")
                      ->orWhere('major', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $portfolios,
            'message' => "Search results for '{$search}'"
        ]);
    }

    /**
     * Get user's own portfolios
     */
    public function myPortfolios(): JsonResponse
    {
        $portfolios = PersonalInfo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $portfolios,
            'message' => 'Your portfolios retrieved successfully'
        ]);
    }

    /**
     * Store a newly created portfolio
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title_th' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'semester' => 'required|integer|min:1|max:3',
            'gpa' => 'required|numeric|min:0|max:4',
            'subject_gpa' => 'required|numeric|min:0|max:4',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('portfolio-photos', 'public');
            $data['photo'] = $photoPath;
        }

        $portfolio = PersonalInfo::create($data);

        return response()->json([
            'success' => true,
            'data' => $portfolio,
            'message' => 'Portfolio created successfully'
        ], 201);
    }

    /**
     * Update the specified portfolio
     */
    public function update(Request $request, PersonalInfo $portfolio): JsonResponse
    {
        // Check if user owns this portfolio or is admin
        if ($portfolio->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title_th' => 'sometimes|required|string|max:255',
            'title_en' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'faculty' => 'sometimes|required|string|max:255',
            'major' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:2000|max:' . (date('Y') + 1),
            'semester' => 'sometimes|required|integer|min:1|max:3',
            'gpa' => 'sometimes|required|numeric|min:0|max:4',
            'subject_gpa' => 'sometimes|required|numeric|min:0|max:4',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $data = $validator->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('portfolio-photos', 'public');
            $data['photo'] = $photoPath;
        }

        $portfolio->update($data);

        return response()->json([
            'success' => true,
            'data' => $portfolio,
            'message' => 'Portfolio updated successfully'
        ]);
    }

    /**
     * Remove the specified portfolio
     */
    public function destroy(PersonalInfo $portfolio): JsonResponse
    {
        // Check if user owns this portfolio or is admin
        if ($portfolio->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $portfolio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Portfolio deleted successfully'
        ]);
    }
}
