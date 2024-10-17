<?php

declare(strict_types=1);

namespace Modules\V1\User\Controllers;

//use App\Http\Controllers\V1\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\V1\User\Models\Permission;
use Modules\V1\User\Requests\PermissionUpdateRequest;
use Modules\V1\User\Resources\PermissionResource;
use OpenApi\Annotations as OA;
use Shared\Helpers\ResponseHelper;

final class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/permission/list",
     *      summary="Get All permissions",
     *      description="Display all the permissions",
     *      operationId="showpermissions",
     *      tags={"Permission"},
     *
     *      @OA\RequestBody(
     *       ),
     *
     *      @OA\Response(
     *          response=204,
     *          description="Permissions data show successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Permissions data get successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=204),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/PermissionResource"),
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
            $permissionsList = PermissionResource::collection(Permission::all());
            $data = $this->groupPermissionsByPrefix($permissionsList);
            return ResponseHelper::success(data: $data, message: 'Permissions data getting successfully. ');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/permission/{id}",
     *     summary="Update permission",
     *     description="Updates the permission's name",
     *     operationId="updatePermissionProfile",
     *     tags={"Permission"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the permission to update",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/PermissionUpdateRequest")
     *      ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Permission updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Permission updated successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=204),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/PermissionResource"),
     *         ),
     *     ),
     *
     *     @OA\Response(response=401, ref="#/components/responses/401"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500"),
     * )
     */
    public function update(PermissionUpdateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $permission = Permission::where('id', $request->id)->first();
            $permission->update([
                'name' => $request->name,
            ]);
            DB::commit();

            return ResponseHelper::success(data: new PermissionResource($permission), message: 'Permission updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/permission/",
     *      summary="Create a new permission",
     *      tags={"Permission"},
     *      security={{"bearerAuth":{}}},
     *      description="Create a new permission with the provided information.",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", description="Permission's name"),
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Permission Created successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Permission created successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="string", example="201"),
     *              @OA\Property(property="data", ref="#/components/schemas/PermissionResource"),
     *          )
     *      ),
     *
     *      @OA\Response(response=422, ref="#/components/responses/422"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     * )
     */
    public function create(PermissionUpdateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->save();
            DB::commit();

            return ResponseHelper::success(data: new PermissionResource($permission), message: 'Permission created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * Group permissions by their prefix (like "doctor", "role")
     */
    public function groupPermissionsByPrefix($permissions)
    {
        $grouped = [];

        foreach ($permissions as $permission) {
            // Extract the prefix (first part of the name before the '-')
            $prefix = explode('-', $permission['name'])[0];

            // Group by the prefix
            $grouped[$prefix][] = $permission;
        }

        return $grouped;
    }
}
