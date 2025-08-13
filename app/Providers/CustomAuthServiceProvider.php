<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Arrayable;

class CustomAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app['auth']->provider('eloquent_senha', function ($app, $config) {
            return new class($app['hash'], $config['model']) extends EloquentUserProvider {
                public function validateCredentials(UserContract $user, array $credentials)
                {
                    $plain = $credentials['senha'] ?? $credentials['password'] ?? null;

                    if ($plain === null) {
                        return false;
                    }

                    return $this->hasher->check($plain, $user->getAuthPassword());
                }

                public function retrieveByCredentials(array $credentials)
                {
                    if (empty($credentials) ||
                       (count($credentials) === 1 &&
                        array_key_exists('password', $credentials))) {
                        return;
                    }

                    // Renomeia 'senha' para 'password' se necessário para buscar o usuário
                    if (isset($credentials['senha'])) {
                        unset($credentials['senha']);
                    }

                    // First we will add each credential element to the query as a where clause.
                    // Then we can execute the query and, if we found a user, return it in a
                    // Eloquent User "model" that will be utilized by the Guard instances.
                    $query = $this->newModelQuery();

                    foreach ($credentials as $key => $value) {
                        if (str_contains($key, 'password')) {
                            continue;
                        }

                        if (is_array($value) || $value instanceof Arrayable) {
                            $query->whereIn($key, $value);
                        } else {
                            $query->where($key, $value);
                        }
                    }

                    return $query->first();
                }
            };
        });
    }
}
