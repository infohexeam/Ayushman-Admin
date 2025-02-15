<?php

namespace App\Http\Controllers;

use App\Models\Mst_Branch;
use App\Models\Mst_Wellness;
use App\Models\Trn_Wellness_Branch;
use App\Models\Mst_Therapy_Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mst_Wellness_Therapyrooms;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Models\Mst_Pharmacy;

class MstWellnessController extends Controller
{
    public function index(Request $request)
    {

       
            $pageTitle = "Wellnesses";
            $branches = Mst_Branch::where('is_active', 1)->pluck('branch_name', 'branch_id');
            $query = Mst_Wellness::select('mst_wellness.*', 'mst_pharmacies.pharmacy_name')
                ->join('trn_wellness_branches', 'mst_wellness.wellness_id', 'trn_wellness_branches.wellness_id')
                ->join('mst_pharmacies', 'trn_wellness_branches.branch_id', 'mst_pharmacies.id')
                ->orderBy('mst_wellness.created_at', 'desc');
                
                

                if ($request->has('wellness_name')) {   
                    $query->where('mst_wellness.wellness_name', 'LIKE', '%' . $request->wellness_name . '%');
                }
                

            if ($request->has('pharmacy_id')) {
                $query->where('trn_wellness_branches.branch_id', $request->pharmacy_id);
            }

            $wellness = $query->get();
            $pharmacies = Mst_Pharmacy::get();
            
            return view('wellness.index', compact('pageTitle', 'wellness', 'branches','pharmacies'));
        }
  

    public function create()
    {
        try {
            $pageTitle = "Create Wellness";
            $branch = Mst_Branch::where('is_active', 1)->pluck('branch_name', 'branch_id');
            $pharmacies = Mst_Pharmacy::get();
            return view('wellness.create', compact('pageTitle', 'branch','pharmacies'));
        } catch (QueryException $e) {
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'wellness_name' => 'required',
                    'wellness_description' => 'required',
                    'wellness_inclusions' => 'required',
                    'wellness_terms_conditions' => 'required',
                    'branch' => 'required|',
                    'wellness_cost' => 'required|numeric',
                    'wellness_duration' => 'required',
                    'is_active' => 'required',
                    'wellness_image' => 'required|image|mimes:jpeg,png,jpg|',
                ],
                [
                    'wellness_name.required' => 'The wellness name is required.',
                    'wellness_description.required' => 'The wellness description is required.',
                    'wellness_inclusions.required' => 'The wellness inclusions field is required.',
                    'wellness_terms_conditions.required' => 'The wellness terms and conditions are required.',
                    'branch.required' => 'The branch field is required.',
                    'wellness_cost.required' => 'The wellness cost is required.',
                    'wellness_cost.numeric' => 'The wellness cost must be a numeric value.',
                    'wellness_duration.required' => 'The wellness duration field is required.',
                    'is_active.required' => 'The is active field is required.',
                    'wellness_image.required' => 'The wellness image is required.',
                    'wellness_offer_price.required' => 'The wellness offer price is required.',
                ]
            );

            if (!$validator->fails()) {
                $is_active = $request->input('is_active') ? 1 : 0;

                if ($request->hasFile('wellness_image')) {
                    $filename = $request->wellness_image->getClientOriginalName();
                    $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);

                    $new_file_name = $filename_without_ext . time() . '.' . $request->wellness_image->getClientOriginalExtension();

                    $move = $request->wellness_image->move(public_path('assets/uploads/wellness_image'), $new_file_name);

                    // $wellness_image = url("assets/uploads/wellness_image/{$new_file_name}");
                }

