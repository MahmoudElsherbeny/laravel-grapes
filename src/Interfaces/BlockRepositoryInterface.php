<?php

namespace MahmoudElsherbeny\LaravelGrapes\Interfaces;

interface BlockRepositoryInterface
{
    public function getAllBlocks();
    public function createBlock(array $block);
    public function updateBlock(array $block, $id);
    public function deleteBlock($id);
}
