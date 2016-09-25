<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: lelouch
 * Date: 24/9/16
 * Time: 6:58 PM
 */

require_once __DIR__ . "/vendor/autoload.php";

$definition = (new \Potatoes\Definition())
    ->required(["o", "open"], function (string $value) {
        var_dump("I am a required option, which was provided with the value: " . $value);
    })->optional(["s", "search"],  function (?string $value) {
        var_dump("I am an optional option, which was provided with the value: " . ($value ?? 'NULL'));
    })->none(["h", "help"], function () {
        var_dump("Help.");
    });

$wrapper = new \Potatoes\Wrapper($definition->getOptions());
$shellOptions = $wrapper->register();
$wrapper->callBindings($shellOptions);
