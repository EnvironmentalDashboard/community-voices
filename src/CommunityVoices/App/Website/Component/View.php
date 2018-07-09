<?php

namespace CommunityVoices\App\Website\Component;

use CommunityVoices\App\Website\Component\Mapper;

class View
{
    protected $mapperFactory;

    public function __construct(
        MapperFactory $mapperFactory
    ) {
        $this->mapperFactory = $mapperFactory;
    }

    protected function finalize($response)
    {
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $cookieMapper->provideResponseHandler($response);
        $cookieMapper->mapToResponse();
    }

    protected function paginationHTML(array $qs, int $count, int $limit, int $page) {
        $final_page = ceil($count / $limit);
        $ret = '<nav aria-label="Page navigation example" class="text-center"><ul class="pagination" style="display: inline-flex;">';
        if ($page > 0) {
            $ret .= '<li class="page-item"><a class="page-link" href="?';
            $ret .= htmlspecialchars(http_build_query(array_replace($qs, ['page' => $page])));
            $ret .= '" aria-label="Previous"><span aria-hidden="true">&#171;</span><span class="sr-only">Previous</span></a></li>';
        }
        for ($i = 1; $i <= $final_page; $i++) {
            if ($page > 20 && $i > 3 && $ok) {
                $i = $page - 2;
                $ok = false;
                $ret .= "<li class='page-item'><span class='page-link'>...</span></li>";
            }
            if ($i >= 20 && $final_page > ($i+3) && $page+3 < $i) {
                $i = $final_page - 3;
                $ret .= "<li class='page-item'><span class='page-link'>...</span></li>";
            }
            if ($page + 1 === $i) {
                $ret .= '<li class="page-item active"><a class="page-link" href="?'. htmlspecialchars(http_build_query(array_replace($qs, ['page' => $i]))).'">' . $i . '</a></li>';
            }
            else {
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
