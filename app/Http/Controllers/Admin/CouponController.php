<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(): View
    {
        $coupons = Coupon::orderByDesc('id')->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create(): View
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ], [
            'code.required' => 'Kupon kodu zorunludur.',
            'code.unique' => 'Bu kupon kodu zaten kullanımda.',
            'value.required' => 'İndirim değeri zorunludur.',
            'expires_at.after_or_equal' => 'Bitiş tarihi başlangıç tarihinden önce olamaz.',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active', true);

        $coupon = Coupon::create($validated);

        AuditLog::log('coupon_created', 'Coupon', $coupon->id, "Kupon oluşturuldu: {$coupon->code}");

        return redirect()->route('admin.coupons.index')->with('success', "'{$coupon->code}' kuponu başarıyla oluşturuldu.");
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');

        $coupon->update($validated);

        AuditLog::log('coupon_updated', 'Coupon', $coupon->id, "Kupon güncellendi: {$coupon->code}");

        return redirect()->route('admin.coupons.index')->with('success', "'{$coupon->code}' kuponu güncellendi.");
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $code = $coupon->code;
        $coupon->delete();

        AuditLog::log('coupon_deleted', 'Coupon', $coupon->id, "Kupon silindi: {$code}");

        return redirect()->route('admin.coupons.index')->with('success', "'{$code}' kuponu silindi.");
    }
}
