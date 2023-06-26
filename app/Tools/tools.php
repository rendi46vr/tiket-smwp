<?php

namespace App\Tools;

use stdClass;

class Tools
{
    public static function ApiPagination($totalPage, $currentPage = 1, $pageName, $onDataTotal = false)

    {

        // $pg = new stdClass();
        $pg = (object)[];
        // if ($onDataTotal) {
        //     $newTotal = ceil($totalPage * 20);
        //     $totalPage = $newTotal;
        // }
        $pg->totolPage = $totalPage;
        $pg->hasPages = false;
        if ($totalPage > 1) {
            $pg->hasPages = true;
            $pg->firstPage = 1;
            $pg->currentPage = $currentPage;
            $currentPage <= $pg->firstPage ? $pg->onFirstPage = true : $pg->onFirstPage = false;
            $pg->getPageName = $pageName;
            $currentPage >= $totalPage ? $pg->hasMorePages = false : $pg->hasMorePages = true;

            $element = [];
            if ($totalPage > 13 && $totalPage < 16) {
                if ($currentPage < 8) {
                    for ($i = 1; $i <= $totalPage; $i++) {
                        if ($i <= 10) {
                            array_push($element, $i);
                        } else {
                            if (($totalPage - $i) == 1) {
                                array_push($element, "...");
                                array_push($element, $totalPage - 1);
                            }
                            if (($i - $totalPage) == 0) {
                                array_push($element, $totalPage);
                            }
                        }
                    }
                } elseif ($currentPage >= 8) {
                    if (($currentPage - $totalPage) <= 6) {
                        array_push($element, 1);
                        array_push($element, 2);
                        array_push($element, "...");
                        for ($i = $totalPage - 10; $i <= $totalPage; $i++) {
                            array_push($element, $i);
                        }
                    }
                }
            } elseif ($totalPage > 15) {
                if ($currentPage < 8) {
                    for ($i = 1; $i <= $totalPage; $i++) {
                        if ($i <= 10) {
                            array_push($element, $i);
                        } else {
                            if (($totalPage - $i) == 1) {
                                array_push($element, "...");
                                array_push($element, $totalPage - 1);
                            }
                            if (($i - $totalPage) == 0) {
                                array_push($element, $totalPage);
                            }
                        }
                    }
                } elseif ($currentPage >= 8) {
                    array_push($element, 1);
                    array_push($element, 2);
                    if (($totalPage - $currentPage) <= 6) {
                        array_push($element, "...");
                        for ($i = $totalPage - 10; $i <= $totalPage; $i++) {
                            array_push($element, $i);
                        }
                    } else {
                        array_push($element, "...");
                        for ($i = $currentPage - 3; $i <= $currentPage + 3; $i++) {
                            array_push($element, $i);
                        }
                        array_push($element, "...");
                        array_push($element, $totalPage - 1);
                        array_push($element, $totalPage);
                    }
                }
            } else {
                for ($i = 1; $i <= $totalPage; $i++) {
                    array_push($element, $i);
                }
            }

            // if($pg->currentPage )
            return view("tools.apipagination", ["paginator" => $pg, "elements" => $element])->render();
        }
    }
    public static function fRupiah($rupiah)
    {
        $hasil_rupiah = "Rp " . number_format($rupiah, 2, ',', '.');
        return $hasil_rupiah;
    }
}
