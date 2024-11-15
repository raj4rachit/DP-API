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
     *     summary="Retrieve a list of all Packages",
     *     tags={"Packages"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the list of packages",
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
     *     description="Create a new package with the provided information.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Package details to be created",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "total_patients", "patient_charge", "is_default", "status"},
     *
     *                 @OA\Property(property="name", type="string", example="test name", description="The name of the package"),
     *                 @OA\Property(property="description",type="string",example="A detailed description of the package",description="Optional description of the package"),
     *                 @OA\Property(property="total_patients",type="number",example="10",description="Total number of patients in the package"),
     *                 @OA\Property(property="patient_charge",type="number",example="100",description="Charge per patient in the package"),
     *                 @OA\Property(property="is_default",type="string",example="1",description="Indicates whether this package is the default (1 = true, 0 = false)"),
     *                 @OA\Property(property="status",type="string",example="Active",description="Current status of the package (e.g., Active, Inactive, Canceled)"),
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
     *             @OA\Property(property="statusCode", type="integer", example=201),
     *             @OA\Property(property="data", ref="#/components/schemas/PackageResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error, invalid input data",
     *         ref="#/components/responses/422"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error, unexpected server issue",
     *         ref="#/components/responses/500"
     *     )
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
     *     summary="Show details of a specific Package",
     *     operationId="showPackage",
     *     tags={"Packages"},
     *     description="Fetch the details of a package by its ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the package to fetch",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Package details fetched successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Package data fetched successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/PackageResource")
     *         )
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
     *         description="Validation error - Invalid package ID",
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
     *
     *     security={{"bearerAuth":{}}}
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
     *     description="Update the details of a package by its ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the package to update",
     *
     *         @OA\Schema(type="string", example="9d445a1c-cee5-4a68-b729-9edf8df71d87")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated package data",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "total_patients", "patient_charge", "is_default", "status"},
     *
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="My company",
     *                     description="The name of the package"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     example="Detailed package description",
     *                     description="Description of the package's features or purpose"
     *                 ),
     *                 @OA\Property(
     *                     property="total_patients",
     *                     type="string",
     *                     example="10",
     *                     description="Total number of patients in the package"
     *                 ),
     *                 @OA\Property(
     *                     property="patient_charge",
     *                     type="string",
     *                     example="100",
     *                     description="Charge per patient for the package"
     *                 ),
     *                 @OA\Property(
     *                     property="is_default",
     *                     type="string",
     *                     example="1",
     *                     description="Indicates if this package is the default (1 = true, 0 = false)"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="Active",
     *                     description="Current status of the package (e.g., Active, Inactive, Canceled)"
     *                 )
     *             )
     *         )
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
     *         description="Package not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Package not found"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Invalid data provided for package"),
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
     *             @OA\Property(property="message", type="string", example="An error occurred while updating the package"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
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
     *     description="Delete a package by its ID.",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the package to delete",
     *
     *         @OA\Schema(
     *             type="string",
     *             example="9d445a1c-cee5-4a68-b729-9edf8df71d87"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Package deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Package deleted successfully"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="statusCode", type="integer", example=204)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Package not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Package not found"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=404)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="An error occurred while deleting the package"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="statusCode", type="integer", example=500)
     *         )
     *     ),
     *
     *     security={{"bearerAuth":{}}}
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
