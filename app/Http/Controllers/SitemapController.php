<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap
     */
    public function index(): Response
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $products = Product::where('is_active', true)->orderByDesc('updated_at')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // 1. Static Core Pages
        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['url' => route('products.index'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['url' => route('about'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ['url' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ['url' => route('size.guide'), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
            ['url' => route('order.track.index'), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => now()->toAtomString()],
        ];

        foreach ($staticPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($page['url']) . '</loc>';
            $xml .= '<lastmod>' . $page['lastmod'] . '</lastmod>';
            $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }

        // 2. Active Categories
        foreach ($categories as $cat) {
            $catUrl = url('/urunler?category=' . $cat->slug);
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($catUrl) . '</loc>';
            $xml .= '<lastmod>' . $cat->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // 3. Active Products
        foreach ($products as $prod) {
            $prodUrl = route('product.detail', $prod->slug);
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($prodUrl) . '</loc>';
            $xml .= '<lastmod>' . $prod->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
