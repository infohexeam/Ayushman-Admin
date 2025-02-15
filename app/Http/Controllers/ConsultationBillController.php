<?php

namespace App\Http\Controllers;
use App\Models\Mst_Patient;
use App\Models\Trn_Consultation_Booking;
use App\Models\Mst_Master_Value;
use App\Models\Mst_User;
use App\Models\Mst_Staff;
use App\Models\Trn_Consultation_Booking_Invoice;
use App\Models\Trn_Ledger_Posting;
use App\Models\Mst_Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Trn_Consultation_Booking_Invoice_Payment;

class ConsultationBillController extends Controller
{
public function consultationBill(Request $request)
{

        $userTypeId = Auth::user()->user_type_id;
        $branchId = null;

        if ($userTypeId != 1) {
            
            $staffId = Auth::user()->staff_id;
            $branchId = Mst_Staff::where('staff_id',$staffId)->pluck('branch_id');

         $patients = Trn_Consultation_Booking::leftJoin('mst_patients', 'mst_patients.id', '=', 'trn_consultation_bookings.patient_id')
        ->leftJoin('mst_master_values as booking_type', 'booking_type.id', '=', 'trn_consultation_bookings.booking_type_id')
        ->leftJoin('mst_timeslots', 'trn_consultation_bookings.time_slot_id', '=', 'mst_timeslots.id')
        ->leftJoin('mst_staffs', 'mst_staffs.staff_id', '=', 'trn_consultation_bookings.doctor_id')
        ->leftJoin('mst_branches', 'mst_branches.branch_id', '=', 'trn_consultation_bookings.branch_id')
        ->leftJoin('mst_master_values as booking_status', 'booking_status.id', '=', 'trn_consultation_bookings.booking_status_id')
        ->where('trn_consultation_bookings.branch_id', $branchId)
        ->where('trn_consultation_bookings.booking_type_id', 84)
        ->where('trn_consultation_bookings.is_billable', 0)
        ->select('trn_consultation_bookings.*', 'mst_patients.patient_code', 'mst_patients.patient_name', 'mst_patients.patient_email', 'mst_patients.patient_mobile', 'booking_type.master_value', 'booking_status.master_value','mst_staffs.*','mst_branches.*');
        
        if ($request->filled('patient_name')) {
            $patients->where('mst_patients.patient_name', $request->input('patient_name'));
        }
        
        if ($request->filled('booking_date')) {
            $patients->whereDate('trn_consultation_bookings.booking_date', $request->input('booking_date'));
        }
       if ($request->filled('booking_id')) {
            $bookingId = $request->input('booking_id');
            $patients->where('trn_consultation_bookings.booking_reference_number', 'like', "%$bookingId%");
        }

                    
        $patients = $patients->orderBy('trn_consultation_bookings.booking_date', 'desc')->take(20)->get();
               
        $pageTitle = "Consultation Billing";

        $patientLists = Mst_Patient::get();
        
        return view('consultation-bill.index', compact('patients', 'pageTitle','patientLists'));
    }
    else{
        
        $patients = Trn_Consultation_Booking::leftJoin('mst_patients', 'mst_patients.id', '=', 'trn_consultation_bookings.patient_id')
        ->leftJoin('mst_master_values as booking_type', 'booking_type.id', '=', 'trn_consultation_bookings.booking_type_id')
        ->leftJoin('mst_timeslots', 'trn_consultation_bookings.time_slot_id', '=', 'mst_timeslots.id')
        ->leftJoin('mst_staffs', 'mst_staffs.staff_id', '=', 'trn_consultation_bookings.doctor_id')
        ->leftJoin('mst_branches', 'mst_branches.branch_id', '=', 'trn_consultation_bookings.branch_id')
        ->leftJoin('mst_master_values as booking_status', 'booking_status.id', '=', 'trn_consultation_bookings.booking_status_id')
        ->where('trn_consultation_bookings.booking_type_id', 84)
        ->where('trn_consultation_bookings.is_billable', 0)
        ->select('trn_consultation_bookings.*', 'mst_patients.patient_code', 'mst_patients.patient_name', 'mst_patients.patient_email', 'mst_patients.patient_mobile', 'booking_type.master_value', 'booking_status.master_value','mst_staffs.*','mst_branches.*');
        
        // Apply branch filter based on user type
        if (Auth::user()->user_type_id == 18 || Auth::user()->user_type_id == 21 || Auth::user()->user_type_id == 20) {
            $branch_id = Auth::user()->staff->branch->branch_id;
            if ($branch_id) {
                $patients->where('mst_branches.branch_id', $branch_id);
            }
        } else {
            // Apply branch filter based on pharmacy session
            if (session()->has('pharmacy_id') && session()->has('pharmacy_name') && session('pharmacy_id') != "all") {
                $pharmacy_id = session('pharmacy_id');
                $pharmacy = Mst_Pharmacy::with('branch')->find($pharmacy_id);
                if ($pharmacy && $pharmacy->branch) {
                    $branch_id = $pharmacy->branch;
                    $patients->where('mst_branches.branch_id', $branch_id);
                }
            }
        }


    if ($request->filled('patient_name')) {
        $patients->where('mst_patients.patient_name', $request->input('patient_name'));
    }

    if ($request->filled('booking_date')) {
        $patients->whereDate('trn_consultation_bookings.booking_date', $request->input('booking_date'));
    }
     if ($request->filled('booking_id')) {
        $bookingId = $request->input('booking_id');
        $patients->where('trn_consultation_bookings.booking_reference_number', 'like', "%$bookingId%");
    }

                
    $patients = $patients->orderBy('trn_consultation_bookings.booking_date', 'desc')->take(20)->get();

        
    $pageTitle = "Consultation Billing";

    $patientLists = Mst_Patient::get();

    return view('consultation-bill.index', compact('patients', 'pageTitle','patientLists'));
    }
    }


