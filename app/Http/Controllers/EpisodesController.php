<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Controllers\BaseController;

class EpisodesController extends BaseController
{
   public function __construct()
   {
       $this->class = Episode::class;
   }
}
