<?php

namespace MSA\LaravelGrapes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomeBlock extends Model
{
    use HasFactory;

    protected $table = 'page_builder_custome_blocks';

    protected $fillable = [
      'name',
      'block_data',
    ];
}