                $wellness = Mst_Wellness::create([
                    'wellness_name' => $request->input('wellness_name'),
                    'wellness_description' => $request->input('wellness_description'),
                    'wellness_inclusions' => $request->input('wellness_inclusions'),
                    'wellness_terms_conditions' => $request->input('wellness_terms_conditions'),
                    'wellness_cost' => $request->input('wellness_cost'),
                    'wellness_duration' => $request->input('wellness_duration'),
                    'remarks' => $request->input('remarks'),
                    'is_active' => $is_active,
                    'wellness_image' => $new_file_name,
                    'offer_price' => $request->input('wellness_offer_price'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Check if 'branch' is an array 
                if (is_array($request->input('branch'))) {
                    // Iterate through the selected branches and store them in trn_wellness_branches
                    foreach ($request->input('branch') as $branchId) {
                        Trn_Wellness_Branch::create([
                            'wellness_id' => $wellness->wellness_id, // Link to the newly created wellness record
                            'branch_id' => $branchId,
                        ]);
                    }
                } else {
                    // If 'branch' is a single value, store it in Mst_Wellness table 
                    $wellness->branch_id = $request->input('branch');
                    $wellness->save();
                }
                return redirect()->route('wellness.index')->with('success', 'Wellness added successfully');
            } else {
                $messages = $validator->errors();
                return redirect()->route('wellness.create')->with('errors', $messages);
            }
        } catch (QueryException $e) {
            dd($e->getMessage());
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }

    public function edit($wellness_id)
    {
        try {
            $pageTitle = "Edit Wellness";
            $wellness = Mst_Wellness::findOrFail($wellness_id);
            // $wellness->load('branches');
            $branch_ids = Trn_Wellness_Branch::where('wellness_id', $wellness_id)->pluck('branch_id');
            $pharmacies = Mst_Pharmacy::get();
            $branch = Mst_Branch::where('is_active', 1)->pluck('branch_name', 'branch_id');
            return view('wellness.edit', compact('pageTitle', 'wellness', 'branch', 'branch_ids','pharmacies'));
        } catch (QueryException $e) {
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'wellness_name' => 'required',
                    'wellness_description' => 'required',
                    'wellness_inclusions' => 'required',
                    'wellness_terms_conditions' => 'required',
                    'branch' => 'required|array',
                    'wellness_cost' => 'required|numeric',
                    'wellness_duration' => 'required|integer|min:0',
                    'is_active' => 'required',
                    'wellness_image' => 'image|mimes:jpeg,png,jpg',
                ],
                [
                    'wellness_name.required' => 'The wellness name is required.',
                    'wellness_description.required' => 'The wellness description is required.',
                    'wellness_inclusions.required' => 'The wellness inclusions field is required.',
                    'wellness_terms_conditions.required' => 'The wellness terms and conditions are required.',
                    'branch.required' => 'The branch field is required and must be an array.',
                    'wellness_cost.required' => 'The wellness cost is required.',
                    'wellness_cost.numeric' => 'The wellness cost must be a numeric value.',
                    'wellness_duration.required' => 'The wellness duration field is required.',
                    'is_active.required' => 'The is active field is required.',
                ]
            );

            if (!$validator->fails()) {
                $is_active = $request->input('is_active') ? 1 : 0;

                $new_file_name = $request->saved_img;
                if ($request->hasFile('wellness_image')) {
                    $filename = $request->wellness_image->getClientOriginalName();
                    $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);

                    $new_file_name = $filename_without_ext . time() . '.' . $request->wellness_image->getClientOriginalExtension();

                    $move = $request->wellness_image->move(public_path('assets/uploads/wellness_image'), $new_file_name);

                    // $wellness_image = url("assets/uploads/wellness_image/{$new_file_name}");
                }

                $wellness = Mst_Wellness::find($id);
                // Update the wellness record with the new values
                $wellness->wellness_name = $request->input('wellness_name');
                $wellness->wellness_description = $request->input('wellness_description');
                $wellness->wellness_inclusions = $request->input('wellness_inclusions');
                $wellness->wellness_terms_conditions = $request->input('wellness_terms_conditions');
                $wellness->wellness_cost = $request->input('wellness_cost');
                $wellness->wellness_duration = $request->input('wellness_duration');
                $wellness->remarks = $request->input('remarks');
                $wellness->is_active = $is_active;
                $wellness->wellness_image = $new_file_name;
                $wellness->offer_price = $request->input('wellness_offer_price');
                $wellness->updated_at = Carbon::now();
                $wellness->save();

                // Delete existing records in trn_wellness_branches for this wellness
                Trn_Wellness_Branch::where('wellness_id', $wellness->wellness_id)->delete();

                // Iterate through the selected branches and store them in trn_wellness_branches
                foreach ($request->input('branch') as $branchId) {
                    Trn_Wellness_Branch::create([
                        'wellness_id' => $wellness->wellness_id,
                        'branch_id' => $branchId,
                    ]);
                }
                return redirect()->route('wellness.index')->with('success', 'Wellness updated successfully');
            } else {
                $messages = $validator->errors();
                return redirect()->route('wellness.edit', $id)->with('errors', $messages);
            }
        } catch (QueryException $e) {
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }

    public function show($id)
    {
  
            $pageTitle = "View wellness details";
            $show = Mst_Wellness::findOrFail($id);
            $branch = Mst_Branch::where('is_active', 1)->pluck('branch_name', 'branch_id');
            $branch_ids = Trn_Wellness_Branch::where('wellness_id', $id)->pluck('branch_id');
            $pharmacies = Mst_Pharmacy::get();
            return view('wellness.show', compact('pageTitle', 'show', 'branch', 'branch_ids','pharmacies'));

    }

    public function destroy($wellness_id)
    {

            $wellness = Mst_Wellness::findOrFail($wellness_id);
            $wellness->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wellness deleted successfully',
        ]);
    }


    public function changeStatus(Request $request, $wellness_id)
    {
        try {
            $wellness = Mst_Wellness::findOrFail($wellness_id);
            $wellness->is_active = !$wellness->is_active;
            $wellness->save();
            return 1;
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error in changeStatus method: ' . $e->getMessage());
    
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }
    
    

    public function roomAssign()
    {
        try {
            $pageTitle = "Assign Therapy Room";
            $branches = Mst_Branch::pluck('branch_name', 'branch_id');
            $assignedRooms = Mst_Wellness_Therapyrooms::with(['branch', 'wellness', 'therapyRoom'])
                ->orderBy('mst__wellness__therapyrooms.created_at', 'desc')
                ->get();
            $pharmacies = Mst_Pharmacy::get();

            return view('wellness.room', compact('pageTitle', 'branches', 'assignedRooms','pharmacies'));
        } catch (QueryException $e) {
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }

    public function roomDestroy($wellness_id)
    {
        try {
            $assignedRoom = Mst_Wellness_Therapyrooms::findOrFail($wellness_id);
            $assignedRoom->delete();
            return 1;
        } catch (QueryException $e) {
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }

    public function getBranchWellnessRoomIds($id)
    {
        try {
            // Fetch therapy rooms
            $therapy_rooms = Mst_Therapy_Room::where('branch_id', $id)->where('is_active', 1)->select('room_name', 'id')->get();

            // Fetch wellnesses
            $wellnesses = Mst_Wellness::join('trn_wellness_branches', 'mst_wellness.wellness_id', 'trn_wellness_branches.wellness_id')
                ->where('trn_wellness_branches.branch_id', $id)
                ->where('mst_wellness.is_active', 1)
                ->select(
                    'mst_wellness.wellness_name',
                    'mst_wellness.wellness_id'
                )
                ->get();

            $bRooms = [];
            $bWellnesses = [];

            // Create an array of therapy rooms
            foreach ($therapy_rooms as $room) {
                $bRooms[$room->id] = $room->room_name;
            }

            // Create an array of wellnesses
            foreach ($wellnesses as $wellness) {
                $bWellnesses[$wellness->wellness_id] = $wellness->wellness_name;
            }

            // Return the response as JSON
            return response()->json(['rooms' => $bRooms, 'wellnesses' => $bWellnesses]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function roomStore(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'branch_id' => 'required',
                    'wellness_id' => 'required',
                    'therapy_room_id' => 'required',
                ],
                [
                    'branch_id.required' => 'The branch is required.',
                    'wellness_id.required' => 'The wellness is required.',
                    'therapy_room_id.required' => 'The therapy rrom is required.',
                ]
            );

            if (!$validator->fails()) {

                $count = count($request->therapy_room_id);
                $all_therapy_rooms = $request->therapy_room_id;

                for ($i = 0; $i < $count; $i++) {
                    $exists = Mst_Wellness_Therapyrooms::where('branch_id', $request->input('branch_id'))->where('therapy_room_id', $all_therapy_rooms[$i])->where('wellness_id', $request->input('wellness_id'))->exists();
                    if (!$exists) {
                        Mst_Wellness_Therapyrooms::create([
                            'branch_id' => $request->input('branch_id'),
                            'wellness_id' => $request->input('wellness_id'),
                            'therapy_room_id' => $all_therapy_rooms[$i],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        // return redirect()->route('wellness.room.assign')->with('error', 'This therapy room already assigned for this wellness in this branch.');
                    }
                }
                return redirect()->route('wellness.room.assign')->with('success', 'Therapy room assigned successfully');
            } else {
                $messages = $validator->errors();
                return redirect()->route('wellness.room.assign')->with('errors', $messages);
            }
        } catch (QueryException $e) {
            dd($e->getMessage());
            return redirect()->route('home')->with('error', 'Something went wrong');
        }
    }
        public function getWellnessList($pharmacyId)
        {
            $wellnessOptions = Mst_Wellness::join('trn_wellness_branches', 'mst_wellness.wellness_id', '=', 'trn_wellness_branches.wellness_id')
                ->join('mst_pharmacies', 'trn_wellness_branches.branch_id', '=', 'mst_pharmacies.id')
                ->where('trn_wellness_branches.branch_id', $pharmacyId)
                ->pluck('mst_wellness.wellness_name', 'mst_wellness.wellness_id');
        
            return response()->json($wellnessOptions);
        }

}
