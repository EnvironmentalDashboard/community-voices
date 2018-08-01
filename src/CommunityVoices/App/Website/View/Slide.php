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
        $svg_ver = (isset($qs['ver']) && $qs['ver'] === 'svg');

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
            if ($svg_ver) {
                $slide->slide->g = htmlspecialchars($this->formatSlide($slide->slide->image->image->filename, $slide->slide->image->image->id, $slide->slide->quote->quote->text, $slide->slide->quote->quote->attribution, $slide->slide->contentCategory->contentCategory->id));
            }
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

        $obj = new \stdClass;
        $obj->groupCollection = $json->groupCollection;
        $tagXMLElement = new SimpleXMLElement(
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

        $obj = new \stdClass;
        $obj->contentCategoryCollection = array_map(function($a) { $o = new \stdClass; $o->contentCategory = $a; return $o; }, ['Serving Our Community', 'Our Downtown', 'Next Generation', 'Heritage', 'Natural Oberlin', 'Neighbors']);
        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        /**
         * Slide XML Package
         */
        $slidePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);
        $packagedSlide->adopt($paginationXMLElement);
        $packagedSlide->adopt($attrXMLElement);
        $packagedSlide->adopt($photoXMLElement);
        $packagedSlide->adopt($orgXMLElement);
        $packagedSlide->adopt($contentCategoryXMLElement);
        $packagedSlide->adopt($tagXMLElement);
        foreach ($qs as $key => $value) {
            if ($key === 'search' || $key === 'order') {
                $packagedSlide->addChild($key, $value);
            } else {
                $packagedSlide->addChild($key, (is_array($value)) ? ','.implode(',', $value).',' : ','.$value.',');
            }
        }
        // var_dump($packagedSlide);die;

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
        $domainXMLElement->addChild('extraJS', "slide-collection");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getSlide($routes, $context)
    {
        $svg_ver = (isset($_GET['ver']) && $_GET['ver'] === 'svg');

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

        if ($svg_ver) {
            $json->slide->g = htmlspecialchars($this->formatSlide($json->slide->image->image->filename, $json->slide->image->image->id, $json->slide->quote->quote->text, $json->slide->quote->quote->attribution, $json->slide->contentCategory->contentCategory->id));
        } else {
            $dimensions = (file_exists($json->slide->image->image->filename)) ? getimagesize($json->slide->image->image->filename) : [16, 12];
            $aspect_ratio = $dimensions[0] / $dimensions[1];
            $scaled_ar = 3 - (($aspect_ratio ** 4) / 5);
            $strlen = strlen($json->slide->quote->quote->text);
            $scaled_len = 1 - ((($strlen/500) ** 2));
            $json->slide->font_size = 0.5 + $scaled_ar + $scaled_len;//($aspect_ratio * ($strlen/100))
        }

        $slideXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($json)
        );

        /**
         * Slide XML Package
         */
        $slidePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $height = (isset($_GET['height']) && intval($_GET['height']) > 0) ? (int) $_GET['height'] : 1080;
        $width = (isset($_GET['width']) && intval($_GET['width'])) > 0 ? (int) $_GET['width'] : 1920;
        $slidePackageElement->addChild('height', $height);
        $slidePackageElement->addChild('width', $width);

        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);

        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate slide module
         */
        $slideModule = new Component\Presenter(($svg_ver) ? 'Module/Slide' : 'Module/HTMLSlide');
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

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter(($svg_ver) ? 'SVG' : 'Blank');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);

        if ($svg_ver) {
            header('Content-type: image/svg+xml');
        }

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

    public function getSlideUpdate($routes, $context)
    {
        parse_str($_SERVER['QUERY_STRING'], $qs);

        $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
        $json = json_decode($slideAPIView->getSlideUpdate()->getContent());

        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        $obj = new \stdClass;
        $obj->slide = $json->slide;
        $slideXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $obj = new \stdClass;
        $obj->groupCollection = $json->groupCollection;
        $tagXMLElement = new SimpleXMLElement(
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

        $slidePackageElement = new Helper\SimpleXMLElementExtension('<form/>');
        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);
        $packagedSlide->adopt($tagXMLElement);
        $packagedSlide->adopt($attrXMLElement);
        $packagedSlide->adopt($photoXMLElement);
        $packagedSlide->adopt($orgXMLElement);
        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);
        $slideModule = new Component\Presenter('Module/Form/SlideUpload');
        $slideModuleXML = $slideModule->generate($slidePackageElement);
        // var_dump($packagedSlide->slide);die;

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

    public function postSlideUpdate($routes, $context)
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

    private function formatSlide($fn, $imgId, $text, $attribution, $cc) {
        if (!file_exists($fn)) { // it wont exist on local
            exit('Slide image not found; are you on local?');
        }
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

        $lines = $this->splitText($text, $this->lineWidth($final_width + ($final_x*2)));
        while (count($lines) > 7) {
            $final_width = $final_width / 1.2;
            $final_height = $final_height / 1.2;
            $final_x = $final_x / 1.2;
            $lines = $this->splitText($text, $this->lineWidth($final_width + ($final_x*2)));
        }
        $len = strlen($text);
        return '--><image x="'.$final_x.'px" y="'.$final_y.'px" width="'.$final_width.'px" height="'.$final_height.'px" xlink:href="'.$image_href.'"></image>' . $this->formatText($lines, $attribution, $final_width + ($final_x*2), (10 + ( (10/$len) * 100 )),  $this->convertRange(350 - $len, 0, 350, 2, 4)) . $this->contentCategoryBar($cc);
    }

    private function formatText(array $lines, string $attribution, float $x, float $y, float $font_size) { // max lines like 8
        $ret = '<text font-family="Comfortaa, Helvetica, sans-serif" x="'.$x.'px" y="'.$y.'%" fill="#fff" font-size="'.$font_size.'px"><tspan>' . implode('</tspan><tspan x="'.$x.'px" dy="4">', $lines) . '</tspan><tspan font-size="2px" x="'.$x.'px" dy="5">&#8212; ';
        $once = 0;
        if (strlen($attribution) > 10) {
            foreach (explode(',', $attribution) as $part) {
                if ($once++ === 1) {
                    $ret .= ',</tspan><tspan font-size="2px" x="'.($x+2).'px" dy="2">';
                }
                $ret .= $part;
            }
        } else {
            $ret .= $attribution;
        }
        return $ret . '</tspan></text>';
    }

    private function splitText($text, $line_width) {
        $i = 0;
        $lines = [];
        $lines[0] = '';
        $space_left = $line_width;
        foreach (explode(' ', $text) as $word) {
            $width = strlen($word);
            if ($width + 1 > $space_left) {
                $lines[++$i] = $word;
                $space_left = $line_width - $width;
            } else {
                $lines[$i] .= " {$word}";
                $space_left = $space_left - $width - 1;
            }
        }
        return $lines;
    }

    private function lineWidth($image_end) {
        return ((100-$image_end)/100) * 60; // ~55-60 is about image width, $image_end is out of 100
    }

    private function contentCategoryBar(int $cc) {
        $fn = '/var/www/html/src/CommunityVoices/App/Website/Presentation/Static/contentCategory/' . $cc . '.png';
        return '<image x="0" y="1.75px" width="100%" xlink:href="data:image/png;base64,' . base64_encode(file_get_contents($fn)).'"></image>';
    }

    private function convertRange($val, $old_min, $old_max, $new_min, $new_max) {
        return ((($new_max - $new_min) * ($val - $old_min)) / ($old_max - $old_min)) + $new_min;
    }

    /**
     * taken from https://stackoverflow.com/a/9225522/2624391
     */
    private function minimumRaggedness($input, $LineWidth, $lineBreak = "\n")
    {
        $words = explode(" ", $input);
        $wsnum = count($words);
        $wslen = array_map("strlen", $words);
        $inf = PHP_INT_MAX;

        // keep Costs
        $C = array();

        for ($i = 0; $i < $wsnum; ++$i)
        {
            $C[] = array();
            for ($j = $i; $j < $wsnum; ++$j)
            {
                $l = 0;
                for ($k = $i; $k <= $j; ++$k)
                    $l += $wslen[$k];
                $c = $LineWidth - ($j - $i) - $l;
                if ($c < 0)
                    $c = $inf;
                else
                    $c = $c * $c;
                $C[$i][$j] = $c;
            }
        }

        // apply recurrence
        $F = array();
        $W = array();
        for ($j = 0; $j < $wsnum; ++$j)
        {
            $F[$j] = $C[0][$j];
            $W[$j] = 0;
            if ($F[$j] == $inf)
            {
                for ($k = 0; $k < $j; ++$k)
                {
                    $t = $F[$k] + $C[$k + 1][$j];
                    if ($t < $F[$j])
                    {
                        $F[$j] = $t;
                        $W[$j] = $k + 1;
                    }
                }
            }
        }

        // rebuild wrapped paragraph
        $output = "";
        if ($F[$wsnum - 1] < $inf)
        {
            $S = array();
            $j = $wsnum - 1;
            for ( ; ; )
            {
                $S[] = $j;
                $S[] = $W[$j];
                if ($W[$j] == 0)
                    break;
                $j = $W[$j] - 1;
            }

            $pS = count($S) - 1;
            do
            {
                $i = $S[$pS--];
                $j = $S[$pS--];
                for ($k = $i; $k < $j; $k++)
                    $output .= $words[$k] . " ";
                $output .= $words[$k] . $lineBreak;
            }
            while ($j < $wsnum - 1);
        }
        else
            $output = $input;

        return $output;
    }
}
