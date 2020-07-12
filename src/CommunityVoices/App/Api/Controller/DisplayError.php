<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;

class DisplayError extends Component\Controller
{
    const ERRORSLOGPATH = '/var/www/html/log/access.log';

    protected function getError()
    {

    }
    protected function getAllErrors($request,$lines=500)
    {
        $dates = empty($request->query->get('dateRange')) ? [false,false] : explode(" - ",$request->query->get('dateRange'));
        $linePos = empty($request->query->get('linePos')) ? PHP_INT_MAX : intval($request->query->get('linePos'));
        $numLinesToAdd = intval($request->query->get('numLines')) ?? 0;
        $initialRequest = $request->query->get('firstTime');

        if($initialRequest)
            return $this->tailRead(self::ERRORSLOGPATH,$lines);
        else
            return $this->tailRead(self::ERRORSLOGPATH,$numLinesToAdd,$linePos,$dates[0],$dates[1]);

    }
    protected function getSomeErrors($request)
    {
        $lines = $request->attributes->get('lines');
        return $this->getAllErrors($request,$lines);
    }

    private function tailRead($filepath, $lines, $endLine = PHP_INT_MAX, $startDate = false, $endDate = false) {
            if($endLine < 0) return new HttpFoundation\JsonResponse(''); // dummy response indicating nothing was read
            $f = new \SplFileObject($filepath);

    		// Jump to endline of range
            $f->seek($endLine);
            $currentLine = $f->key();

            // last line in file is blank, probably due to newline
            if($endLine == PHP_INT_MAX)
                $currentLine --;

            $errors = array();

            if($startDate == false && $endDate == false) {
                while($currentLine >= 0 && $lines > 0) {
                    $f->seek($currentLine);
                    $line = $f->current();
                    $timeStamp = mb_substr($line,1,19);
                    $message = mb_substr($line,21);
                    $item = ['Time' => $timeStamp, 'Message' => $message, 'unixTime' => strtotime($timeStamp)];

                    array_push($errors,$item);
                    $currentLine --;
                    $lines --;
                }
            } else {
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                while($currentLine >= 0) {
                    $f->seek($currentLine);
                    $line = $f->current();
                    $timeStamp = mb_substr($line,1,19);
                    $unixTimeStamp = strtotime($timeStamp);
                    $message = mb_substr($line,21);
                    $item = ['Time' => $timeStamp, 'Message' => $message, 'unixTime' => $unixTimeStamp];

                    if($unixTimeStamp > $endDate) {
                        $currentLine --;
                    } else if ($unixTimeStamp >= $startDate) {
                        array_push($errors,$item);
                        $currentLine --;
                    } else {
                        break;
                    }
                }
            }

            $f = null; // closes file
            $response = new HttpFoundation\JsonResponse(["errorsLog" => [$errors,$currentLine]]);
            return $response;

    }


}
