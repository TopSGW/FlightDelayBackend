<?php

namespace App\Http\Controllers;

use App\Salutation;
use App\Transformers\SalutationTransformer;
use Illuminate\Http\Request;

class SalutationsController extends ApiController
{
    /**
     * @var SalutationTransformer
     */
    private $salutationTransformer;

    /**
     * @param SalutationTransformer $salutationTransformer
     * @internal param SalutationTransformer $salutationTransformer
     */
    public function __construct(SalutationTransformer $salutationTransformer)
    {
        $this->salutationTransformer = $salutationTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respond([
            'data' => $this->salutationTransformer->transformCollection(Salutation::all()->toArray())
        ]);
    }
}
