<?php

namespace App\Http\Controllers;

use App\ClaimType;
use App\Transformers\ClaimTypeTransformer;

class ClaimTypesController extends ApiController
{
    /**
     * @var ClaimTypeTransformer
     */
    private $claimTypeTransformer;

    /**
     * @param ClaimTypeTransformer $claimTypeTransformer
     */
    public function __construct(ClaimTypeTransformer $claimTypeTransformer)
    {
        $this->claimTypeTransformer = $claimTypeTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $claimTypes = ClaimType::all();

        return $this->respond(['data' => $this->claimTypeTransformer->transformCollection($claimTypes->toArray())]);
    }
}
