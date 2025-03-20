<?php

namespace MSA\LaravelGrapes\Repositories;

use MSA\LaravelGrapes\Interfaces\PageRepositoryInterface;
use MSA\LaravelGrapes\Models\Page;
use MSA\LaravelGrapes\Services\GenerateFrontEndService;

class PageRepository implements PageRepositoryInterface
{
    public function __construct(
        private GenerateFrontEndService $generateFrontendService)
    {
        //
    }

    public function getAllPages()
    {
        $pages = Page::select('id', 'name', 'slug', 'is_active')->get();
        return $pages;
    }

    public function getPageById($id)
    {
        return Page::findOrFail($id);
    }

    public function deletePage($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
    }

    public function createPage(array $pageDetails)
    {
        return Page::create($pageDetails);
    }

    public function UpdatePage(array $newPageDetails, $id)
    {
        $page = Page::findOrFail($id);

        $page->update($newPageDetails);

        return [
            'success' => true,
            'page'    => $page
        ];
    }

    public function UpdatePageContent(array $newPageContent, $id)
    {
        $page = Page::findOrfail($id);
        $page->update($newPageContent);
        return $page;
    }
}
