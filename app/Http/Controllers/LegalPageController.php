<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    public function kvkk(): View
    {
        return view('pages.legal.page', [
            'pageTitle' => 'KVKK Aydınlatma Metni',
            'contentKey' => 'legal_kvkk',
            'defaultContent' => 'VELORA olarak 6698 sayılı Kişisel Verilerin Korunması Kanunu (“KVKK”) uyarınca, veri sorumlusu sıfatıyla kişisel verilerinizin güvenliğine ve gizliliğine azami önem vermekteyiz.',
        ]);
    }

    public function privacy(): View
    {
        return view('pages.legal.page', [
            'pageTitle' => 'Gizlilik Politikası',
            'contentKey' => 'legal_privacy',
            'defaultContent' => 'VELORA web sitesini ziyaret eden kullanıcılarımızın gizlilik haklarını korumak temel ilkemizdir. Kişisel bilgileriniz üçüncü şahıslarla ticari amaçla paylaşılmaz.',
        ]);
    }

    public function cookies(): View
    {
        return view('pages.legal.page', [
            'pageTitle' => 'Çerez Politikası',
            'contentKey' => 'legal_cookies',
            'defaultContent' => 'Sitemizde kullanıcı deneyimini iyileştirmek, sepet ve oturum işlevlerini sağlamak amacıyla çerezler (cookies) kullanılmaktadır.',
        ]);
    }

    public function distanceSelling(): View
    {
        return view('pages.legal.page', [
            'pageTitle' => 'Mesafeli Satış Sözleşmesi',
            'contentKey' => 'legal_distance_selling',
            'defaultContent' => 'İşbu sözleşme 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği hükümleri uyarınca düzenlenmiştir.',
        ]);
    }

    public function preInformation(): View
    {
        return view('pages.legal.page', [
            'pageTitle' => 'Ön Bilgilendirme Formu',
            'contentKey' => 'legal_pre_info',
            'defaultContent' => 'Tüketici, mesafeli sözleşmenin kurulmasından önce satıcının unvanı, iletişim bilgileri, ürünün temel nitelikleri ve toplam fiyatı konusunda bilgilendirilmiştir.',
        ]);
    }

    public function returnPolicy(): View
    {
        return view('pages.legal.page', [
            'pageTitle' => 'İade ve Değişim Politikası',
            'contentKey' => 'legal_return_policy',
            'defaultContent' => 'Satın aldığınız ürünleri teslim aldığınız tarihten itibaren 14 gün içerisinde orijinal kutusunda, kullanılmamış ve faturalı olarak kolayca iade edebilir veya numara değişimi talep edebilirsiniz.',
        ]);
    }
}
