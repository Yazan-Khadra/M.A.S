<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class ImageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Check if the request is for an image
        if ($request->is('storage/images/*')) {
            $response->header('Content-Type', $this->getContentType($request->path()));
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
            $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
            $response->header('Cache-Control', 'public, max-age=31536000'); // Cache for 1 year
        }
        
        return $response;
    }
    
    private function getContentType(string $path): string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            default => 'application/octet-stream',
        };
    }
} 