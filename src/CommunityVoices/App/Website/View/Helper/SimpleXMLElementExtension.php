<?php

namespace CommunityVoices\App\Website\View\Helper;

use \SimpleXMLElement;

class SimpleXMLElementExtension extends SimpleXMLElement
{
    public function adopt($new, $root = true)
    {
        if ($root === true) {
            $root = $this;
        }

        $node = $root->addChild($new->getName(), htmlspecialchars((string) $new));

        foreach ($new->attributes() as $attr => $value) {
            $node->addAttribute($attr, $value);
        }

        foreach ($new->children() as $child) {
            $this->adopt($child, $node);
        }
    }
}
