<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display About page
     */
    public function about(): View
    {
        return view('pages.about');
    }

    /**
     * Display Contact page
     */
    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * Submit Contact Form
     */
    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email_or_phone' => 'required|string|min:5|max:100',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Lütfen adınızı ve soyadınızı giriniz.',
            'email_or_phone.required' => 'Lütfen telefon numarası veya e-posta adresi giriniz.',
            'message.required' => 'Lütfen iletmek istediğiniz mesajınızı yazınız.',
            'message.min' => 'Mesajınız en az 10 karakter olmalıdır.',
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email_or_phone' => $validated['email_or_phone'],
            'subject' => $validated['subject'] ?? 'Genel İletişim',
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Mesajınız başarıyla iletildi. En kısa sürede tarafınıza dönüş yapılacaktır.');
    }

    /**
     * Size Guide Page / Modal content
     */
    public function sizeGuide(): View
    {
        return view('pages.size-guide');
    }
}
