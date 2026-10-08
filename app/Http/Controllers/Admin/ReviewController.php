<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display all customer reviews with status filters
     */
    public function index(Request $request): View
    {
        $query = ProductReview::with(['product', 'user', 'order'])->orderByDesc('created_at');

        $status = $request->query('status');
        if ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'pending') {
            $query->where('is_approved', false);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('author_name', 'like', "%{$search}%")
                  ->orWhere('author_email', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        try {
            $reviews = $query->paginate(20)->withQueryString();

            $stats = [
                'total' => ProductReview::count(),
                'pending' => ProductReview::where('is_approved', false)->count(),
                'approved' => ProductReview::where('is_approved', true)->count(),
                'avg_rating' => (float) round(ProductReview::avg('rating') ?? 5.0, 1),
            ];
        } catch (\Throwable $e) {
            $reviews = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $stats = [
                'total' => 0,
                'pending' => 0,
                'approved' => 0,
                'avg_rating' => 5.0,
            ];
        }

        return view('admin.reviews.index', compact('reviews', 'stats', 'status', 'search'));
    }

    /**
     * Approve review
     */
    public function approve(ProductReview $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        AuditLog::record(
            'review_approved',
            ProductReview::class,
            $review->id,
            "Ürün yorumu #{$review->id} onaylandı ve yayına alındı."
        );

        return back()->with('success', 'Yorum onaylandı ve sitede yayına alındı.');
    }

    /**
     * Reject / unapprove review
     */
    public function reject(ProductReview $review): RedirectResponse
    {
        $review->update(['is_approved' => false]);

        AuditLog::record(
            'review_rejected',
            ProductReview::class,
            $review->id,
            "Ürün yorumu #{$review->id} yayından kaldırıldı (beklemede)."
        );

        return back()->with('success', 'Yorum yayından kaldırıldı.');
    }

    /**
     * Delete review
     */
    public function destroy(ProductReview $review): RedirectResponse
    {
        AuditLog::record(
            'review_deleted',
            ProductReview::class,
            $review->id,
            "Ürün yorumu #{$review->id} silindi: ({$review->author_name})"
        );

        $review->delete();

        return back()->with('success', 'Yorum kalıcı olarak silindi.');
    }
}
