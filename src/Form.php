<?php
namespace Gajus\Dora;

/**
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Form implements \Psr\Log\LoggerAwareInterface {
    private
        /**
         * @var Psr\Log\LoggerInterface
         */
        $logger,
        /**
         * Quasi-persistent unique indentifier. This UID does not change unless
         * the underlying code has changed, i.e. UID is derived using the hash
         * of the caller file/line.
         * 
         * @var string
         */
        $uid,
        /**
         * CSRF token is derived from session_id and therefore is unique to each
         * session.
         * 
         * @var string
         */
        $csrf,
        /**
         * Input assigned to the form. This data is used together with input_index
         * to determine the representable input value.
         *
         * @var array
         */
        $data = [],
        /**
         * Index of all inputs generated using this form instance. Incremental input
         * index is used to determine input value in case of a numeric array input.
         *
         * ['input_name' => [instance1, instance2, ..], ..]
         *
         * @var array
         */
        $input_index = [],
        /**
         * Indicates whether this particular form has been submitted.
         * 
         * @var boolean
         */
        $is_submitted = false,
        /**
         * @var string
         */
        $template;
        
    /**
     * @param array $data Data used to populate Input generated using an instance of this Form.
     * @param null|string $template Template class name.
     */
    public function __construct (array $data = null, $template = 'Gajus\Dora\Template\Traditional') {
        if (session_status() === \PHP_SESSION_NONE) {
            throw new Exception\LogicException('Session must be started before using Dora.');
        }

        $this->template = $template;
        $this->logger = new \Psr\Log\NullLogger();

        $caller = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];

        $this->uid = (string) crc32($caller['file'] . '_' . $caller['line']);
        
        unset($caller);

        $this->csrf = sha1(session_id());

        $this->data = $data;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(\Psr\Log\LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * 
     * 
     * @return array
     */
    public function getData () {
        if (isset($_SESSION['gajus']['dora']['flash'][$this->uid])) {
            $data = $_SESSION['gajus']['dora']['flash'][$this->uid];
        } else {
            $data = $this->data;
        }

        unset($data['gajus']);

        return $data;
    }

    /**
     * 
     * 
     * @return boolean
     */
    public function isSubmitted ($check_csfr = true) {
        $submitted = false;

        if (isset($this->data['gajus']['dora']['uid']) && $this->data['gajus']['dora']['uid'] === $this->uid) {
            $submitted = true;
        }

        if ($check_csfr) {
            $submitted = isset($this->data['gajus']['dora']['csrf']) && $this->data['gajus']['dora']['csrf'] === $this->csrf;
        }

        return $submitted;
    }

    public function sign () {
        return '
        <input type="hidden" name="gajus[dora][uid]" value="' . $this->uid . '">
        <input type="hidden" name="gajus[dora][csrf]" value="' . $this->csrf . '">
        ';
    }

    /**
     * Used to create input that is associated with the Form instance data.
     * 
     * @param string $name
     * @param array $attributes
     * @param array $properties
     * @param string $template
     * @return \gajus\dora\Input
     */
    public function input ($name, array $attributes = null, array $properties = [], $template = null) {
        // Incremental input index is based on input name.
        if (!isset($this->input_index[$name])) {
            $this->input_index[$name] = [];
        }

        $index = count($this->input_index[$name]);

        if (isset($properties['value'])) {
            throw new Exception\InvalidArgumentException('Input instantiated using Form::input() method cannot explicitly define "value" property.');
        }

        if (isset($properties['uid'])) {
            throw new Exception\InvalidArgumentException('Input instantiated using Form::input() method cannot explicitly define "uid" property.');
        }

        $properties['uid'] = crc32($this->uid . '_' . $name . '_' . $index);

        $input = new Input($name, $attributes, $properties, $template ? $template : $this->template);

        #$template = $template ? $template : $this->template;

        #$this->input_index[$name][] = $input;
        $this->input_index[$name][] = null;

        // Input name path (e.g. foo[bar]) is used to resolve input value from the Form instance data.
        $path = $input->getNamePath();

        $value = $this->getData();

        // Indicates whether input name attribute implies that expected value is an array, e.g. foo[].
        $declared_as_array = false;
        
        if (strpos(strrev($name), '][') === 0) {
            array_pop($path);
            
            $declared_as_array = true;
        }

        foreach ($path as $crumble) {
            if (!isset($value[$crumble])) {
                $value = null;
                
                break;
            }
            
            $value = $value[$crumble];
        }

        if (is_array($value)) {
            if (!$declared_as_array) {
                $value = null;
            } else if (isset($attributes['multiple'])) {
                $value = $value;
            } else if (isset($value[$index])) {
                $value = $value[$index];
            } else {
                $value = null;
            }
        } else if ($declared_as_array) {
            $value = null;
        }

        $input->setProperty('value', $value);

        return $input;
    }
    
    /**
     * @return string
     */
    public function getUid () {
        return $this->uid;
    }
}