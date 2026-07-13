<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userlevel = Auth::user()->userlevel;

        // Map path pattern to disallowed userlevels (blacklist)
        $disallowedMap = [
            'manage-events'            => [6],
            'manage-events/*'          => [6],
            'manage-trucks'            => [3, 5, 7, 9],
            'manage-trucks/*'          => [3, 5, 7, 9],
            'manage-inventory'         => [3, 5, 7, 9],
            'manage-inventory/*'       => [3, 5, 7, 9],
            'manage-regions'           => [3, 4, 5, 6, 7, 8, 9],
            'manage-regions/*'         => [3, 4, 5, 6, 7, 8, 9],
            'manage-social-media'      => [3, 5, 7, 9],
            'manage-social-media/*'    => [3, 5, 7, 9],
            'manage-countries'         => [3, 4, 5, 6, 7, 8, 9],
            'manage-countries/*'       => [3, 4, 5, 6, 7, 8, 9],
            'manage-dealers'           => [3, 4, 5, 6, 7, 8, 9],
            'manage-dealers/*'         => [3, 4, 5, 6, 7, 8, 9],
            'manage-restricted-riders' => [3, 5, 7, 9],
            'manage-restricted-riders/*'=> [3, 5, 7, 9],
            'manage-groups'            => [3, 5, 7, 9],
            'manage-groups/*'          => [3, 5, 7, 9],
            'manage-signed-waivers'    => [3, 5, 7, 9],
            'manage-signed-waivers/*'  => [3, 5, 7, 9],
            'manage-import-vehicles'   => [3, 4, 5, 6, 7, 9],
            'manage-import-vehicles/*' => [3, 4, 5, 6, 7, 9],
            'manage-models'            => [3, 5, 7, 9],
            'manage-models/*'          => [3, 5, 7, 9],
            'manage-users'             => [3, 4, 5, 6, 7, 8, 9],
            'manage-users/*'            => [3, 4, 5, 6, 7, 8, 9],
            'manage-waivers'           => [3, 5, 6, 7, 9],
            'manage-waivers/*'         => [3, 5, 6, 7, 9],
            'manage-email-templates'   => [3, 5, 7, 9],
            'manage-email-templates/*' => [3, 5, 7, 9],
            'manage-sms-templates'     => [3, 5, 7, 9],
            'manage-sms-templates/*'   => [3, 5, 7, 9],
            'manage-surveys'           => [3, 4, 5, 6, 7, 8, 9],
            'manage-surveys/*'         => [3, 4, 5, 6, 7, 8, 9],
            'manage-survey-questions'  => [3, 4, 5, 6, 7, 8, 9],
            'manage-survey-questions/*'=> [3, 4, 5, 6, 7, 8, 9],
            'manage-survey-answers'    => [3, 4, 5, 6, 7, 8, 9],
            'manage-survey-answers/*'  => [3, 4, 5, 6, 7, 8, 9],
        ];

        foreach ($disallowedMap as $pattern => $levels) {
            if ($request->is($pattern) && in_array($userlevel, $levels)) {
                return redirect()->back(fallback: '/')->with('msg', 'You do not have permission to access this page');
            }
        }

        // Map path pattern to allowed userlevels (whitelist)
        $allowedMap = [
            'manage-data-management'   => [1, 2],
            'manage-data-management/*' => [1, 2],
            'manage-pre-reg-email'     => [1, 2],
            'manage-pre-reg-email/*'   => [1, 2],
            'manage-pre-reg-html'      => [1, 2],
            'manage-pre-reg-html/*'    => [1, 2],
            'generate-cards'           => [1],
            'generate-cards/*'         => [1],
        ];

        foreach ($allowedMap as $pattern => $levels) {
            if ($request->is($pattern) && !in_array($userlevel, $levels)) {
                return redirect()->back(fallback: '/')->with('msg', 'You do not have permission to access this page');
            }
        }

        return $next($request);
    }
}
