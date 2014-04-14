<?php
namespace Gajus\Dora;

/**
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
abstract class Template {
    private
        $input;
    
    final public function __construct (\Gajus\Dora\Input $input) {
        $this->input = $input;
    }
    
    /**
     * @return Gajus\Dora\Input
     */
    public function getInput () {
        return $this->input;
    }

    /**
     * @return string
     */
    abstract public function toString ();
}