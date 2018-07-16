<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Slide extends Component\View
{
    protected $recognitionAdapter;
    protected $slideAPIView;
    protected $secureContainer;
    protected $transcriber;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer,
        Api\View\Slide $slideAPIView
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
        $this->slideAPIView = $slideAPIView;
    }

    public function getAllSlide($routes, $context)
    {
        parse_str($_SERVER['QUERY_STRING'], $qs);

        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather slide information
         */
        $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
        // var_dump($slideAPIView->getAllSlide()->getContent());die;
        $json = json_decode($slideAPIView->getAllSlide()->getContent());
        // var_dump($json->slideCollection);die;
        $obj = new \stdClass();
        $obj->slideCollection = (array) $json->slideCollection;
        $count = $obj->slideCollection['count'];
        $limit = $obj->slideCollection['limit'];
        $page = $obj->slideCollection['page'];
        unset($obj->slideCollection['count']);
        unset($obj->slideCollection['limit']);
        unset($obj->slideCollection['page']);
        foreach ($obj->slideCollection as $key => $slide) {
            $slide->slide->g = htmlspecialchars($this->format($slide->slide->image->image->filename, $slide->slide->image->image->id, $slide->slide->quote->quote->text, $slide->slide->quote->quote->attribution, $slide->slide->contentCategory->contentCategory->id));
            $slide->slide->quote->quote->text = htmlspecialchars($slide->slide->quote->quote->text);
            $slide->slide->quote->quote->attribution = htmlspecialchars($slide->slide->quote->quote->attribution);
            $slide->slide->quote->quote->subAttribution = htmlspecialchars($slide->slide->quote->quote->subAttribution);
            $slide->slide->quote->quote->attribution = htmlspecialchars($slide->slide->quote->quote->attribution);
            $slide->slide->quote->quote->text = htmlspecialchars($slide->slide->quote->quote->text);
            $slide->slide->image->image->title = htmlspecialchars($slide->slide->image->image->title);
        }
        $obj->slideCollection = array_values($obj->slideCollection);

        $slideXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($obj)
        );

        $pagination = new \stdClass();
        $pagination->div = $this->paginationHTML($qs, $count, $limit, $page);
        $paginationXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($pagination)
        );

        /**
         * Slide XML Package
         */
        $slidePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);
        $packagedSlide->adopt($paginationXMLElement);

        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate slide module
         */
        // var_dump($slidePackageElement->domain->slideCollection->slide[0]);exit;
        $slideModule = new Component\Presenter('Module/SlideCollection');
        $slideModuleXML = $slideModule->generate($slidePackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $slideModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: All Slides".
            $slideXMLElement->id
        );
        $domainXMLElement->addChild('navbarSection', "slide");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getSlide($routes, $context)
    {
        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather slide information
         */
        $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
        $json = json_decode($slideAPIView->getSlide()->getContent());

        $json->slide->g = htmlspecialchars($this->format($json->slide->image->image->filename, $json->slide->image->image->id, $json->slide->quote->quote->text, $json->slide->quote->quote->attribution, $json->slide->contentCategory->contentCategory->id));

        $slideXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($json)
        );

        /**
         * Slide XML Package
         */
        $slidePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);

        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate slide module
         */
        $slideModule = new Component\Presenter('Module/Slide');
        $slideModuleXML = $slideModule->generate($slidePackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $slideModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Slide ".
            $slideXMLElement->id
        );
        $domainXMLElement->addChild('navbarSection', "slide");
        $domainXMLElement->addChild('comfortaa', "1");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('Blank');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);

        header('Content-type: image/svg+xml');

        return $response;
    }

    public function getSlideUpload($routes, $context)
    {
        parse_str($_SERVER['QUERY_STRING'], $qs);

        $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
        $json = json_decode($slideAPIView->getSlideUpload()->getContent());

        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        $obj = new \stdClass;
        $obj->groupCollection = $json->groupCollection;
        $slideXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $obj = new \stdClass;
        $obj->attributionCollection = $json->attributionCollection;
        $attrXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $obj = new \stdClass;
        $obj->PhotographerCollection = $json->PhotographerCollection;
        $photoXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $obj = new \stdClass;
        $obj->OrgCollection = $json->OrgCollection;
        $orgXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $paramXML = new SimpleXMLElement('<form/>');
        $formModule = new Component\Presenter('Module/Form/SlideUpload');
        $formModuleXML = $formModule->generate($paramXML);
        // var_dump($formModuleXML);die;

        $slidePackageElement = new Helper\SimpleXMLElementExtension('<form/>');
        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);
        $packagedSlide->adopt($attrXMLElement);
        $packagedSlide->adopt($photoXMLElement);
        $packagedSlide->adopt($orgXMLElement);
        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);
        $slideModule = new Component\Presenter('Module/Form/SlideUpload');
        $slideModuleXML = $slideModule->generate($slidePackageElement);
        // var_dump($slideModuleXML);die;

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        //
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');
        $domainXMLElement->addChild('main-pane', $slideModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Slide Upload"
        );
        $domainXMLElement->addChild('extraJS', "create-slide");
        $domainXMLElement->addChild('comfortaa', "1");

        foreach ($qs as $key => $value) {
            if ($key === 'search') {
                $domainXMLElement->addChild($key, $value);
            } else {
                $domainXMLElement->addChild($key, (is_array($value)) ? ','.implode(',', $value).',' : ','.$value.',');
            }
        }


        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postSlideUpload($routes, $context)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', '<p>Success.</p>');
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $domainXMLElement->addChild(
          'title',
          "Community Voices"
        );

        /**
         * Prepare template
         */
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }



    private function formatText(string $text, string $attribution, float $image_end) {
        $space_left = 100 - $image_end;
        $font_size = $this->convertRange($space_left, 0, 100, 2.7, 3.7);
        $every = round($this->convertRange($space_left, 0, 100, 14, 25));
        $counter = 0;
        $len = strlen($text);
        $ret = '<text font-family="Comfortaa, Helvetica, sans-serif" x="'.$image_end.'px" y="'.(10 + ( (10/$len) * 100 )).'%" fill="#fff" font-size="'.$font_size.'px"><tspan>';
        foreach (str_split($text) as $char) {
            if ($counter++ > $every && $char === ' ') {
                $counter = 0;
                $ret .= '</tspan><tspan x="'.$image_end.'px" dy="4">';
            }
            $ret .= $char;
        }
        $ret .= '</tspan><tspan font-size="2px" x="'.$image_end.'px" dy="5">&#8212; ';
        $once = 0;
        if (strlen($attribution) > 10) {
            foreach (explode(',', $attribution) as $part) {
                if ($once++ === 1) {
                    $ret .= ',</tspan><tspan font-size="2px" x="'.($image_end+2).'px" dy="2">';
                }
                $ret .= $part;
            }
        }
        return $ret . '</tspan></text>';
    }

    private function format($fn, $imgId, $text, $attribution, $cc) {
        if (file_exists($fn)) { // it wont exist on local
            $max_height = 39; // viewBox height is 50px, but minus 7px for content category banner and 4px for margin around image
            $max_width = 56; // viewBox width is 100px, but image should take at most 60% of space, minus 4px for margin
            $size = getimagesize($fn);
            $w = $size[0];
            $h = $size[1];
            $aspect_ratio = $w/$h;
            $max_aspect_ratio = $max_width/$max_height;
            $final_height = $max_height;
            $final_width = ($final_height * $aspect_ratio);
            if ($final_width > $max_width) {
                $final_width = $max_width;
            }
            $final_y = 2; // 2px on each side = 4px of margin total
            if ($final_width != $max_width) {
                $final_x = ($max_width - $final_width) / 4;
            } else {
                $final_x = 2;
            }
            $image_href = 'data:' . mime_content_type($fn) . ';base64,' . base64_encode(file_get_contents($fn));
        } else {
            $final_y = 10;
            $final_x = 10;
            $final_width = 35;
            $final_height = 35;
            $image_href = 'https://environmentaldashboard.org/cv/uploads/'.$imgId;
        }
        return '--><image x="'.$final_x.'px" y="'.$final_y.'px" width="'.$final_width.'px" height="'.$final_height.'px" xlink:href="'.$image_href.'"></image>' . $this->formatText($text, $attribution, $final_width + ($final_x*2)) . $this->contentCategoryBar($cc);
    }

    private function contentCategoryBar(int $cc) {
        $fn = '/var/www/html/src/CommunityVoices/App/Website/Presentation/Static/contentCategory/' . $cc . '.png';
        return '<image x="0" y="1.75px" width="100%" xlink:href="data:image/png;base64,' . base64_encode(file_get_contents($fn)).'"></image>';
    }

    private function convertRange($val, $old_min, $old_max, $new_min, $new_max) {
        return ((($new_max - $new_min) * ($val - $old_min)) / ($old_max - $old_min)) + $new_min;
    }
}
