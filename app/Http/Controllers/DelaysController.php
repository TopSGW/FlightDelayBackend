<?php

namespace App\Http\Controllers;

use App\Delay;
use App\Transformers\DelayTransformer;

class DelaysController extends ApiController
{
    /**
     * @var DelayTransformer
     */
    private $delayTransformer;

    public function __construct(DelayTransformer $delayTransformer)
    {
        $this->delayTransformer = $delayTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $delays = Delay::all();

        return $this->respond(['data' => $this->delayTransformer->transformCollection($delays->toArray())]);
    }
}
