<?php

if (! function_exists('returnScriptWithNonce')) {
    function returnScriptWithNonce(string $path): string
    {
        return '<script nonce="' . csp_nonce('script') . '" src="' . $path . '"></script>';
    }
}