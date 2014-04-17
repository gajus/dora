<?php
namespace Gajus\Dora;

/**
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Input {
    private
        /**
         * HTML attributes.
         * Attributes are accessible to the Label template via getAttribute.
         * 
         * @var array
         */
        $attributes = [],
        /**
         * Input properties specific to the input type, e.g. 'options' property is
         * used together with the <select> input.
         * Properties are accessible to the Label template via getProperty.
         *
         * @var array
         */
        $properties = [],
        /**
         * Value is used to determine whether input has been casted to string.
         * After input is casted to string, some operations are no longer possible, e.g.
         * generating a new random ID.
         * 
         * @var boolean
         */
        $is_stringified = false,
        /**
         * @var string
         */
        $template;
    
    /**
     * @param string $name Input name.
     * @param array $attributes HTML attributes.
     * @param array $properties Input properties, e.g. input name.
     * @param null|string $template
     */
    public function __construct ($name, array $attributes = null, array $properties = null, $template = null) {
        if ($properties === null) {
            $properties = [];
        }

        $this->template = $template;

        if ($this->template) {
            if (!class_exists($this->template)) {
                throw new Exception\LogicException('Template does not exist.');
            } else if (!is_subclass_of($this->template, 'Gajus\Dora\Template')) {
                throw new Exception\LogicException('Template does not extend Gajus\Dora\Template.');
            }
        }

        $this->attributes['name'] = $name;

        if ($attributes) {
            foreach ($attributes as $k => $v) {
                $this->setAttribute($k, $v);
            }
        }

        foreach ($properties as $k => $v) {
            $this->setProperty($k, $v);
        }

        if (!isset($this->attributes['type']) && isset($this->properties['options'])) {
            $this->attributes['type'] = 'select';
        } else if (!isset($this->attributes['type'])) {
            $this->attributes['type'] = 'text';
        }

        if (!isset($this->properties['value'])) {
            $this->properties['value'] = null;
        }

        if (!isset($this->properties['name'])) {
            $name_path = $this->getNamePath();
            
            $this->properties['name'] = static::deriveName($name_path);
        }

        if (isset($this->attributes['type']) && in_array($this->attributes['type'], ['radio', 'checkbox']) && !isset($this->attributes['value'])) {
            $this->attributes['value'] = 1;
        }

        if (isset($this->properties['options']) && $this->attributes['type'] !== 'select') {
            throw new Exception\InvalidArgumentException('[input="' . $this->attributes['type'] . '"] does not support "options" property.');
        }
    }

    /**
     * Convert array selector representation (['baz', 'foo_bar']) to
     * English friendly representation (Bar Foo Bar).
     *
     * If selector name is ends with "_id", then Id is dropped off the name.
     * 
     * @param array 
     * @return string
     */
    static public function deriveName ($name_path) {
        $path = explode('_', implode('_', array_filter($name_path)));

        if (count($path) > 1 && $path[count($path) -1] == 'id') {
            array_pop($path);
        }

        return ucwords(implode(' ', $path));
    }

    public function getUid () {
        return mt_rand(0, 9999);
    }
    
    /**
     * @param string $name Name of the property.
     * @param mixed $value
     * @return mixed
     */
    public function setProperty ($name, $value) {
        $this->properties[$name] = $value;
    }

    /**
     * @param string $name Name of the property.
     * @return mixed
     */
    public function getProperty ($name) {
        return isset($this->properties[$name]) ? $this->properties[$name] : null;
    }
    
    /**
     * @return mixed If no value is matched, will return null or (if input name implies that expected value is an array) an empty array.
     */
    public function getValue () {
        return $this->properties['value'];
    }
    
    /**
     * Parse input[name] into an array reprensentation.
     *
     * @return array [name="a[b][c][]"] is represented ['a', 'b', 'c'].
     */
    public function getNamePath () {
        if (strpos($this->attributes['name'], '[') === false) {
            return [$this->attributes['name']];
        }
    
        $path = explode('[', $this->attributes['name'], 2); // ['name']['[foo][bar]']
        
        return array_merge([$path[0]], explode('][', mb_substr($path[1], 0, -1)));
    }

    /**
     * Type checking ommission is intentional. PHP does not provide scalar
     * data type hinting. Non-scallar variables will thrown an error when
     * cast to string. Manual validation is an unnecessary overhead.
     *
     * There is no value escaping either. It is assumed that pre-caution
     * steps (e.g. FILTER_SANITIZE_SPECIAL_CHARS) are already taken.
     *
     * @param string $name
     * @param string $value
     */
    private function setAttribute ($name, $value) {
        // Not possible when setAttribute is private.
        #if (!is_string($name)) {
        #    throw new \InvalidArgumentException('Attribute name is not a string.');
        #} else
        if (!is_string($value) && !is_int($value)) {
            throw new Exception\InvalidArgumentException('Attribute value is not a string.');
        } else if ($name === 'name') {
            throw new Exception\InvalidArgumentException('"name" attribute cannot be overwritten.');
        }

        $this->attributes[$name] = $value;
    }
    
    /**
     * If [id] is undefined at the time of request, Dora will use instance UID.
     *
     * @return null|string Attribute value.
     */
    public function getAttribute ($name) {
        if ($name === 'id' && !isset($this->attributes['id'])) {
            if ($this->is_stringified) {
                throw new Exception\LogicException('Too late to generate random [id].');
            }
            
            $this->attributes['id'] = isset($this->properties['uid']) ? 'dora-input-' . $this->properties['uid'] : 'dora-input-' . $this->getUid();
        }
        
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }
    
    /**
     * Generate string representation of the input attributes.
     * Attribute string will vary depending on the input type.
     *
     * [value] is excluded intentionally because of the inconsistent
     * implementation between different input types.
     */
    private function stringifyAttributes () {
        $attributes = $this->attributes;

        $attributes_string = '';
        
        switch ($this->attributes['type']) {
            case 'checkbox':
            case 'radio':
                $value = $this->getValue();
                
                if ($this->attributes['value'] == $value || is_array($value) && in_array($this->attributes['value'], $value)) {
                    $attributes['checked'] = 'checked';
                }
                
                break;
            
            case 'textarea':
            case 'select':
                unset($attributes['type']);
                break;
        }

        if (!in_array($this->attributes['type'], ['checkbox', 'radio'])) {
            unset($attributes['value']);
        }
        
        ksort($attributes); // To make the unit testing simpler.
        
        #if (strpos(strrev($attributes['name']), '][') === 0) {
        #    $attributes['name'] = substr_replace($attributes['name'], '[' . $this->index . ']', -2);
        #}
        
        foreach ($attributes as $k => $v) {
            $attributes_string .= ' ' . $k . '="' . $v . '"';
        }
        
        return trim($attributes_string);
    }
    
    public function toString () {
        if ($this->is_stringified) {
            throw new Exception\LogicException('Input has already been stringified.');
        }

        if ($this->template) {
            $template = $this->template;

            $this->template = null;

            $template = new $template ($this);

            return $template->toString();
        }
        
        $this->is_stringified = true;
        
        $value = $this->getValue();
        
        $attributes_string = $this->stringifyAttributes();
        
        switch ($this->attributes['type']) {
            case 'select':
                if (!isset($this->properties['options'])) {
                    $this->properties['options'] = [];
                }

                $options_string    = '';

                $placeholder = null;
                $has_selected_option = false;

                if (isset($this->properties['options'][0])) {
                    $placeholder = $this->properties['options'][0];

                    unset($this->properties['options'][0]);
                }

                foreach ($this->properties['options'] as $v => $l) {
                    $selected = '';

                    if ((is_array($value) && in_array($v, $value) || !is_null($value) && $value == $v)) {
                        $selected = ' selected="selected"';

                        $has_selected_option = true;
                    }
                
                    $options_string    .= '<option value="' . $v . '"' . $selected . '>' . $l . '</option>';
                }

                if ($placeholder) {
                    if ($has_selected_option) {
                        $options_string    = '<option disabled="disabled">' . $placeholder . '</option>' . $options_string;
                    } else {
                        $options_string    = '<option selected="selected" disabled="disabled">' . $placeholder . '</option>' . $options_string;
                    }
                }
            
                $input = '<select ' . $attributes_string . '>' . $options_string . '</select>';
                break;
            
            case 'checkbox':
            case 'radio':
            case 'password':
                $input = '<input ' . $attributes_string . '>';
                break;

            default:
                if ($this->attributes['type'] === 'textarea') {
                    $input = '<textarea ' . $attributes_string . '>' . filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS) . '</textarea>';
                } else {
                    $input = '<input ' . $attributes_string . ' value="' . filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS) . '">';
                }
                break;
        }
        
        return $input;
    }

    public function __toString () {
        return $this->toString();
    }
}