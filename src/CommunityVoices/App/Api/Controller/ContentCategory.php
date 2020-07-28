<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service\ContentCategoryLookup;
use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;

use CommunityVoices\App\Api\Component;

class ContentCategory extends Component\Controller
{
    protected $recognitionAdapter;
    protected $contentCategoryLookup;
    protected $contentCategoryManagement;
    protected $imageManagement;

    public function __construct(
        Component\SecureContainer $secureContainer,
        Component\RecognitionAdapter $recognitionAdapter,
        Service\ContentCategoryLookup $contentCategoryLookup,
        Service\ContentCategoryManagement $contentCategoryManagement,
        Service\ImageManagement $imageManagement
    ) {
        parent::__construct($secureContainer);

        $this->recognitionAdapter = $recognitionAdapter;
        $this->contentCategoryLookup = $contentCategoryLookup;
        $this->contentCategoryManagement = $contentCategoryManagement;
        $this->imageManagement = $imageManagement;
    }

    protected function getAllContentCategory()
    {
        $this->contentCategoryLookup->findAll();
    }

    // Note that this is basically a copy of the Quote controller's method,
    // so it should probably be moved to a helper function.
    // (along the way, how to handle 404s should be solved)
    protected function getContentCategory($request)
    {
        $contentCategoryId = (int) $request->attributes->get("groupId");

        try {
            $this->contentCategoryLookup->findById($contentCategoryId);
        } catch (Exception\IdentityNotFound $e) {
            /**
           * @todo This is not necessarily the way to handle 404s
           */
            $this->send404();
        }
    }

    protected function getContentCategoryUpload()
    {
        // intentionally blank
    }

    protected function postContentCategoryUpload($request)
    {
        $identity = $this->recognitionAdapter->identify();

        $file = $request->files->get('file');
        $label = $request->request->get('label');
        $color = $request->request->get('color');

        if (!is_null($file)) {
            $uploaded_images = $this->imageManagement->upload([$file], null, null, null, null, null, $identity, true, null);
            $this->contentCategoryManagement->upload($uploaded_images[0], $label, $color);
        } else {
            $this->contentCategoryManagement->upload(null, $label, $color);
        }
    }

    protected function getContentCategoryUpdate($request)
    {
        $this->getContentCategory($request);
    }

    protected function postContentCategoryUpdate($request)
    {
        $identity = $this->recognitionAdapter->identify();

        $groupId = $request->attributes->get('groupId');
        $file = $request->files->get('file');
        $label = $request->request->get('label');
        $color = $request->request->get('color');

        if (!is_null($file)) {
            $uploaded_images = $this->imageManagement->upload([$file], null, null, null, null, null, $identity, true, null);
        }

        $this->contentCategoryManagement->update($groupId, isset($uploaded_images) ? $uploaded_images[0] : null, $label, $color);
    }

    protected function postContentCategoryDelete($request)
    {
        $groupId = $request->attributes->get('groupId');

        $this->contentCategoryManagement->delete($groupId);
    }
}
