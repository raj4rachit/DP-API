<?php

declare(strict_types=1);

namespace Modules\V1\User\Controllers;

//use App\Http\Controllers\V1\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Requests\RoleCreateRequest;
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
            return ResponseHelper::success(RoleResource::collection(Role::all()), 'Roles data getting successfully. ');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return ResponseHelper::error($exception->getMessage(), 500);
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
        DB::beginTransaction();
        try {
            $role = Role::where('uuid', $request->id)->first();
            $role->update([
                'name' => $request->name,
            ]);

            // Assign multiple permissions to the role
            $role->givePermissionTo($request->permissions);
            DB::commit();

            return ResponseHelper::success(new RoleResource($role), 'Role updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
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
     *                  @OA\Property(property="permissions", type="array", description="Permission's name",
                 *           @OA\Items(type="string")
                 *       )
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
    public function store(RoleCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $role = new Role();
            $role->name = $request->name;
            $role->guard_name = 'sanctum';
            $role->save();

            // Assign multiple permissions to the role
            $role->givePermissionTo($request->permissions);

            DB::commit();
            return ResponseHelper::success(new RoleResource($role), 'Role created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
