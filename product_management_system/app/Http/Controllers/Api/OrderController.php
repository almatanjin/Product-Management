<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Order;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Requests\StoreOrderRequest;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('customer')->get();
       
        if(count($orders) === 0)
        {
            return response()->json([
                'message' => 'No data Found',
                'success' => false,
            ], 400);
        }

        return response()->json([
            'data' => $orders,
            'success' => true,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        
        try 
        {
            $order = Order::create($request->all());

        return response()->json([
            'data' => $order,
            'success' => true,
            'message' => 'Order created Succesfully'
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
            $order = Order::with('customer')->findOrFail($id);
    
            return response()->json([
                'data' => $order,
                'success' => true,
                'message' => 'Order retrieved successfully',
            ], 200); 
            

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order not found',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        try {
            
            $order = Order::findOrFail($id);
            $order->update($request->validated());
    
            return response()->json([
                'data' => $order,
                'success' => true,
                'message' => 'Order updated successfully',
            ], 200); 
            

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order not found',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update Order information',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 
        }
    }


    public function destroy(string $id)
    {
        try {
            $order = Order::findOrFail($id);
    
            $order->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order not found',
                'success' => false,
                'message' => $e->getMessage()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to remove Order',
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */


     public function exportCSV()
    {
        $filename = 'order-data.csv';
 
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
 
        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
 
            // Add CSV headers
            fputcsv($handle, [
                'Customer',
                'Total Amount',
                'Status',
            ]);
 
             // Fetch and process data in chunks
            Order::with('customer')->chunk(25, function ($orders) use ($handle) {
                foreach ($orders as $order) {
             // Extract data from each employee.
                    $data = [
                        isset($order->customer->first_name)? $order->customer->first_name. " " . $order->customer->last_name : '',
                        isset($order->total_amount)? $order->total_amount : '',
                        isset($order->status)? $order->status : '',
                    ];
 
             // Write data to a CSV file.
                    fputcsv($handle, $data);
                }
            });
 
            // Close CSV file handle
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
            'error' => 'Failed to update Orders',
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
        $customer_id = $row[0] ?? '';
        $total_amount = $row[1] ?? '';
        $status = $row[2] ?? ''; 

        $order = new Order();
        $order->customer_id = $customer_id;
        $order->total_amount = $total_amount;
        $order->status = $status;
        $order->save();
    }
}
    
}
