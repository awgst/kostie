<?php

namespace Modules\Kost\Http\Controllers\API;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kost\Repositories\KostRepository;

class KostController extends Controller
{
    use ApiResponse;

    private $kost;

    public function __construct(KostRepository $kost)
    {
        $this->kost = $kost;
    }

    /**
     * List all available kost
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->all();
            $kosts = $this->kost;
            if ($request->has('sort')) {
                $order = $request->has('order') ? $request->order : 'asc';
                $kosts = $kosts->sortBy($request->sort, $order);
            }
            $kosts = $kosts->filter($filters)
                        ->available()
                        ->paginate(10);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
        
        return $this->responseOk($kosts);
    }

    /**
     * Detail kost
     * @param $id
     */
    public function show($id)
    {
        try {
            $kost = $this->kost->findOrFail($id, ['owner']);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
        
        return $this->responseOk($kost);
    }
}
