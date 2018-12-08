<?php

namespace Laravel\Passport;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     *
     * @return void
     */
    public function all()
    {
        $this->forAuthorization();
    }

    /**
     * Register the routes needed for authorization.
     *
     * @return void
     */
    public function forAuthorization()
    {
        $this->router->group(['middleware' => ['api']], function ($router) {
            $router->get('/wechat', 'WechatController@echo')->name('wechat.echo');
        });

//        $this->router->group(['middleware' => ['web', 'auth']], function ($router) {
//            $router->get('/authorize', [
//                'uses' => 'AuthorizationController@authorize',
//                'as' => 'passport.authorizations.authorize',
//            ]);
//        });
    }

}
