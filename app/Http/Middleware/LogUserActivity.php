<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Logs every state-changing request (POST/PUT/PATCH/DELETE) of a logged-in user.
 * Only field NAMES are stored, never values, so passwords/NID/bank data never reach the log.
 */
class LogUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && ! $request->isMethodSafe()) {
            try {
                $name = $request->route()?->getName() ?? $request->path();
                $last = Str::afterLast($name, '.');

                $verb = match ($last) {
                    'store'         => 'Created',
                    'update'        => 'Updated',
                    'destroy'       => 'Deleted',
                    'update-status' => 'Changed status of',
                    'read', 'read-all' => 'Marked as read',
                    default         => Str::headline($last),
                };

                $subject = Str::headline(Str::singular(
                    (string) Str::of($name)->beforeLast('.')->afterLast('.')
                ));

                ActivityLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => Str::after($name, '.'),
                    'description' => trim("$verb $subject"),
                    'method'      => $request->method(),
                    'route_name'  => $name,
                    'url'         => Str::limit($request->fullUrl(), 500, ''),
                    'status_code' => $response->getStatusCode(),
                    'ip_address'  => $request->ip(),
                    'user_agent'  => Str::limit((string) $request->userAgent(), 500, ''),
                    'properties'  => [
                        'route_params' => collect($request->route()?->parameters() ?? [])
                            ->map(fn ($v) => $v instanceof Model ? $v->getKey() : $v)
                            ->all(),
                        'fields' => array_keys($request->except([
                            '_token', '_method', 'password', 'password_confirmation', 'current_password',
                        ])),
                    ],
                ]);
            } catch (\Throwable $e) {
                report($e); // logging must never break the real request
            }
        }

        return $response;
    }
}