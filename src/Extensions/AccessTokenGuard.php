<?php

namespace EscolaLms\Lrs\Extensions;

use Illuminate\Http\Request;
use Trax\Auth\Contracts\AccessGuardContract;
use Trax\Repo\CrudRepository;
use Laravel\Passport\Passport;
use Trax\Auth\Stores\BasicHttp\BasicHttpProvider;

class AccessTokenGuard implements AccessGuardContract
{
    /**
     * The providers.
     *
     * @var \Trax\Auth\Stores\BasicHttp\BasicHttpProvider
     */
    protected $provider;



    /**
     * Get the type used in the access model.
     *
     * @return string
     */
    public function type(): string
    {
        return 'basic_http';
    }

    /**
     * Get the name of the guard for humans.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Basic HTTP';
    }

    /**
     * Get the guard credentials validation rules.
     *
     * @param \Illuminate\Http\Request  $request;
     * @return array
     */
    public function validationRules(Request $request)
    {
        return [
            'credentials.username' => 'required|alpha_dash',
            'credentials.password' => 'required|regex:/^\S*$/u',
        ];
    }

    /**
     * Get the credentials provider.
     *
     * @return \Trax\Repo\CrudRepository
     */
    public function provider(): CrudRepository
    {
        return $this->provider ?? $this->provider = new BasicHttpProvider();
    }

    /**
     * Check the request credentials.
     *
     * @param  \Trax\Auth\Stores\BasicHttp\BasicHttp  $credentials
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function check($credentials, Request $request): bool
    {

        $token = $request->header('Authorization');
        $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));

        // $auth = base64_encode($uname . ":" . $pass);



        if (empty($decoded)) {
            return false;
        }

        $token = Passport::token()->where('id', $decoded->jti)->first();
        if (!$token) {
            return false;
        }

        $this->user = $token->user()->get();

        return true;
    }
}
