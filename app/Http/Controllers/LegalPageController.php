<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    private function renderLegal(string $title, string $settingKey, string $defaultContent): View
    {
        $content = Setting::where('key', $settingKey)->value('value') ?: $defaultContent;

        return view('pages.legal.page', [
            'title' => $title,
            'pageTitle' => $title,
            'content' => $content,
            'contentKey' => $settingKey,
            'defaultContent' => $defaultContent,
        ]);
    }

    public function kvkk(): View
    {
        return $this->renderLegal(
            'KVKK Aydınlatma Metni',
            'legal_kvkk',
            'VELORA olarak 6698 sayılı Kişisel Verilerin Korunması Kanunu (“KVKK”) uyarınca, veri sorumlusu sıfatıyla kişisel verilerinizin güvenliğine ve gizliliğine azami önem vermekteyiz.'
        );
    }

    public function privacy(): View
    {
        return $this->renderLegal(
            'Gizlilik Politikası',
            'legal_privacy',
            'VELORA web sitesini ziyaret eden kullanıcılarımızın gizlilik haklarını korumak temel ilkemizdir. Kişisel bilgileriniz üçüncü şahıslarla ticari amaçla paylaşılmaz.'
        );
    }

    public function cookies(): View
    {
        return $this->renderLegal(
            'Çerez Politikası',
            'legal_cookies',
            'Sitemizde kullanıcı deneyimini iyileştirmek, sepet ve oturum işlevlerini sağlamak amacıyla çerezler (cookies) kullanılmaktadır.'
        );
    }

    public function distanceSelling(): View
    {
        return $this->renderLegal(
            'Mesafeli Satış Sözleşmesi',
            'legal_distance_selling',
            'İşbu sözleşme 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği hükümleri uyarınca düzenlenmiştir.'
        );
    }

    public function preInfo(): View
    {
        return $this->renderLegal(
            'Ön Bilgilendirme Formu',
            'legal_pre_info',
            'Tüketici, mesafeli sözleşmenin kurulmasından önce satıcının unvanı, iletişim bilgileri, ürünün temel nitelikleri ve toplam fiyatı konusunda bilgilendirilmiştir.'
        );
    }

    public function returnPolicy(): View
    {
        return $this->renderLegal(
            'İade ve Değişim Politikası',
            'legal_return_policy',
            'Satın aldığınız ürünleri teslim aldığınız tarihten itibaren 14 gün içerisinde orijinal kutusunda, kullanılmamış ve faturalı olarak kolayca iade edebilir veya numara değişimi talep edebilirsiniz.'
        );
    }
}
