<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Slide
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function getAllSlide()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('slideFindAll');
        $slideCollection = $stateObserver->getEntry('slideCollection')[0]->toArray();
        // for ($i = 0; $i < count($slideCollection['slideCollection']); $i++) { 
        //     $slideCollection['slideCollection'][$i]['slide']['quote']['quote']['SVGtext'] = $this->SVGText($slideCollection['slideCollection'][$i]['slide']['quote']['quote']['text']);
        // }
        $response = new HttpFoundation\JsonResponse($slideCollection);
        return $response;
    }

    public function getSlide()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('slideLookup');
        $slide = $stateObserver->getEntry('slide')[0];

        $response = new HttpFoundation\JsonResponse($slide->toArray());

        return $response;
    }

    public function getSlideUpload()
    {
        // intentionally blank
    }

    public function postSlideUpload()
    {
        // intentionally blank
    }

    // private function SVGText($str) {
    //     $ret = '<text x="50%" y="45%" fill="#fff" font-size="4px">';
    //     $i = 0;
    //     foreach (explode(' ', $str) as $w => $word) {
    //         if ($w % 5 === 0) {
    //             $ret .= '<tspan dy="2">';
    //         }
    //         $ret .= "{$word}";
    //         if ($w % 5 === 0) {
    //             $ret .= '</tspan>';
    //         }
    //     }
    //     return $ret . '</text>';
    // }
}
