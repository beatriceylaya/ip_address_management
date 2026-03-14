<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIpRequest;
use App\Http\Requests\UpdateIpRequest;
use App\Http\Resources\IpAddressResource;
use App\Models\IpAddress;
use Illuminate\Http\Request;

class IpAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ipAddresses = IpAddress::paginate($request->input('per_page', 10));

        return IpAddressResource::collection($ipAddresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIpRequest $request)
    {
        $ipAddress = $request->user()
            ->ipAddresses()
            ->create($request->validated());

        return response()->json($ipAddress, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(IpAddress $ipAddress)
    {
        return response()->json($ipAddress);
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(UpdateIpRequest $request, IpAddress $ipAddress)
    {
        $this->authorize('update', $ipAddress);
        $ipAddress->update($request->validated());

        return response()->json($ipAddress, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IpAddress $ipAddress)
    {
        $this->authorize('delete', $ipAddress);
        $ipAddress->delete();

        return response()->json(null, 204);
    }
}
