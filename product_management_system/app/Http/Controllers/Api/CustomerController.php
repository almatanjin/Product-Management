<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
       
        if(count($customers) === 0)
        {
            return response()->json([
                'message' => 'No data Found',
                'success' => false,
            ], 400);
        }

        return response()->json([
            'data' => $customers,
            'success' => true,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        try 
        {
            $customer = Customer::create($request->all());

        return response()->json([
            'data' => $customer,
            'success' => true,
            'message' => 'Customer created succesfully'
        ], 201);
        }

        catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create',
                'success' => false,
                'message' => $e->getMessage(),
            ], 500); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
    
            return response()->json([
                'data' => $customer,
                'success' => true,
                'message' => 'Customer retrieved successfully',
            ], 200); 
            

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Customer not found',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        try {
            
            $customer = Customer::findOrFail($id);

            $customer->update($request->validated());
    
            return response()->json([
                'data' => $customer,
                'success' => true,
                'message' => 'Customer updated successfully',
            ], 200); 
            

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Customer not found',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update Customer information',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
    
            $customer->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Customer not found',
                'success' => false,
                'message' => $e->getMessage()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to remove Customer',
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportCSV()
    {
        $filename = 'customer-data.csv';
 
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
 
        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Address'
            ]);
 
            Customer::chunk(25, function ($customers) use ($handle) {
                foreach ($customers as $customer) {
                    $data = [
                        isset($customer->first_name)? $customer->first_name : '',
                        isset($customer->last_name)? $customer->last_name : '',
                        isset($customer->email)? $customer->email : '',
                        isset($customer->phone)? $customer->phone : '',
                        isset($customer->address)? $customer->address : '',
                    ];
 
                    fputcsv($handle, $data);
                }
            });
 
            fclose($handle);
        }, 200, $headers);
    }


    public function importCSV(Request $request)

    {
    // Validate the incoming request
    $request->validate([
        'import_csv' => 'required|mimes:csv,txt', // Allow .csv and .txt files
    ]);

    $file = $request->file('import_csv');

    // Open the file for reading
    $handle = fopen($file->path(), 'r');

    if ($handle === false) {
        return response()->json([
            'error' => 'Failed to update Customers',
            'success' => false,
        ], 404); 

    }

    fgetcsv($handle);

    $chunkSize = 25;

    while (!feof($handle)) {
        $chunkData = [];

        for ($i = 0; $i < $chunkSize; $i++) {
            $data = fgetcsv($handle);

            if ($data === false) {
                break;
            }

            $chunkData[] = $data;
        }
        $this->processChunkData($chunkData);
    }

    fclose($handle);

    return response()->json([
        'message' => 'Data has been added successfully.',
        'success' => true,
    ], 200); 

}

public function processChunkData($chunkData)
{
    
    foreach ($chunkData as $row) {
        $first_name = $row[0] ?? '';
        $last_name = $row[1] ?? '';
        $email = $row[2] ?? ''; 
        $phone = $row[3] ?? '';
        $address = $row[4] ?? '';

        $customer = new Customer();
        $customer->first_name = $first_name;
        $customer->last_name = $last_name;
        $customer->email = $email;
        $customer->phone = $phone;
        $customer->address = $address;
        $customer->save();
    }
}
}