        public function generateInvoice($id)
        {
            $invoice  = Trn_Consultation_Booking::leftJoin('mst_patients', 'mst_patients.id', '=', 'trn_consultation_bookings.patient_id')
            ->leftJoin('mst_staffs', 'mst_staffs.staff_id', '=', 'trn_consultation_bookings.doctor_id')
            ->where('trn_consultation_bookings.id', $id)->first(); 
             $booking_id = $id;
        
            $pageTitle = "Consultation Billing";
            $user_id = Auth::id();
            $discount = Mst_User::where('user_id',$user_id)->value('discount_percentage');
            $patientLists = Mst_Patient::get();
            $paymentType = Mst_Master_Value::where('master_id', 25)->pluck('master_value', 'id');
            return view('consultation-bill.create', compact('invoice', 'pageTitle','patientLists','paymentType','discount','booking_id'));
        }

        public function saveInvoice(Request $request, $id)
        {
            $request->validate([
                'booking_id' => 'required',
                'branch_id' => 'required',
                'booking_date' => 'required',
                'invoice_date' => 'required',
                'consultation_fee' => 'required',
            ]);
            $user_id = Auth::id();
            $invoice = new Trn_Consultation_Booking_Invoice();
            $invoice->booking_id = $request->input('booking_id');
            $invoice->branch_id = $request->input('branch_id');
            $invoice->booking_date = $request->input('booking_date');
            $invoice->invoice_date = $request->input('invoice_date');
            $invoice->paid_amount = $request->input('discountAmount');
            $invoice->created_by = $user_id;
            $invoice->save();

            // Updating the booking_invoice_number field
            $invoice->booking_invoice_number = 'INV100' . $invoice->id;
            $invoice->bill_token = 'CN_TKN' . $invoice->id;
            $invoice->save();

            $booking = Trn_Consultation_Booking::find($request->input('booking_id'));
            if ($booking) {
                $booking->is_billable = 1;
                $booking->booking_status_id = 88;
                $booking->save();
            }

            foreach ($request->input('amount') as $key => $value) {
                $invoicePayment = new Trn_Consultation_Booking_Invoice_Payment();
                $invoicePayment->consultation_booking_invoice_id = $invoice->id;
                $invoicePayment->paid_amount = $request->input('amount')[$key];
                $invoicePayment->payment_mode = $request->input('payment_mode')[$key];
                $invoicePayment->deposit_to = $request->input('deposit_to')[$key];
                $invoicePayment->reference_no = $request->input('reference_no')[$key];
                // Add other fields as needed
                $invoicePayment->save();
            }



            // Check if payment_mode and deposit_to exist in the request and set them if true
            if ($request->has('payment_mode') && $request->has('deposit_to')) {
                $invoice->paid_amount = $request->input('paid_amount');
                $invoice->discount = $request->input('discount');
                $invoice->amount = $request->input('discountAmount');
                $invoice->is_paid = 1;
                $invoice->save();

        //Accounts Payable
        Trn_Ledger_Posting::create([
            'posting_date' => Carbon::now(),
            'master_id' => 'WL_TKN' . $invoice->id,
            'account_ledger_id' => 1,
            'entity_id' => $request->patient_id,
            'debit' => $request->input('discountAmount'),
            'credit' => 0,
            'branch_id' => $request->input('branch_id'),
            'transaction_id' =>  $invoice->id,
            'narration' => 'Wellness Booking Invoice Payment'
        ]);

        // Consulting Revenue
        Trn_Ledger_Posting::create([
            'posting_date' => Carbon::now(),
            'master_id' => 'WL_TKN' . $invoice->id,
            'account_ledger_id' => 85,
            'entity_id' => 0,
            'debit' => 0,
            'credit' => $request->input('discountAmount'),
            'branch_id' => $request->input('branch_id'),
            'transaction_id' =>  $invoice->id,
            'narration' => 'Wellness Booking Invoice Payment'
        ]);
        //Accounts Receivable
        Trn_Ledger_Posting::create([
            'posting_date' => Carbon::now(),
            'master_id' => 'WL_TKN' . $invoice->id,
            'account_ledger_id' => 1,
            'entity_id' => $request->patient_id,
            'debit' => 0,
            'credit' => $request->input('discountAmount'),
            'branch_id' => $request->input('branch_id'),
            'transaction_id' =>  $invoice->id,
            'narration' => 'Wellness Booking Invoice Payment'
        ]);
        //Cash or Bank Account
        Trn_Ledger_Posting::create([
            'posting_date' => Carbon::now(),
            'master_id' => 'WL_TKN' . $invoice->id,
            'account_ledger_id' => 4,
            'entity_id' => $request->patient_id,
            'debit' => $request->input('discountAmount'),
            'credit' => 0,
            'branch_id' => $request->input('branch_id'),
            'transaction_id' =>  $invoice->id,
            'narration' => 'Wellness Booking Invoice Payment'
        ]);
            }

            return redirect()->route('consultation-bill.index')->with('success', 'Invoice created successfully');
        }
        
}
