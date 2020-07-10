<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service\TagLookup;
use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;

use CommunityVoices\App\Api\Component;

class Tag extends Component\Controller
{
    protected $recognitionAdapter;
    protected $tagLookup;
    protected $tagManagement;

    public function __construct(
        Component\SecureContainer $secureContainer,
        Component\RecognitionAdapter $recognitionAdapter,
        Service\TagLookup $tagLookup,
        Service\TagManagement $tagManagement
    ) {
        parent::__construct($secureContainer);

        $this->recognitionAdapter = $recognitionAdapter;
        $this->tagLookup = $tagLookup;
        $this->tagManagement = $tagManagement;
    }

    protected function getAllTag()
    {
        $this->tagLookup->findAll();
    }

    protected function postTagUpload($request)
    {
        $label = $request->request->get('text');

        $this->tagManagement->upload($label);
    }
    protected function postTagUpdate($request)
    {
        $groupId = $request->attributes->get('groupId');

        $label = $request->request->get('label');

        $this->tagManagement->update($groupId, $label);
    }

    protected function postTagDelete($request)
    {
        $groupId = $request->attributes->get('groupId');

        $this->tagManagement->delete($groupId);
    }

}
