<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;
use Illuminate\Http\Request;

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

        TestResponse::macro('assertSessionHasNoErrors', function ($keys = [], $format = null, $errorBag = 'default') {
            $bag = app('session.store')->get('errors');
            if (is_null($bag)) {
                PHPUnit::assertTrue(true);
                return $this;
            }

            $keys = (array) $keys;

            $errors = $bag->getBag($errorBag);

            foreach ($keys as $key => $value) {
                if (is_int($key)) {
                    PHPUnit::assertFalse($errors->has($value), "Session has error: $value");
                } else {
                    PHPUnit::assertNotContains($value, $errors->get($key, $format));
                }
            }

            return $this;
        });

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
                $field_name ? $this->assertSessionHasErrors($field_name) : $this->assertSessionHasErrors($field_name);
            } catch (ExpectationFailedException $e) {
                throw new ExpectationFailedException(($field_name ? "Validação do campo \"$field_name\" ": "Validação de um campo "). "incorreta - valor inválido está a ser considerado como válido.\n" . $message ?? "");
            }
            return $this;
        });

        TestResponse::macro('assertValid', function ($field_name = null, $message = null) {
            try {
                $field_name ? $this->assertSessionHasNoErrors() : $this->assertSessionHasNoErrors([$field_name]);
            } catch (ExpectationFailedException $e) {
                throw new ExpectationFailedException(($field_name ? "Validação do campo \"$field_name\" ": "Validação de um campo "). "incorreta - valor válido está a ser considerado como inválido.\n" . $message ?? "");
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

        TestResponse::macro('assertSuccessfulOrRedirect', function() {
            PHPUnit::assertTrue(
                $this->isSuccessful() || $this->isRedirect(),
                'Response status code ['. $this->getStatusCode() .'] is not a successful status code.'
            );
            return $this;
        });

        TestResponse::macro('assertUnauthorized', function($method="", $url="", $message = null) {
            PHPUnit::assertTrue(
                $this->getStatusCode() == 403 || $this->isRedirect(),
                $message ? $message : "Access to resource " . ($method ?? '') . " " . ($url ?? '') . ' should be unauthorized, but resource is accessible! Failed to protect resource.'
            );
            return $this;
        });

        // END CUSTOM CODE -------------------------------------------------------

        return $app;
    }
}
