<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    /**
     * Store new customer review for a product
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'author_name' => $user ? 'nullable|string|max:100' : 'required|string|max:100',
            'author_email' => 'nullable|email|max:150',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:1500',
        ], [
            'rating.required' => 'Lütfen 1 ile 5 arasında bir puan seçiniz.',
            'author_name.required' => 'Adınızı ve soyadınızı giriniz.',
            'comment.required' => 'Yorumunuzu yazınız.',
            'comment.min' => 'Yorumunuz en az 5 karakter olmalıdır.',
        ]);

        $authorEmail = $user ? $user->email : ($validated['author_email'] ?? null);
        $authorName = $user ? $user->name : ($validated['author_name'] ?? 'Müşteri');

        // Check if verified purchaser
        $isVerifiedPurchase = false;
        $orderId = null;

        $matchingItem = OrderItem::where('product_id', $product->id)
                                 ->whereHas('order', function ($q) use ($user, $authorEmail) {
                                     $q->whereIn('status', ['tamamlandi', 'teslim_edildi'])
                                       ->where(function ($sub) use ($user, $authorEmail) {
                                           if ($user) {
                                               $sub->where('user_id', $user->id)
                                                   ->orWhere('customer_email', $user->email);
                                           } elseif ($authorEmail) {
                                               $sub->where('customer_email', $authorEmail);
                                           }
                                       });
                                 })
                                 ->first();

        if ($matchingItem) {
            $isVerifiedPurchase = true;
            $orderId = $matchingItem->order_id;
        }

        $review = ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'order_id' => $orderId,
            'author_name' => $authorName,
            'author_email' => $authorEmail,
            'rating' => (int) $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'is_approved' => false,
            'is_verified_purchase' => $isVerifiedPurchase,
        ]);

        AuditLog::record(
            'product_review_submitted',
            ProductReview::class,
            $review->id,
            "{$authorName}, '{$product->name}' için {$review->rating} yıldızlı yorum gönderdi."
        );

        return back()->with('success', 'Değerlendirmeniz ve yorumunuz alındı. Yönetici onayının ardından ürün sayfasında yayınlanacaktır. Teşekkür ederiz!');
    }
}
