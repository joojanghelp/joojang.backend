<?php
namespace App\Traits;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait MasterTrait
{
    /**
     * 페이징 함수 키값 있음.
     *
     * @param [type] $items
     * @param integer $perPage
     * @param [type] $page
     * @param [type] $baseUrl
     * @param array $options
     * @return void
     */
    public function paginate($items, $perPage = 15, $page = null, $baseUrl = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        if ($baseUrl) {
            $lap->setPath($baseUrl);
        }

        return $lap;
    }

    /**
     * 페이징 함수. key 값업음.
     *
     * @param [type] $items
     * @param integer $perPage
     * @param [type] $page
     * @param array $options
     * @return void
     */
    public function paginateCollection($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
        //ref for array_values() fix: https://stackoverflow.com/a/38712699/3553367
    }
}
