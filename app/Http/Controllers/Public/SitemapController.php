<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $services = Service::where('is_active', true)->get();
        $baseUrl = url('/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $staticRoutes = [
            '',
            '/services',
            '/free-car-health-check',
            '/book',
            '/drive-club',
            '/gallery',
            '/about',
            '/contact',
            '/faq',
            '/privacy',
            '/terms',
        ];

        foreach ($staticRoutes as $route) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . $route . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>' . ($route === '' ? '1.0' : '0.8') . '</priority>';
            $xml .= '</url>';
        }

        foreach ($services as $service) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/services/' . $service->slug . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $baseUrl = url('/');
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /passport/\n";
        $content .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
