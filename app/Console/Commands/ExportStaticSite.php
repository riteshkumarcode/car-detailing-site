<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ExportStaticSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:export-static {--target=dist : Output directory relative to project root} {--base-url= : Production domain or base URL}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all public pages, assets, and Netlify configuration into a static build folder ready for Netlify deployment.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetDirName = $this->option('target') ?: 'dist';
        $targetPath = base_path($targetDirName);

        $this->info("🚀 Starting static site export for Netlify to: {$targetPath}");

        // 1. Wipe or prepare target directory
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
        }
        File::makeDirectory($targetPath, 0755, true);

        // 2. Copy compiled public assets
        $this->copyPublicAssets($targetPath);

        // 3. Define routes to export
        $routes = [
            '/' => 'index.html',
            '/services' => 'services/index.html',
            '/free-car-health-check' => 'free-car-health-check/index.html',
            '/book' => 'book/index.html',
            '/drive-club' => 'drive-club/index.html',
            '/gallery' => 'gallery/index.html',
            '/about' => 'about/index.html',
            '/contact' => 'contact/index.html',
            '/faq' => 'faq/index.html',
            '/privacy' => 'privacy/index.html',
            '/terms' => 'terms/index.html',
            '/styleguide' => 'styleguide/index.html',
            '/sitemap.xml' => 'sitemap.xml',
            '/robots.txt' => 'robots.txt',
        ];

        // Add dynamic service detail routes
        $services = Service::where('is_active', true)->get();
        foreach ($services as $service) {
            $routes["/services/{$service->slug}"] = "services/{$service->slug}/index.html";
        }

        // 4. Render and write each route
        $this->info("📄 Rendering " . count($routes) . " static pages...");

        foreach ($routes as $uri => $relativeFile) {
            $outputFilePath = $targetPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativeFile);
            $dir = dirname($outputFilePath);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            try {
                $html = $this->renderUri($uri);

                // Normalise localhost URLs to root-relative paths or custom base url
                $baseUrl = rtrim($this->option('base-url') ?: '', '/');
                $html = str_replace('http://localhost/', ($baseUrl ? $baseUrl . '/' : '/'), $html);
                $html = str_replace('http://localhost', ($baseUrl ?: '/'), $html);

                // Apply Netlify form attributes and enhancements if applicable
                $html = $this->enhanceForNetlify($html, $uri);

                File::put($outputFilePath, $html);
                $this->line("  ✓ Rendered [{$uri}] -> {$relativeFile}");
            } catch (\Throwable $e) {
                $this->error("  ✗ Failed to render [{$uri}]: " . $e->getMessage());
            }
        }

        // 5. Generate custom Netlify 404 page
        $this->generate404Page($targetPath);

        // 6. Generate Netlify _redirects and _headers files
        $this->generateNetlifyFiles($targetPath);

        $this->info("✨ Static site export complete! Output ready in directory: /{$targetDirName}");
        $this->info("💡 You can now deploy this folder to Netlify via Netlify CLI or drag & drop!");

        return Command::SUCCESS;
    }

    /**
     * Copy public build assets, images, icons, and fonts into dist.
     */
    protected function copyPublicAssets(string $targetPath): void
    {
        $this->info("📦 Copying build artifacts and public assets...");

        $sourcePublic = public_path();

        // Copy build directory (Vite compiled CSS/JS)
        if (File::exists($sourcePublic . '/build')) {
            File::copyDirectory($sourcePublic . '/build', $targetPath . '/build');
            $this->line("  ✓ Copied /build (Vite compiled assets)");
        }

        // Copy optional public asset directories
        $subDirs = ['images', 'fonts', 'css', 'js'];
        foreach ($subDirs as $dir) {
            if (File::exists($sourcePublic . '/' . $dir)) {
                File::copyDirectory($sourcePublic . '/' . $dir, $targetPath . '/' . $dir);
                $this->line("  ✓ Copied /{$dir}");
            }
        }

        // Copy individual standalone public root files
        $files = ['favicon.svg', 'favicon.ico', 'manifest.json', 'robots.txt'];
        foreach ($files as $file) {
            if (File::exists($sourcePublic . '/' . $file)) {
                File::copy($sourcePublic . '/' . $file, $targetPath . '/' . $file);
            }
        }
    }

    /**
     * Render the given application URI by executing an internal HTTP GET request.
     */
    protected function renderUri(string $uri): string
    {
        $request = Request::create($uri, 'GET');
        $request->headers->set('Accept', 'text/html,application/xhtml+xml,application/xml');

        $response = app()->handle($request);

        return $response->getContent();
    }

    /**
     * Enhance HTML output for seamless Netlify compatibility.
     * Injects Netlify Form handling so forms work without a backend server,
     * and adds a client-side success banner.
     */
    protected function enhanceForNetlify(string $html, string $uri): string
    {
        // Enhance Contact Form for Netlify Forms
        if ($uri === '/contact') {
            // Transform contact form to include Netlify form tags
            $pattern = '/<form([^>]*)action="[^"]*"([^>]*)method="POST"([^>]*)>/i';
            $replacement = '<form$1action="/contact?status=success"$2method="POST"$3 data-netlify="true" name="contact" netlify-honeypot="bot-field">' . "\n" .
                '    <input type="hidden" name="form-name" value="contact">' . "\n" .
                '    <p class="hidden" style="display:none;"><label>Don’t fill this out: <input name="bot-field" /></label></p>';

            $html = preg_replace($pattern, $replacement, $html);

            // Add client-side success alert script
            $successBannerScript = <<<'HTML'
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.search.includes('status=success')) {
        const banner = document.createElement('div');
        banner.className = 'mb-6 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-900 font-medium flex items-center gap-3';
        banner.innerHTML = '<span class="text-teal-600 text-xl font-bold">✓</span><div><strong>Thank you!</strong> Your message has been received by The Drive Clinic team. We will call or WhatsApp you shortly.</div>';
        const form = document.querySelector('form[name="contact"]');
        if (form && form.parentNode) {
            form.parentNode.insertBefore(banner, form);
        }
    }
});
</script>
HTML;
            $html = str_replace('</body>', $successBannerScript . "\n</body>", $html);
        }

        // Add graceful client-side fallback banner for Livewire booking / health check if standalone
        if (in_array($uri, ['/free-car-health-check', '/book'])) {
            $fallbackNotice = <<<'HTML'
<script>
document.addEventListener('DOMContentLoaded', function() {
    // If backend Livewire endpoint is not reachable, wire form buttons to open direct WhatsApp booking
    const submitBtns = document.querySelectorAll('button[type="submit"]');
    submitBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Check if Livewire has active backend connection
            if (typeof window.Livewire === 'undefined' || !window.Livewire.components) {
                // Standalone mode: fallback to WhatsApp direct booking
                const phoneInput = document.querySelector('input[type="tel"]') || document.querySelector('input[name*="mobile"]');
                const plateInput = document.querySelector('input[name*="plate"]') || document.querySelector('input[placeholder*="JK"]');
                const phone = phoneInput ? phoneInput.value : '';
                const plate = plateInput ? plateInput.value : '';
                
                const text = encodeURIComponent(`Hi Drive Clinic, I would like to book an appointment.\nPhone: ${phone}\nPlate: ${plate}`);
                window.open(`https://wa.me/917006000000?text=${text}`, '_blank');
            }
        });
    });
});
</script>
HTML;
            $html = str_replace('</body>', $fallbackNotice . "\n</body>", $html);
        }

        return $html;
    }

    /**
     * Generate custom 404.html page for Netlify.
     */
    protected function generate404Page(string $targetPath): void
    {
        $content = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found | The Drive Clinic</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Barlow', sans-serif; background-color: #F8F9FA; color: #162422; display: flex; align-items: center; justify-content: center; min-height: 100vh; text-align: center; padding: 20px; box-sizing: border-box; }
        .box { max-width: 520px; background: #fff; padding: 48px 36px; border-radius: 24px; border: 1px solid #E5E7EB; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .badge { display: inline-block; padding: 6px 14px; background: #E8F5F1; color: #163F3A; font-weight: 700; border-radius: 999px; font-size: 13px; margin-bottom: 20px; }
        h1 { font-family: 'Archivo', sans-serif; font-size: 48px; margin: 0 0 12px; color: #163F3A; line-height: 1.1; }
        p { font-size: 17px; color: #526361; line-height: 1.5; margin: 0 0 28px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; background: #163F3A; color: #fff; text-decoration: none; padding: 14px 28px; border-radius: 12px; font-weight: 600; font-size: 15px; transition: background .15s ease; }
        .btn:hover { background: #0E2926; }
    </style>
</head>
<body>
    <div class="box">
        <div class="badge">404 NOT FOUND</div>
        <h1>Vehicle Off-Route</h1>
        <p>The page you are looking for has been moved, removed, or never existed in our diagnostic bay.</p>
        <a href="/" class="btn">Return to Studio Home</a>
    </div>
</body>
</html>
HTML;
        File::put($targetPath . '/404.html', $content);
        $this->line("  ✓ Generated custom Netlify 404 page (404.html)");
    }

    /**
     * Generate Netlify _redirects file for hybrid backend routing and fallback handling.
     */
    protected function generateNetlifyFiles(string $targetPath): void
    {
        $redirectsContent = <<<'TXT'
# ==============================================================================
# Netlify Redirects & Proxies — The Drive Clinic
# ==============================================================================

# Hybrid API & Admin Proxy (uncomment and replace URL when Laravel backend is deployed):
# /admin/*      https://your-laravel-api.example.com/admin/:splat      200!
# /livewire/*   https://your-laravel-api.example.com/livewire/:splat   200!
# /api/*        https://your-laravel-api.example.com/api/:splat        200!

# Pretty URL routing
/services/ceramic-coating-9h    /services/ceramic-coating-9h/index.html    200
/services/engine-bay-decontamination /services/engine-bay-decontamination/index.html 200
/services/essential-foam-wash   /services/essential-foam-wash/index.html   200
/services/interior-deep-clean   /services/interior-deep-clean/index.html   200
/services/paint-correction-gloss /services/paint-correction-gloss/index.html 200
/services/underbody-anti-rust   /services/underbody-anti-rust/index.html   200

# 404 Fallback
/*    /404.html    404
TXT;
        File::put($targetPath . '/_redirects', $redirectsContent);
        $this->line("  ✓ Generated Netlify _redirects");
    }
}
