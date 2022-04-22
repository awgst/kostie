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

    public function index(Request $request)
    {
        try {
            $filters = $request->all();
            $kosts = $this->kost
                        ->filter($filters)
                        ->available()
                        ->paginate(10);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
        
        return $this->responseOk($kosts);
    }
}
