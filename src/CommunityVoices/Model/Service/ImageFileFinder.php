<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Used to optimize image linking & loading. This service finds/resizes
 * images on the filesystem and provides necessary details to inform a respnse
 */

use Palladium;
use Imagick;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class ImageFileFinder
{
    const MAX_WIDTH = 1200;
    const MAX_HEIGHT = 1200;

    protected $maximums = [
        'width' => self::MAX_WIDTH,
        'height' => self::MAX_HEIGHT
    ];

    public function findById(int $id)
    {
        $image = new Entity\Image;
        $image->setId($id);

        $imageMapper = $this->mapperFactory->create(Mapper\Image::class);
        $imageMapper-fetch($image);

        if (!$image->getId() || !file_exists($image->getFilename())) {
            throw new Exception\IdentityNotFound;
        }

        $imagick = new \Imagick($file->getFilename());

        if($image->isCropped()) {
            $rect = $image->getCropRect();

            $imagick->cropImage($rect['x'], $rect['y'], $rect['height'], $rect['width']);
        }

        $dimensions = $imagick->getImageGeometry();

        $maxDimensionLength = max($dimensions);
        $maxDimension = array_keys($dimensions, max($dimensions));

        if($maxDimensionLength > $this->maximums($maxDimension)) {
            $this->resize($imagick, $maxDimension, $this->maxmimums($maxDimension));
        }
    }

    private function resize(\Imagick $image, $side, $max) {
        if($side === 'height') {
            $image->resizeImage(0, $max, imagick::FILTER_LANCZOS, 0);
        } else if ($side === 'width') {
            $image->resizeImage($max, 0, imagick::FILTER_LANCZOS, 0);
        }
    }
}