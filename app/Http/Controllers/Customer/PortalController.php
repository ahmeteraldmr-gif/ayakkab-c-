<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PortalController extends Controller
{
    /**
     * Customer Dashboard overview
     */
    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $recentOrders = $user->orders()->take(5)->get();
        $totalOrdersCount = $user->orders()->count();
        $pendingOrdersCount = $user->orders()->whereIn('status', ['yeni', 'hazirlaniyor', 'kargoda', 'kargolandi'])->count();
        $savedAddressesCount = $user->addresses()->count();
        $defaultAddress = $user->defaultAddress();

        $stats = [
            'total_orders' => $totalOrdersCount,
            'active_orders' => $pendingOrdersCount,
            'delivered_orders' => $user->orders()->whereIn('status', ['teslim_edildi', 'tamamlandi'])->count(),
            'saved_addresses' => $savedAddressesCount,
        ];

        return view('pages.customer.dashboard', compact(
            'user',
            'recentOrders',
            'totalOrdersCount',
            'pendingOrdersCount',
            'savedAddressesCount',
            'defaultAddress',
            'stats'
        ));
    }

    /**
     * Customer Orders list
     */
    public function orders(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $orders = $user->orders()->paginate(10);

        return view('pages.customer.orders', compact('orders'));
    }

    /**
     * Customer Order detail (Protected against IDOR)
     */
    public function orderDetail(string $orderNumber): View
    {
        /** @var User $user */
        $user = Auth::user();

        $order = Order::with(['items', 'returnRequests'])
                      ->where('order_number', $orderNumber)
                      ->where('user_id', $user->id)
                      ->firstOrFail();

        return view('pages.customer.order-detail', compact('order'));
    }

    /**
     * Customer Profile & Password view
     */
    public function profile(): View
    {
        $user = Auth::user();
        return view('pages.customer.profile', compact('user'));
    }

    /**
     * Update customer profile info
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:25',
        ], [
            'name.required' => 'Ad Soyad zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.unique' => 'Bu e-posta adresi başka bir hesap tarafından kullanılıyor.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Mevcut şifrenizi giriniz.',
            'current_password.current_password' => 'Mevcut şifreniz hatalı.',
            'password.required' => 'Yeni şifrenizi giriniz.',
            'password.min' => 'Yeni şifreniz en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Yeni şifreleriniz uyuşmuyor.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Şifreniz başarıyla değiştirildi.');
    }

    /**
     * Saved Addresses list
     */
    public function addresses(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $addresses = $user->addresses;

        return view('pages.customer.addresses', compact('addresses'));
    }

    /**
     * Store new customer address
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:25',
            'city' => 'required|string|max:50',
            'district' => 'required|string|max:50',
            'address' => 'required|string',
            'is_default' => 'nullable|boolean',
        ], [
            'title.required' => 'Adres başlığı (Örn: Evim, İşyeri) zorunludur.',
            'full_name.required' => 'Alıcı adı zorunludur.',
            'phone.required' => 'Telefon numarası zorunludur.',
            'city.required' => 'İl seçiniz.',
            'district.required' => 'İlçe giriniz.',
            'address.required' => 'Açık adres zorunludur.',
        ]);

        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'title' => $validated['title'],
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'address' => $validated['address'],
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'Yeni adresiniz başarıyla kaydedildi.');
    }

    /**
     * Update existing address
     */
    public function updateAddress(Request $request, CustomerAddress $address): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:25',
            'city' => 'required|string|max:50',
            'district' => 'required|string|max:50',
            'address' => 'required|string',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
            $address->is_default = true;
        }

        $address->update($validated);

        return back()->with('success', 'Adres bilgileriniz güncellendi.');
    }

    /**
     * Delete address
     */
    public function deleteAddress(CustomerAddress $address): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Adres başarıyla silindi.');
    }

    public function destroyAddress(CustomerAddress $address): RedirectResponse
    {
        return $this->deleteAddress($address);
    }

    /**
     * Set address as default
     */
    public function setDefaultAddress(CustomerAddress $address): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', "'{$address->title}' varsayılan teslimat adresi olarak ayarlandı.");
    }

    /**
     * Create Return or Exchange Request
     */
    public function storeReturnRequest(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'order_item_id' => 'nullable|integer|exists:order_items,id',
            'type' => 'required|in:return,exchange',
            'reason' => 'required|string|min:10|max:1000',
            'requested_size' => 'nullable|string|max:20',
        ], [
            'type.required' => 'Lütfen talep türünü (İade veya Değişim) seçiniz.',
            'reason.required' => 'Lütfen talep nedeninizi açıklayınız.',
            'reason.min' => 'Neden açıklaması en az 10 karakter olmalıdır.',
        ]);

        $order = Order::where('id', $validated['order_id'])
                      ->where('user_id', $user->id)
                      ->firstOrFail();

        if (!in_array($order->status, ['tamamlandi', 'teslim_edildi'])) {
            return back()->with('error', 'Yalnızca teslim edilmiş siparişler için iade veya değişim talebinde bulunabilirsiniz.');
        }

        // Verify order_item belongs to this specific order if provided
        if (!empty($validated['order_item_id'])) {
            $itemBelongs = OrderItem::where('id', $validated['order_item_id'])
                                    ->where('order_id', $order->id)
                                    ->exists();
            if (!$itemBelongs) {
                return back()->with('error', 'Seçilen ürün bu siparişe ait değildir.');
            }
        }

        // Check for existing active return/exchange request on this item/order
        $existingActiveQuery = ReturnRequest::where('order_id', $order->id)
                                            ->whereIn('status', ['bekliyor', 'inceleniyor', 'onaylandi']);
        if (!empty($validated['order_item_id'])) {
            $existingActiveQuery->where('order_item_id', $validated['order_item_id']);
        }

        if ($existingActiveQuery->exists()) {
            return back()->with('error', 'Bu ürün/sipariş için halen işlemde olan aktif bir iade veya değişim talebiniz bulunmaktadır.');
        }

        $returnRequest = ReturnRequest::create([
            'order_id' => $order->id,
            'order_item_id' => $validated['order_item_id'] ?? null,
            'user_id' => $user->id,
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'requested_size' => $validated['requested_size'] ?? null,
            'status' => 'bekliyor',
        ]);

        AuditLog::record(
            'return_request_created',
            ReturnRequest::class,
            $returnRequest->id,
            "Müşteri ({$user->name}) Sipariş #{$order->order_number} için {$returnRequest->type_label} talebi oluşturdu."
        );

        return back()->with('success', 'İade / Değişim talebiniz başarıyla alındı. İnceleme sonrası sizinle iletişime geçilecektir.');
    }

    public function submitReturnRequest(Request $request): RedirectResponse
    {
        return $this->storeReturnRequest($request);
    }
}
