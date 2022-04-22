<?php

namespace Modules\Kost\Http\Controllers\API;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Kost\Exceptions\InvalidUserTypeException;
use Modules\Kost\Exceptions\OutOfCreditException;
use Modules\Kost\Http\Requests\StoreRequest;
use Modules\Kost\Http\Requests\UpdateRequest;
use Modules\Kost\Repositories\KostRepository;
use Modules\User\Events\AskKostAvailability;

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

    /**
     * Create kost
     * @param Request $request
     */
    public function store(StoreRequest $request)
    {
        try {
            Gate::allows('kost.store');
            $kost = $this->kost->store($request->data());
        } catch (InvalidUserTypeException $e) {
            return $this->responseError($e->getMessage(), JsonResponse::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseOk($kost, JsonResponse::HTTP_CREATED);
    }

    /**
     * Update kost
     * @param Request $request
     * @param $id
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $kost = $this->kost->findOrFail($id);
            Gate::allows('kost.update', $kost);
            $kost = $kost->update($request->data());
        } catch (InvalidUserTypeException $e) {
            return $this->responseError($e->getMessage(), JsonResponse::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseOk($kost);
    }

    /**
     * Delete kost
     * @param $id
     */
    public function destroy($id)
    {
        try {
            $kost = $this->kost->findOrFail($id);
            Gate::allows('kost.destroy', $kost);
            $kost = $kost->delete();
        } catch (InvalidUserTypeException $e) {
            return $this->responseError($e->getMessage(), JsonResponse::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseOk($kost);
    }

    /**
     * Ask kost availability
     * @param $id
     */
    public function askAvailability($id)
    {
        try {
            $kost = $this->kost->findOrFail($id);
            Gate::allows('kost.ask-availability', $kost);
            $user = app(\Modules\User\Repositories\UserRepository::class);
            if ($user->alreadyAsked($kost->id, auth()->user()->id)) {
                throw new Exception('Sudah pernah ditanyakan');
            }
            $kost->checker()->attach([auth()->user()->id]);
            event(new AskKostAvailability(auth()->user()));
        } catch (OutOfCreditException $e) {
            return $this->responseError($e->getMessage(), JsonResponse::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseOk($kost, JsonResponse::HTTP_OK, 'Berhasil menanyakan ketersediaan');
    }
}
