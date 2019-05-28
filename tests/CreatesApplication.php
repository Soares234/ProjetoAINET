<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // CUSTOM CODE -------------------------------------------------------


        // TestResponse::macro('assertSessionHasNoErrors', function ($keys = [], $format = null, $errorBag = 'default') {
        //     $bag = app('session.store')->get('errors');
        //     if (is_null($bag)) {
        //         PHPUnit::assertTrue(true);
        //         return $this;
        //     }

        //     $keys = (array) $keys;

        //     $errors = $bag->getBag($errorBag);

        //     foreach ($keys as $key => $value) {
        //         if (is_int($key)) {
        //             PHPUnit::assertFalse($errors->has($value), "Session has error: $value");
        //         } else {
        //             PHPUnit::assertNotContains($value, $errors->get($key, $format));
        //         }
        //     }

        //     return $this;
        // });

        TestResponse::macro('assertSeeCount', function ($string, $occorrences = 1, $message = '') {
            $haystack = \mb_strtolower($this->getContent());
            $needle = \mb_strtolower($string);
            PHPUnit::assertEquals($occorrences, \mb_substr_count($haystack, $needle), $message);

            return $this;
        });

        TestResponse::macro('assertPatternCount', function ($pattern, $occorrences = 1, $message = '') {
            $haystack = \mb_strtolower($this->getContent());
            PHPUnit::assertEquals($occorrences, preg_match_all($pattern, $haystack), $message);
            return $this;
        });

        // TestResponse::macro('assertSeeLike', function ($likeExpression, $occorrences = 1, $message = '') {
        //     $haystack = \mb_strtolower($this->getContent());
        //     $pattern = str_replace("/", "\/", $likeExpression);
        //     $pattern = str_replace(" % ",".*?",$pattern);
        //     $pattern = \mb_strtolower('/' . $pattern . '/u');

        //     preg_match($pattern, $haystack, $matches, PREG_OFFSET_CAPTURE, 0);

        //     PHPUnit::assertEquals($occorrences, preg_match_all($pattern, $haystack), $message);

        //     return $this;
        // });

        TestResponse::macro('assertDontSeeAll', function ($strings, $message = null) {
            $currentString = "";
            try {
                foreach ($strings as $string) {
                    $currentString = $string;
                    $this->assertDontSee($string);
                }
            } catch (ExpectationFailedException $e) {
                throw new ExpectationFailedException($message ?? "A Resposta inclui: " . $currentString);
            }
            return $this;
        });

        TestResponse::macro('assertSeeAll', function ($strings, $message = null) {
            $currentString = "";
            try {
                foreach ($strings as $string) {
                    $currentString = $string;
                    $this->assertSee($string);
                }
            } catch (ExpectationFailedException $e) {
                throw new ExpectationFailedException($message ?? "A Resposta não inclui: " . $currentString);
            }
            return $this;
        });

        TestResponse::macro('assertInvalid', function ($field_name = null, $message = null) {
            try {
                $field_name ? $this->assertSessionHasErrors($field_name) : $this->assertSessionHasErrors();
            } catch (ExpectationFailedException $e) {
                //dump($this->session()->get('errors')->getBag($errorBag));
                throw new ExpectationFailedException(($field_name ? "Validação do campo \"$field_name\" ": "Validação de um campo "). "incorreta - valor inválido está a ser considerado como válido.\n" . $message ?? "");
            }
            return $this;
        });

        TestResponse::macro('assertValid', function ($field_name = null, $message = null) {
            try {
                $field_name ? $this->assertSessionHasNoError($field_name) : $this->assertSessionHasNoErrors();
            } catch (ExpectationFailedException $e) {                
                throw new ExpectationFailedException(($field_name ? "Validação do campo \"$field_name\" ": "Validação dos campos "). "incorreta - valor válido está a ser considerado como inválido.\n" . $message ?? "");
            }
            return $this;
        });

        TestResponse::macro('assertAllValid', function ($message = null) {
            try {
                $this->assertSessionHasNoErrors();
            } catch (ExpectationFailedException $e) { 
                $error_keys = $this->session()->get('errors')->getBag('default')->keys();
                $msgStr = null;
                foreach ($error_keys as $field_name) {
                    $msgStr .= $msgStr ? ", $field_name" : $field_name;
                }
                throw new ExpectationFailedException("Validação dos campos falhou - Os campos [$msgStr] estão a ser considerados inválidos.\n" . $message ?? "");
            }
            return $this;
        });

        TestResponse::macro('assertSessionHasNoError', function ($keys = [], $format = null, $errorBag = 'default') {
            $session = session();
            if (!$session) {
                PHPUnit::assertTrue(true, "Something went wrong with 'assertSessionHasNoError'");
            } else {     
                $errors = $session->get('errors');
                if (!$errors) {
                    PHPUnit::assertTrue(true, "Something went wrong with 'assertSessionHasNoError'");
                } else {    
                    $keys = (array) $keys;
                    $errors = $this->session()->get('errors')->getBag($errorBag);

                    foreach ($keys as $key => $value) {
                        if (is_int($key)) {
                            PHPUnit::assertFalse($errors->has($value), "Session has error: $value");
                        } else {
                            PHPUnit::assertNotContains($value, $errors->get($key, $format));
                        }
                    }
                } 
            }
            return $this;
        });

        TestResponse::macro('assertSeeInOrder_2', function ($strings, $message = null) {
            try {
                $msg = "";
                foreach ($strings as $string) {
                    $msg.= $string. "  |  ";
                }
                $this->assertSeeInOrder($strings);
            } catch (ExpectationFailedException $e) {
                throw new ExpectationFailedException($message ?? "A resposta não inclui as strings (pela ordem indicada): " . $msg, $e->getComparisonFailure());
                //throw new ExpectationFailedException($message ?? $e->getMessage(), $e->getComparisonFailure());
            }
            return $this;
        });

        TestResponse::macro('assertDontSeeInOrder_2', function ($strings, $message = null) {
            try {
                $msg = "";
                foreach ($strings as $string) {
                    $msg.= $string. "  |  ";
                }
                PHPUnit::assertThat($strings, new DontSeeInOrder($this->getContent()));
            } catch (ExpectationFailedException $e) {
                throw new ExpectationFailedException($message ?? "A resposta inclui as strings (pela ordem indicada): " . $msg, $e->getComparisonFailure());
                //throw new ExpectationFailedException($message ?? $e->getMessage(), $e->getComparisonFailure());
            }
            return $this;
        });

        TestResponse::macro('assertSuccessfulOrRedirect', function() {
            PHPUnit::assertTrue(
                $this->isSuccessful() || $this->isRedirect(),
                'Response status code ['. $this->getStatusCode() .'] is not a successful status code.'
            );
            return $this;
        });

        TestResponse::macro('assertNotStatus', function($status, $message = null) {
            $actual = $this->getStatusCode();
            PHPUnit::assertFalse(
                $actual === $status,
                $message ? $message : "Expected status code different of {$status} but received {$actual}."
            );
            return $this;
        });

        TestResponse::macro('assertUnauthorized', function($method="", $url="", $message = null) {
            // Se não tem acesso, deve dar código de erro 401 ou 403,
            // ou redirecionar para login,
            // ou redirecionar para email/verify (quando utilizador ainda não verificou email)
            // ou redirecionar para change password (quando o utilizador ainda não alterar a password inicial)
            PHPUnit::assertTrue(
                $this->getStatusCode() == 401 || $this->getStatusCode() == 403 || $this->isRedirect(App::make('url')->to('login')) || $this->isRedirect(App::make('url')->to('email/verify')) || $this->isRedirect(App::make('url')->to('password')),
                $message ? $message : "Access to resource " . ($method ?? '') . " " . ($url ?? '') . ' should be unauthorized, but resource is accessible! Failed to protect resource.'
            );
            return $this;
        });

        TestResponse::macro('assertAuthorized', function($method="", $url="", $message = null) {
            PHPUnit::assertFalse(
                $this->getStatusCode() == 401 || $this->getStatusCode() == 403 || $this->isRedirect(App::make('url')->to('login')) || $this->isRedirect(App::make('url')->to('email/verify')),
                $message ? $message : "Access to resource " . ($method ?? '') . " " . ($url ?? '') . ' should be authorized, but resource is not accessible! Failed to access resource.'
            );
            return $this;
        });

        TestResponse::macro('assertFileExists', function($filename, $message = null) {
            //Storage::fake('local');
            PHPUnit::assertTrue(                
                Storage::exists($filename),
                $message ? $message : "File '$filename' does not exists on file storage"
            );
            return $this;
        });

        TestResponse::macro('assertFileDoesNotExists', function($filename, $message = null) {
            //Storage::fake('local');
            PHPUnit::assertFalse(
                Storage::exists($filename),
                $message ? $message : "File '$filename' exists on file storage"
            );
            return $this;
        });

        // END CUSTOM CODE -------------------------------------------------------

        return $app;
    }
}
