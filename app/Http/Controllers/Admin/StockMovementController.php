<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Display filtered list of stock movements
     */
    public function index(Request $request): View
    {
        $query = StockMovement::with(['product', 'size', 'user'])->orderByDesc('id');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('size_id')) {
            $query->where('size_id', $request->size_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        try {
            $movements = $query->paginate(25)->withQueryString();
        } catch (\Throwable $e) {
            $movements = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 25);
        }

        $products = Product::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::orderBy('sort_order')->get();
        $admins = User::where('is_admin', true)->get();

        return view('admin.stocks.movements', compact('movements', 'products', 'sizes', 'admins'));
    }
}
