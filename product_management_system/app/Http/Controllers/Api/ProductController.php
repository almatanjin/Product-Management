<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        if(count($products) === 0)
        {
            return response()->json([
                'message' => 'No data Found',
                'success' => false,
            ], 400);
        }

        return response()->json([
            'data' => $products,
            'success' => true,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try 
        {
  
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $filename, 'public');

           $product = Product::create([
            'name' => $request->input('name'),
            'image' => $path,
            'description' => $request->input('description'),
            'price' => $request->input('price')
           ]);

        return response()->json([
            'data' => $product,
            'success' => true,
            'message' => 'Product created Succesfully'
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
            $product = Product::findOrFail($id);
    
            return response()->json([
                'data' => $product,
                'success' => true,
                'message' => 'Product retrieved successfully',
            ], 200); 

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            
            $product = Product::findOrFail($id);

            $product->update($request->validated());

            return $request;
    
            return response()->json([
                'data' => $product,
                'success' => true,
                'message' => 'Product updated successfully',
            ], 200); 
            

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404); 

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update product',
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
            $product = Product::findOrFail($id);
    
            $product->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
                'success' => false,
                'message' => $e->getMessage()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete the product',
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportCSV()
    {
        $filename = 'product-data.csv';
 
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
                'Name',
                'Description',
                'Price',
            ]);
 
             
            Product::chunk(25, function ($products) use ($handle) {
                foreach ($products as $product) {
             
                    $data = [
                        isset($product->name)? $product->name : '',
                        isset($product->description)? $product->description : '',
                        isset($product->price)? $product->price : '',
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
            'error' => 'Failed to update products',
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
        $name = $row[0] ?? '';
        $description = $row[1] ?? '';
        $price = isset($row[2]) ? (float)$row[2] : 0.0; 
        $product = new Product();
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->save();
    }
}


}
