<?php

namespace CommunityVoices\App\Website\Component;

/**
 * Responsible for transcribing arrays/objects into proper encoded XML
 */
class Transcriber
{
    /**
     * Converts an associative array/object to XML using the following rules:
     *  1. Lowest level array values are encoded for XML
     *  2. Associative array keys are used as element names, while their values are
     *     the elements value
     *  3. Indexed arrays' values are not surrounded by elements
     *
     * @param  mixed $array Associative array/object to be converted
     * @return string Output XML
     */
    public function toXml($array)
    {
        $xml = '';

        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) { //PHP 7.1 supports is_iterable
                $value = $this->toXml($value);
            } else {
                $value = $this->encode($value);
            }

            if (is_int($key)) {
                $xml .= $value;
            } else {
                $xml .= '<' . $key . '>' . $value . '</' . $key . '>';
            }
        }

        return $xml;
    }

    private function encode($input)
    {
        // For our XSLT, we want all booleans to come out as strings.
        return (is_bool($input) && $input) ? var_export($input, true) : htmlspecialchars($input);
    }
}
