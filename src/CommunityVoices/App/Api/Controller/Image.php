<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;
use CommunityVoices\App\Api\Component;

class Image extends Component\Controller
{
    protected $imageLookup;
    protected $imageManagement;
    protected $tagLookup;

    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,

        Component\RecognitionAdapter $recognitionAdapter,
        Service\ImageLookup $imageLookup,
        Service\ImageManagement $imageManagement,
        Service\TagLookup $tagLookup
    ) {
        parent::__construct($identifier, $logger);

        $this->recognitionAdapter = $recognitionAdapter;
        $this->imageLookup = $imageLookup;
        $this->imageManagement = $imageManagement;
        $this->tagLookup = $tagLookup;
    }

    public static function CANsendImage($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    protected function sendImage($request)
    {
        $imageId = $request->attributes->get('id');

        $this->imageLookup->printById((int) $imageId);
    }

    public static function CANgetImage($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    /**
     * image lookup by id
     */
    protected function getImage($request)
    {
        $imageId = $request->attributes->get('id');

        try {
            $this->imageLookup->findById((int) $imageId);
        } catch (Exception\IdentityNotFound $e) {
            $this->send404();
        }
    }

    public static function CANgetAllImage($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    protected function getAllImage($request)
    {
        $search = (string) $request->query->get('search');
        $tags = $request->query->get('tags');
        $photographers = $request->query->get('photographers');
        $orgs = $request->query->get('orgs');
        $order = (string) $request->query->get('order');
        $status = $request->query->get('status');
        $status = ($status == null) ? ["approved","pending","rejected"] : explode(',', $status);

        $page = (int) $request->query->get('page');
        $page = ($page > 0) ? $page - 1 : 0; // current page, make page 0-based
        $limit = (int) $request->query->get('per_page');
        $limit = ($limit > 0) ? $limit : 15; // number of items per page
        $offset = $limit * $page;
        $only_unused = (int) $request->query->get('unused');
        $only_unused = ($only_unused === 0 || $only_unused === 1) ? $only_unused : 0;

        $this->imageLookup->findAll($page, $limit, $offset, $order, $only_unused, $search, $tags, $photographers, $orgs, $status);
    }

    public static function CANgetImageUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    protected function getImageUpload()
    {
        $this->tagLookup->findAll();
    }

    public static function CANpostImageUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    protected function postImageUpload($request)
    {
        $identity = $this->recognitionAdapter->identify();

        $files = $request->files->get('file');
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $dateTaken = $request->request->get('dateTaken');
        $photographer = $request->request->get('photographer');
        $organization = $request->request->get('organization');
        $approved = $request->request->get('approved');
        $tags = $request->request->get('tags');

        $this->imageManagement->upload(
            $files,
            $title,
            $description,
            $dateTaken,
            $photographer,
            $organization,
            $identity,
            $approved,
            $tags
      );
    }

    public static function CANgetImageUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    protected function getImageUpdate($request)
    {
        $imageId = $request->attributes->get('id');
        try {
            $this->imageLookup->findById((int) $imageId);
        } catch (Exception\IdentityNotFound $e) {
            $this->send404();
        }
    }

    public static function CANpostImageUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    protected function postImageUpdate($request)
    {
        $id = (int) $request->attributes->get('id');
        if ($id === 0) {
            $id = (int) $request->request->get('id');
        }

        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $dateTaken = $request->request->get('dateTaken');
        $photographer = $request->request->get('photographer');
        $organization = $request->request->get('organization');
        $crop_x = $request->request->get('crop_x');
        $crop_y = $request->request->get('crop_y');
        $crop_width = $request->request->get('crop_width');
        $crop_height = $request->request->get('crop_height');
        $status = ($request->request->get('approve') === '1') ? Entity\Media::STATUS_APPROVED : Entity\Media::STATUS_PENDING;
        $tags = $request->request->get('tags') ?? [];

        $this->imageManagement->update(
            $id,
            $title,
            $description,
            $dateTaken,
            $photographer,
            $organization,
            ['x' => $crop_x, 'y' => $crop_y, 'width' => $crop_width, 'height' => $crop_height],
            $tags,
            $status
        );
    }

    public static function CANpostImageDelete($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    protected function postImageDelete($request)
    {
        $id = (int) $request->attributes->get('id');

        $this->imageManagement->delete($id);
    }

    public static function CANpostImageUnpair($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    protected function postImageUnpair($request)
    {
        $image_id = (int) $request->attributes->get('image');
        $slide_id = (int) $request->attributes->get('slide');

        $this->imageManagement->unpair($image_id, $slide_id);
    }
}
