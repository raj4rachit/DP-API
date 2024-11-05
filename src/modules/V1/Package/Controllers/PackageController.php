<?php

declare(strict_types=1);

namespace Modules\V1\Package\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\V1\Package\Models\Package;
use Modules\V1\Package\Requests\PackageCreateRequest;
use Modules\V1\Package\Resources\PackageResource;
use OpenApi\Annotations as OA;
use SebastianBergmann\Invoker\Exception;
use Shared\Helpers\ResponseHelper;

final class PackageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/package",
     *     summary="Get list of all Packages",
     *     tags={"Packages"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Package data getting successfully",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/PackageResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     * Get all Packages
     */
    public function index(): JsonResponse
    {
        try {
            $packages = Package::all();

            return ResponseHelper::success(PackageResource::collection($packages), 'Package data getting successfully. ');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/package",
     *     summary="Create a new Package",
     *     operationId="createPackage",
     *     tags={"Packages"},
     *     security={{"bearerAuth":{}}},
     *     description="Create a new Package with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="test name"),
     *                  @OA\Property(property="description",type="string", example="Asddfdsfererer"),
     *                  @OA\Property(property="total_patients",type="string", example="10"),
     *                  @OA\Property(property="patient_charge",type="string", example="100"),
     *                  @OA\Property(property="is_default",type="string", example="1"),
     *                  @OA\Property(property="status",type="string", example="Active"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Package created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Package created successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="string", example="201"),
     *             @OA\Property(property="data", ref="#/components/schemas/PackageResource"),
     *         )
     *     ),
     *
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function store(PackageCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Package Creation
            $package = new Package();
            $package->name = $request->name;
            if ('' !== $request->description) {
                $package->description = $request->description;
            }
            $package->save();

            DB::commit();

            return ResponseHelper::success(new PackageResource($package), 'Package created successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/package/{id}",
     *     summary="Show a specific Package",
     *     operationId="showPackage",
     *     tags={"Packages"},
     *     description="Fetch details of a Package by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Package to show",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Package details",
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Package data fetch successfully"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="statusCode", type="integer", example=200),
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/PackageResource"),
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized access"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=401)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Invalid Package ID"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="An error occurred on the server"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $package = Package::findOrFail($id);
            if ( ! $package) {
                return ResponseHelper::error('Package not found');
            }

            return ResponseHelper::success(new PackageResource($package), 'Package data fetched successfully');
        } catch (Exception $e) {
            log::error($e->getMessage());

            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/package/{id}",
     *     summary="Update a specific Package",
     *     operationId="updatePackage",
     *     tags={"Packages"},
     *     description="Update the details of a Package by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Package to update",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated Package data",
     *
     *         @OA\MediaType(
     *              mediaType="application/json",
     *
     *              @OA\Schema(
     *
     *                  @OA\Property(property="name", type="string", example="My company", description="Package name"),
     *                  @OA\Property(property="description", type="string", example="safsdfsf", description="Package description"),
     *                  @OA\Property(property="total_patients",type="string", example="10"),
     *                  @OA\Property(property="patient_charge",type="string", example="100"),
     *                  @OA\Property(property="is_default",type="string", example="1"),
     *                  @OA\Property(property="status",type="string", example="Active"),
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Package updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/PackageResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Package not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:packages,name,' . $id . ',uuid',
                'description' => 'nullable|string',
                'total_patients' => 'required|numeric',
                'patient_charge' => 'required|numeric',
                'is_default' => 'required|numeric|in:0,1',
                'status' => 'required|string|in:Active,Inactive,Canceled',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            $package = Package::findOrFail($id);
            if ( ! $package) {
                return ResponseHelper::error('Package not found');
            }

            $package->update($request->all());
            DB::commit();

            return ResponseHelper::success(new PackageResource($package), 'Package updated successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }

    }

    /**
     * @OA\Delete(
     *     path="/package/{id}",
     *     summary="Delete a specific Package",
     *     operationId="deletePackage",
     *     tags={"Packages"},
     *     description="Delete a Package by their ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Package to be deleted",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Package deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Package not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $package = Package::findOrFail($id);
            if ( ! $package) {
                return ResponseHelper::error('Package not found');
            }

            $package->delete();
            DB::commit();

            return ResponseHelper::success(null, 'Package deleted successfully');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return ResponseHelper::error($exception->getMessage(), 500);
        }
    }
}
