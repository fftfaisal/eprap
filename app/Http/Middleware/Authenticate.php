<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
class Authenticate extends Middleware
{
    protected $guards = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->guards = $guards;
        if (\Auth::check() && \Auth::user()::ADMININSTRATOR)
        {
            \Debugbar::enable();
        }
        else
        {
            \Debugbar::enable();
        }
        return parent::handle($request, $next, ...$guards);
    }

    // protected function unauthenticated($request, array $guards)
    // {
    //     throw new AuthenticationException(
    //         'Unauthenticated.', $guards, $this->redirectTo($request,$guards)
    //     );
    // }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            if(filled($this->guards) && $this->guards[0] == 'student'){
                return route('student.login');
            }
            return route('login');
        }
    }
}
