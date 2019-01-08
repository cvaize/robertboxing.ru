<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class EnableDebugForAdministrator {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$developersIds = [
				1,
		];

		if (auth()->check() && \in_array(auth()->user()->getKey(), $developersIds, true)) {
			Config::set('app.debug', true);
			Config::set('app.debugbar.enabled', true);
		} else {
			Config::set('app.debug', false);
			Config::set('app.debugbar.enabled', false);
		}

		return $next($request);
	}
}
