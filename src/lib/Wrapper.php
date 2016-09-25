<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: lelouch
 * Date: 24/9/16
 * Time: 6:52 PM
 */

namespace Potatoes;
class Wrapper {
    private $options;
    private $shellOptions;

    public function __construct(array $options) {
        $this->options = $options;
        return $this;
    }

    public function register(): array {
        $short = $this->options["short"];
        $long = $this->options["long"];

        $sOpts = "";
        $lOpts = [];

        foreach ($short as $shortOpts) {
            $sOpts .= $shortOpts["option"];
        }
        foreach ($long as $longOpts) {
            $lOpts[] = $longOpts["option"];
        }

        $this->shellOptions = getopt($sOpts, $lOpts);
        return $this->shellOptions;
    }

    public function callBindings(array $shellOptions) {
        if(count($shellOptions) === 0) {
            trigger_error("No shell options were passed, or, no valid options were passed, thus no bindings were called.", E_USER_WARNING);
        } else {
            foreach ($shellOptions as $option => $val) {
                if(strlen($option) > 1)
                    $opts = $this->options["long"];
                else
                    $opts = $this->options["short"];
                foreach ($opts as $longOpt) {
                    if(str_replace(':', '', $longOpt["option"]) === $option) {
                        // Current element contains the bindings
                        $numberOfColons = substr_count($longOpt["option"], ':');
                        switch ($numberOfColons) {
                            case ColonsDef::REQUIRED:
                                $longOpt["binding"]($val);
                                break;
                            case ColonsDef::OPTIONAL:
                                $arg = ($val === false) ? null : $val;
                                $longOpt["binding"]($arg);
                                break;
                            case ColonsDef::NONE:
                                $longOpt["binding"]();
                                break;
                        }
                    }
                }
            }
        }
    }
}