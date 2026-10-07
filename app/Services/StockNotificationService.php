<?php

namespace App\Services;

use App\Mail\StockAvailableNotificationMail;
use App\Models\Product;
use App\Models\Size;
use App\Models\StockNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StockNotificationService
{
    /**
     * Notify pending subscribers when stock becomes available (>0)
     */
    public static function notifySubscribers(Product $product, ?Size $size = null, int $newStock = 0): int
    {
        if ($newStock <= 0) {
            return 0;
        }

        $query = StockNotification::where('product_id', $product->id)
                                  ->where('is_notified', false);

        if ($size) {
            $query->where(function ($q) use ($size) {
                $q->where('size_id', $size->id)
                  ->orWhereNull('size_id');
            });
        }

        $pendingNotifications = $query->get();
        $notifiedCount = 0;

        foreach ($pendingNotifications as $notification) {
            if (empty($notification->email)) {
                continue;
            }

            try {
                Mail::to($notification->email)->send(new StockAvailableNotificationMail($product, $size));

                $notification->update([
                    'is_notified' => true,
                    'notified_at' => now(),
                ]);

                $notifiedCount++;
            } catch (\Throwable $e) {
                Log::warning("Failed to send stock notification email to {$notification->email}: " . $e->getMessage(), [
                    'product_id' => $product->id,
                    'size_id' => $size?->id,
                ]);
            }
        }

        return $notifiedCount;
    }
}
