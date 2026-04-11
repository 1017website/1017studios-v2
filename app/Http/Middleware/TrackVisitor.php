<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Pages that should NOT be tracked (admin, assets, ajax, etc.)
     */
    private array $skipPrefixes = [
        'admin', '_debugbar', 'telescope', 'horizon',
    ];

    private array $skipExtensions = [
        'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg',
        'ico', 'woff', 'woff2', 'ttf', 'map', 'xml',
    ];

    /**
     * Map URL paths to readable page names.
     */
    private array $pageNames = [
        '/'          => 'Home',
        '/services'  => 'Services',
        '/portfolio' => 'Portfolio',
        '/about'     => 'About',
        '/contact'   => 'Contact',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests that return HTML (status 200)
        if ($request->method() !== 'GET' || $response->getStatusCode() !== 200) {
            return $response;
        }

        $path = trim($request->path(), '/');

        // Skip admin & asset paths
        foreach ($this->skipPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) return $response;
        }

        // Skip static asset extensions
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($ext, $this->skipExtensions)) return $response;

        // Determine readable page name
        $urlPath  = '/' . $path;
        $pageName = $this->pageNames[$urlPath] ?? ucfirst($path ?: 'Home');

        // Detect device
        $ua     = $request->userAgent() ?? '';
        $device = VisitorLog::detectDevice($ua);

        // Parse referrer (strip own domain)
        $referrer = $request->header('referer');
        if ($referrer && str_contains($referrer, $request->getHost())) {
            $referrer = null; // internal nav — not a real referrer
        }
        $referrer = VisitorLog::parseReferrer($referrer);

        try {
            VisitorLog::create([
                'url'        => $request->fullUrl(),
                'page_name'  => $pageName,
                'ip'         => $request->ip(),
                'session_id' => $request->session()->getId(),
                'referrer'   => $referrer,
                'user_agent' => substr($ua, 0, 500),
                'device'     => $device,
            ]);
        } catch (\Throwable $e) {
            // Never break the site due to tracking errors
        }

        return $response;
    }
}
