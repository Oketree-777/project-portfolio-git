<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store or update rating
     */
    public function store(Request $request, PersonalInfo $personalInfo)
    {
        // If not AJAX request, redirect to portfolio page
        if (!request()->ajax()) {
            return redirect()->route('personal-info.show', $personalInfo->id);
        }

        // Check if portfolio is approved
        if (!$personalInfo->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถให้คะแนนผลงานที่ยังไม่ได้รับการอนุมัติได้'
            ], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ], [
            'rating.required' => 'กรุณาให้คะแนน',
            'rating.integer' => 'คะแนนต้องเป็นตัวเลข',
            'rating.min' => 'คะแนนต้องไม่น้อยกว่า 1 ดาว',
            'rating.max' => 'คะแนนต้องไม่เกิน 5 ดาว',
            'comment.max' => 'ความคิดเห็นต้องไม่เกิน 500 ตัวอักษร'
        ]);

        try {
            $userId = Auth::id();

            // Check if user already rated this portfolio
            $existingRating = Rating::where('user_id', $userId)
                ->where('personal_info_id', $personalInfo->id)
                ->first();

            if ($existingRating) {
                // Update existing rating
                $existingRating->update([
                    'rating' => $request->rating,
                    'comment' => $request->comment
                ]);
                $message = 'อัปเดตคะแนนเรียบร้อยแล้ว';
            } else {
                // Create new rating
                Rating::create([
                    'user_id' => $userId,
                    'personal_info_id' => $personalInfo->id,
                    'rating' => $request->rating,
                    'comment' => $request->comment
                ]);
                $message = 'ให้คะแนนเรียบร้อยแล้ว';
            }

            // Get updated rating statistics
            $ratingStats = $this->getRatingStats($personalInfo->id);

            return response()->json([
                'success' => true,
                'message' => $message,
                'rating_stats' => $ratingStats
            ]);
        } catch (\Exception $e) {
            \Log::error('Rating error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการบันทึกคะแนน: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rating statistics for a portfolio
     */
    public function getRatingStats($personalInfoId)
    {
        $ratings = Rating::where('personal_info_id', $personalInfoId)->get();
        
        if ($ratings->isEmpty()) {
            return [
                'average_rating' => 0,
                'average_percentage' => 0,
                'total_ratings' => 0,
                'rating_distribution' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]
            ];
        }

        $totalRatings = $ratings->count();
        $averageRating = $ratings->avg('rating');
        $averagePercentage = ($averageRating / 5) * 100;

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $ratings->where('rating', $i)->count();
        }

        return [
            'average_rating' => round($averageRating, 1),
            'average_percentage' => round($averagePercentage, 1),
            'total_ratings' => $totalRatings,
            'rating_distribution' => $ratingDistribution
        ];
    }

    /**
     * Realtime stats for a portfolio (views + rating aggregates)
     */
    public function realtimeStats(PersonalInfo $personalInfo)
    {
        // If not AJAX request, return JSON anyway (lightweight endpoint)
        $stats = $this->getRatingStats($personalInfo->id);

        return response()->json([
            'success' => true,
            'views' => (int) $personalInfo->views,
            'average_rating' => $stats['average_rating'],
            'average_percentage' => $stats['average_percentage'],
            'total_ratings' => $stats['total_ratings']
        ]);
    }

    /**
     * Get user's rating for a portfolio
     */
    public function getUserRating(PersonalInfo $personalInfo)
    {
        // If not AJAX request, redirect to portfolio page
        if (!request()->ajax()) {
            return redirect()->route('personal-info.show', $personalInfo->id);
        }

        $userId = Auth::id();
        
        $rating = Rating::where('user_id', $userId)
            ->where('personal_info_id', $personalInfo->id)
            ->first();

        return response()->json([
            'success' => true,
            'rating' => $rating ? [
                'rating' => $rating->rating,
                'comment' => $rating->comment,
                'created_at' => $rating->created_at->format('d/m/Y H:i')
            ] : null
        ]);
    }

    /**
     * Delete user's rating
     */
    public function destroy(PersonalInfo $personalInfo)
    {
        // If not AJAX request, redirect to portfolio page
        if (!request()->ajax()) {
            return redirect()->route('personal-info.show', $personalInfo->id);
        }

        $userId = Auth::id();
        
        $rating = Rating::where('user_id', $userId)
            ->where('personal_info_id', $personalInfo->id)
            ->first();

        if ($rating) {
            $rating->delete();
            
            // Get updated rating statistics
            $ratingStats = $this->getRatingStats($personalInfo->id);
            
            return response()->json([
                'success' => true,
                'message' => 'ลบคะแนนเรียบร้อยแล้ว',
                'rating_stats' => $ratingStats
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'ไม่พบคะแนนที่ต้องการลบ'
        ], 404);
    }

    /**
     * Get all ratings for a portfolio (for admin)
     */
    public function getPortfolioRatings(PersonalInfo $personalInfo)
    {
        // If not AJAX request, redirect to portfolio page
        if (!request()->ajax()) {
            return redirect()->route('personal-info.show', $personalInfo->id);
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $ratings = Rating::where('personal_info_id', $personalInfo->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'ratings' => $ratings->map(function ($rating) {
                return [
                    'id' => $rating->id,
                    'user_name' => $rating->user->name,
                    'rating' => $rating->rating,
                    'comment' => $rating->comment,
                    'created_at' => $rating->created_at->format('d/m/Y H:i')
                ];
            })
        ]);
    }
}