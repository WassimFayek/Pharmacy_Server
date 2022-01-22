<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use App\Models\Image;
use App\Models\contact_information;
use Illuminate\Support\Str;
use File;
use Illuminate\Support\Facades\DB;
class PharmacyController extends Controller
{


    public function add_pharmacy(Request $request)
    {       
        //return Auth::user()->role;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,30',
            'mapLocation' => 'required|string',
            'address' => 'required|string|min:6',
            'delivery' => 'required|',
           
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }

        $user = Pharmacy::create(array_merge(
            $validator->validated(),
           
            
        ));
      
        return response()->json([
            'status' => true,
            'message' => 'pharmacy successfully registered',
            'pharmacy' => $user
        ], 201);
    
    }
  
    
    
  

    public function add_image(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'pharmacy_id' => 'required|integer',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg', 
        ]);
        
        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }
        // store image base64
        $path=public_path();
        $imageName =  Str::random(10).'.'.'png';
        $image = base64_encode(file_get_contents($request->file('image'))); 
        File::put($path. '/image/' .$imageName, base64_decode($image));

        $pharmacy_image = new Image;
        $pharmacy_image->image =  $imageName;
        $pharmacy_image->pharmacy_id = $request->pharmacy_id;
        $pharmacy_image->save();

        return response()->json([
            'status' => true,
            'message' => 'Image successfully stored',
            'image' => $pharmacy_image,
            'file'=>$image,
        ], 201);
        
    }

    public function add_contact_information(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'phone_one' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'phone_two' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'email' => 'required|email|unique:users',
            'pharmacy_id' => 'required|integer',
            
        ]);
        
        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }
        
        $contact_info = new contact_information;
        $contact_info->phone_one =  $request->phone_one;
        $contact_info->phone_two =  $request->phone_two;
        $contact_info->email =  $request->email;
        $contact_info->pharmacy_id = $request->pharmacy_id;
        $contact_info->save();

        return response()->json([
            'status' => true,
            'message' => 'contact information added',
            'contact information' => $contact_info,   
        ], 201);
        
    }

    public function get_pharmacies()
    {
        $pharmacies = Pharmacy::all();
        return response()->json([$pharmacies]);

    }

    function delete_pharmacy(Request $request){

        $pharmacy = Pharmacy::where('id', $request->id)->delete();

        return response()->json([
            'status' => true, 
            'message' => "This record successfully deleted"]);
        
    }

    function Edit_pharmacy(Request $request){
        $pharmacy_id = $request->id;
        $pharmacy = Pharmacy::find($pharmacy_id);
        $pharmacy->name = $request->name;
        $pharmacy->mapLocation = $request->mapLocation;
        $pharmacy->address = $request->address;
        $pharmacy->delivery = $request->delivery;
        $pharmacy->save();


        return response()->json([
            'status' => true, 
            'message' => "This record successfully updated"]);
        
    }



    public function searchPharmacy(Request $request){
   
        $name = '%'.$request->name."%";
        $filterData = DB::table('pharmacies')->where('name','LIKE',$name)
                      ->get();
        if($request->delivery){
            $filterData = DB::table('pharmacies')->where('name','LIKE',$name)
                                                 ->where('delivery', $request->delivery)   
                                                 ->get();
        }
        if($request->nearby){
            $filterData = DB::table('pharmacies')->where('name','LIKE',$name)
            ->where('name','LIKE',$name)
            ->where('delivery', $request->delivery)
            ->where('address', $request->nearby)     
            ->get();
        }
        
        if(count($filterData) > 0){
            return response()->json($filterData, 200);
        }else{
            $response['status'] = "No results found";
            return response()->json($response, 200);
        }
    }


}
