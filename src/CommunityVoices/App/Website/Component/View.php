<?php

namespace CommunityVoices\App\Website\Component;

use \SimpleXMLElement;

use CommunityVoices\App\Api\Component\Mapper;
use CommunityVoices\App\Api\View\Identification;
use CommunityVoices\App\Website\Component;

class View
{
    protected const ERRORS_DEFAULT = ['errors' => []];

    protected $mapperFactory;
    protected $transcriber;
    //protected $identificationAPIView;
    protected $apiProvider;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Identification $identificationAPIView
        Component\ApiProvider $apiProvider
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        //$this->identificationAPIView = $identificationAPIView;
        $this->apiProvider = $apiProvider;
    }

    protected function finalize($response)
    {
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $cookieMapper->provideResponseHandler($response);
        $cookieMapper->mapToResponse();
    }

    protected function identityXMLElement($request)
    {
        return new SimpleXMLElement(
            $this->transcriber->toXml($this->apiProvider->getJson('/identity', $request))
        );
    }

    protected function isLoggedIn($request)
    {
        return !empty($this->identityXMLElement($request)->id);
    }

    /**
     * @todo Mark-up should not be in view
     */
    protected function paginationHTML(array $qs, int $count, int $limit, int $page)
    {
        $final_page = ceil($count / $limit);
        $ret = '<nav aria-label="Page navigation example" class="text-center"><ul class="pagination" style="display: inline-flex; width: 95%; overflow: hidden; margin: auto;">';
        if ($page > 0) {
            $ret .= '<li class="page-item"><a class="page-link" href="?';
            $ret .= htmlspecialchars(http_build_query(array_replace($qs, ['page' => $page])));
            $ret .= '" aria-label="Previous"><span aria-hidden="true">&#171;</span><span class="sr-only">Previous</span></a></li>';
        }
        $ok = true;
        for ($i = 1; $i <= $final_page; $i++) {
            if ($page > 17 && $i > 3 && $ok) {
                $i = $page - 2;
                $ok = false;
                $ret .= "<li class='page-item'><span class='page-link'>...</span></li>";
            }
            if ($i >= 17 && $final_page > ($i+3) && $page+3 < $i) {
                $i = $final_page - 3;
                $ret .= "<li class='page-item'><span class='page-link'>...</span></li>";
            }
            if ($page + 1 === $i) {
                $ret .= '<li class="page-item active"><a class="page-link" href="?'. htmlspecialchars(http_build_query(array_replace($qs, ['page' => $i]))).'">' . $i . '</a></li>';
            } else {
                $ret .= '<li class="page-item"><a class="page-link" href="?'. htmlspecialchars(http_build_query(array_replace($qs, ['page' => $i]))).'">' . $i . '</a></li>';
            }
        }
        if ($page + 1 < $final_page) {
            $ret .= '<li class="page-item"><a class="page-link" href="?';
            $ret .= htmlspecialchars(http_build_query(array_replace($qs, ['page' => $page+2])));
            $ret .= '" aria-label="Next"><span aria-hidden="true">&#187;</span><span class="sr-only">Next</span></a></li>';
        }
        $ret .= '</ul></nav>';
        return $ret;
    }
}
