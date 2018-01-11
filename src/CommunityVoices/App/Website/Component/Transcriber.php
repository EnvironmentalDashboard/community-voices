<?php

namespace CommunityVoices\App\Website\Component;

class Transcriber
{
    public function toXml($array)
    {
        $xml = '';

        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) { //PHP 7.1 supports is_iterable
                $value = $this->toXml($value);
            }

            if (is_int($key)) {
                $xml .= $value;
            } else {
                $xml .= '<' . $key . '>' . $value . '</' . $key . '>';
            }
        }

        return $xml;
    }
}
