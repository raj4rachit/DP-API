<?php

declare(strict_types=1);

namespace Modules\V1\User\Controllers;

//use App\Http\Controllers\V1\Controller;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Requests\RoleUpdateRequest;
use Modules\V1\User\Resources\RoleResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class RoleController extends Controller
{
    /**
     * @OA\Get(
     *      path="/role/list",
     *      summary="Get All roles",
     *      description="Display all the roles",
     *      operationId="showRoles",
     *      tags={"Role"},
     *
     *      @OA\RequestBody(
     *       ),
     *
     *      @OA\Response(
     *          response=204,
     *          description="Roles data show successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Roles data get successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=204),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/RoleResource"),
     *          ),
     *      ),
     *
     *      @OA\Response(response=401, ref="#/components/responses/401"),
     *      @OA\Response(response=422, ref="#/components/responses/422"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     *       security={
     *           {"bearerAuth": {}}
     *       }
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return ResponseHelper::success(data: RoleResource::collection(Role::all()), message: 'Roles data getting successfully. ');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return ResponseHelper::error($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/role/{id}",
     *     summary="Update role",
     *     description="Updates the role's name",
     *     operationId="updateRoleProfile",
     *     tags={"Role"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the role to update",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/RoleUpdateRequest")
     *      ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Role updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Role updated successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=204),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/RoleResource"),
     *         ),
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500"),
     * )
     */
    public function update(RoleUpdateRequest $request): JsonResponse
    {
        $role = Role::where('id', $request->id)->first();
        $role->update([
            'name' => $request->name,
        ]);

        return ResponseHelper::success(data: new RoleResource($role), message: 'Role updated successfully');
    }

    /**
     * @OA\Post(
     *      path="/role/",
     *      summary="Create a new role",
     *      tags={"Role"},
     *      security={{"bearerAuth":{}}},
     *      description="Create a new role with the provided information.",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", description="Role's name"),
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Role Created successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Role created successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="string", example="201"),
     *              @OA\Property(property="data", ref="#/components/schemas/RoleResource"),
     *          )
     *      ),
     *
     *      @OA\Response(response=422, ref="#/components/responses/422"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     * )
     */
    public function store(RoleUpdateRequest $request): JsonResponse
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return ResponseHelper::success(data: new RoleResource($role), message: 'Role created successfully');
    }
}
