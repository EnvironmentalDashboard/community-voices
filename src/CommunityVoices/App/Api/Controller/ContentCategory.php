<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service\ContentCategoryLookup;
use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;

use CommunityVoices\App\Api\Component;

class ContentCategory extends Component\Controller
{
    protected $secureContainer;
    protected $contentCategoryLookup;

    public function __construct(
        Component\SecureContainer $secureContainer,
        Service\ContentCategoryLookup $contentCategoryLookup
    ) {
        parent::__construct($secureContainer);

        $this->contentCategoryLookup = $contentCategoryLookup;
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
        $file = $request->files->get('file');
        $label = $request->request->get('label');

        $uploaded_images = $this->imageManagement->upload([$file], null, null, $dateRecorded, null, null, $identity, $approved, null);

        $this->contentCategoryManagement->upload($uploaded_images[0], $label);
    }

    protected function getContentCategoryUpdate($request)
    {
        $this->getContentCategory($request);
    }

    protected function postContentCategoryUpdate($request)
    {
        $groupId = $request->attributes->get('groupId');
        $label = $request->request->get('label');

        $this->contentCategoryManagement->update($label);
    }
}
