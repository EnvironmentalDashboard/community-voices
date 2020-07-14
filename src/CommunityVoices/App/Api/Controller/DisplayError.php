<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\App\Api\Component;

class DisplayError extends Component\Controller
{
    const ERRORSLOGPATH = '/var/www/html/log/access.log';

    protected function getError()
    {

    }
    protected function getAllErrors($request,$lines=500)
    {
        $fileProcessor = new Component\FileProcessor();
        $dates = empty($request->query->get('dateRange')) ? [false,false] : explode(" - ",$request->query->get('dateRange'));
        $linePos = empty($request->query->get('linePos')) ? PHP_INT_MAX : intval($request->query->get('linePos'));
        $numLinesToAdd = intval($request->query->get('numLines')) ?? 0;
        $initialRequest = $request->query->count() === 0; // checks for existance of request query

        if($initialRequest)
            return $fileProcessor->tailRead(self::ERRORSLOGPATH,$lines);
        else
            return $fileProcessor->tailRead(self::ERRORSLOGPATH,$numLinesToAdd,$linePos,$dates[0],$dates[1]);

    }
    protected function getSomeErrors($request)
    {
        $lines = $request->attributes->get('lines');
        return $this->getAllErrors($request,$lines);
    }

}
