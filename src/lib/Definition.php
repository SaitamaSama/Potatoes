<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: lelouch
 * Date: 24/9/16
 * Time: 6:52 PM
 */

namespace Potatoes;
class Definition {
    /**
     * @var array
     * Stores the options.
     */
    private $options = [
        "short" => [],
        "long" => []
    ];

    /**
     * Define - constructor.
     * @return Definition
     */
    public function __construct() {
        return $this;
    }

    /**
     * @param string|array $option
     * The option key
     * @param callable $bind
     * Closure to call
     * @return Definition
     * 
     * Used to specify an option which must be provided with a value.
     */
    public function required($option, callable $bind): self {
        if(is_array($option)) {
            foreach ($option as $item) {
                $this->add($item, ':', $bind);
            }
        } else {
            $this->add($option, ':', $bind);
        }
        return $this;
    }

    /**
     * @param string|array $option
     * The option key
     * @param callable $bind
     * Closure to call
     * @return Definition
     *
     * Used to specify an option which may or may not be provided with a value.
     */
    public function optional($option, callable $bind): self {
        if(is_array($option)) {
            foreach ($option as $item) {
                $this->add($item, "::", $bind);
            }
        } else {
            $this->add($option, "::", $bind);
        }
        return $this;
    }

    /**
     * @param string|array $option
     * The option key
     * @param callable $bind
     * Closure to call
     * @return Definition
     *
     * Used to specify an option which will not be provided with a value.
     */
    public function none($option, callable $bind): self {
        if(is_array($option)) {
            foreach ($option as $item) {
                $this->add($item, '', $bind);
            }
        } else {
            $this->add($option, '', $bind);
        }
        return $this;
    }

    /**
     * @param string $option (Above)
     * @param string $colons The colons to be appended
     * @param callable $bind (Above)
     * Internal function to add options to the stack.
     */
    private function add(string $option, string $colons, callable $bind): void {
        if(strlen($option) > 1) {
            // Long option
            $this->options["long"][] = [
                "option" => "$option{$colons}",
                "binding" => $bind
            ];
        } elseif(strlen($option) === 1) {
            // Short option
            $this->options["short"][] = [
                "option" => "$option{$colons}",
                "binding" => $bind
            ];
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array {
        return $this->options;
    }
}