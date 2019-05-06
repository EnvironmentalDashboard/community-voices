<?php

namespace CommunityVoices\App\Website\Finder;

class ImageFinderResponder
{
    private function renderTemplate($fn, $params = [])
    {
        ob_start();
        extract($params);
        include($fn);

        return ob_get_clean();
    }

    public function matchesResponse($matches)
    {
        $params = [
            'matches' => $matches
        ];
        
        return $this->renderTemplate('./templates/matches-list.html.php', $params);
    }

    public function inputResponse()
    {
        return $this->renderTemplate('./templates/form.html.php');
    }
}
