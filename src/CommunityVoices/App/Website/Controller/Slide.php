<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Api;

class Slide
{
    protected $slideAPIController;

    public function __construct(
        Api\Controller\Slide $slideAPIController
    ) {
        $this->slideAPIController = $slideAPIController;
    }

    public function getAllSlide($request)
    {
        $this->slideAPIController->getAllSlide($request);
    }

    public function getSlide($request)
    {
        $this->slideAPIController->getSlide($request);
    }

    public function getSlideUpload($request)
    {
        $this->slideAPIController->getSlideUpload($request);
    }

    public function postSlideUpload($request)
    {
        $this->slideAPIController->postSlideUpload($request);
    }

    public function getSlideUpdate($request)
    {
        $this->slideAPIController->getSlideUpdate($request);
    }

    public function postSlideUpdate($request)
    {
        $this->slideAPIController->postSlideUpdate($request);
    }

    public function postSlideDelete($request)
    {
        $this->slideAPIController->postSlideDelete($request);
    }
}
