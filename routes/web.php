<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MstBranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MstStaffController;
use App\Http\Controllers\MstExternalDoctorController;
use App\Http\Controllers\MstTherapyController;
use App\Http\Controllers\MstTimeSlotController;
use App\Http\Controllers\MstPatientController;
use App\Http\Controllers\MstTherapyRoomController;
use App\Http\Controllers\MstMembershipController;
use App\Http\Controllers\MstWellnessController;
use App\Http\Controllers\MstTherapyRoomSlotController;
use App\Http\Controllers\MstUserController;
use App\Http\Controllers\MstUnitController;
use App\Http\Controllers\MstTaxController;
use App\Http\Controllers\MstMedicineController;
use App\Http\Controllers\TrnConsultationBillingController;
use App\Http\Controllers\BookingTypeController;
use App\Http\Controllers\PatientSearchController;
use App\Http\Controllers\MstSupplierController;
use App\Http\Controllers\Auth\MstAuthController;
use App\Http\Controllers\MstStaffSpecializationController;
use App\Http\Controllers\MstTherapyRoomAssigningController;
use App\Http\Controllers\MstMasterValueController;
use App\Http\Controllers\UserPrivilageController;
use App\Http\Controllers\MstQualificationController;
use App\Http\Controllers\MstMedicineDosageController;
use App\Http\Controllers\MstLeaveTypeController;
use App\Http\Controllers\MstManufacturerController;
use App\Http\Controllers\MstTaxGroupController;
use App\Http\Controllers\AccountSubGroupController;
use App\Http\Controllers\AccountLedgerController;
use App\Http\Controllers\EmployeeBranchTransferController;
use App\Http\Controllers\MedicinePurchaseController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\MedicineSalesController;
use App\Http\Controllers\MedicineSalesReturnController;
use App\Http\Controllers\TrnPrescriptionController;
use App\Http\Controllers\TrnJournelEntryController;
use App\Http\Controllers\StaffLeaveController;
use App\Http\Controllers\TrnMedicinePurchaseInvoiceDetailsController;
use App\Http\Controllers\TrnMedicinePurchaseReturnController;
use App\Http\Controllers\TrnMedicineStockUpdationController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryHeadController;
use App\Http\Controllers\SalaryPackageController;
use App\Http\Controllers\AvailableLeaveController;
use App\Http\Controllers\TherapyStockTransferController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\IncomeExpenseController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BookingSearchController;
use App\Http\Controllers\GeneralController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SalaryProcessingController;
use App\Http\Controllers\ConsultationBillController;
use App\Http\Controllers\WellnessBillController;
use App\Http\Controllers\TherapyBillController;
use App\Http\Controllers\InvoiceSettlemntBillController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchStockTransferController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DoctorLeaveRequestController;
use App\Http\Controllers\DoctorAttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/clear-cache', function () {
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('config:cache');
        $exitCode = Artisan::call('optimize:clear');
        return 'DONE'; //Return anything
    });
//General - Public 
Route::get('/general/index/{branch_code}', [GeneralController::class, 'generalIndex'])->name('general.index');
Route::get('/patient/feedback/{id}/create', [FeedbackController::class, 'create'])->name('customer.feedback.create');
Route::post('/patient/feedback/save', [FeedbackController::class, 'saveFeedback'])->name('customer.feedback.save');
Route::get('/feedback/success', [FeedbackController::class, 'successPage'])->name('feedback.success');


//Authentication:
Route::get('/admin', [MstAuthController::class, 'showLoginForm'])->name('mst_login');
Route::post('/admin-login', [MstAuthController::class, 'login'])->name('mst_login_redirect');
Route::match(['get', 'post'], '/logout', [MstAuthController::class, 'logout'])->name('logout');
Route::get('/verification-request', [MstAuthController::class, 'verificationRequest'])->name('verification.request');
// Route::post('/verify-email', [MstAuthController::class, 'verifyEmail'])->name('verify.email');
Route::post('/reset-password', [MstAuthController::class, 'resetPassword'])->name('reset.password');
//pharmacy id = 96
Route::get('/pharmacy', [MstAuthController::class, 'showPharmacyLoginForm'])->name('mst_login.pharmacy');
Route::post('/pharmacy-login-post', [MstAuthController::class, 'Pharmacylogin'])->name('pharmacy.login.redirect');
//Receptionist id = 18
Route::get('/reception', [MstAuthController::class, 'showReceptionistLoginForm'])->name('mst_login.receptionist');
Route::post('/receptionist-login-post', [MstAuthController::class, 'Receptionistlogin'])->name('receptionist.login.redirect');
//Doctor id = 20
Route::get('/doctor', [MstAuthController::class, 'showDoctorLoginForm'])->name('mst_login.doctor');
Route::post('/doctor-login-post', [MstAuthController::class, 'Doctorlogin'])->name('doctor.login.redirect');
//Accountant id= 21
Route::get('/accountant', [MstAuthController::class, 'showAccountantLoginForm'])->name('mst_login.accountant');
Route::post('/accountant-login-post', [MstAuthController::class, 'Accountantlogin'])->name('accountant.login.redirect');

// Auth::routes();

// Routes to reset password starts
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('password.email.send');
Route::get('reset-password/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('password.update');
// Routes to reset password ends

//Authentication:
Route::middleware('auth')->group(function () {
    Route::middleware(['role:96'])->group(function () {
        Route::get('/pharmacy-home', [DashboardController::class, 'pharmaIndex'])->name('pharmacy.home');
    });
     Route::middleware(['role:18'])->group(function () {
        Route::get('/reception-home', [DashboardController::class, 'receptionIndex'])->name('reception.home');
    });
    
    Route::middleware(['role:20'])->group(function () {
        Route::get('/doctor-home', [DashboardController::class, 'doctorIndex'])->name('doctor.home');
    });
    
    Route::middleware(['role:21'])->group(function () {
        Route::get('/accountant-home', [DashboardController::class, 'accountantIndex'])->name('accountant.home');
    });

    //Route access for admin-pharmacist
    Route::middleware(['role:1|role:96'])->group(function () {

    });
    
     //Route access for admin-receptionist
     Route::middleware(['role:1|role:18'])->group(function () {

     });
     
     //Route access for admin-doctor
    Route::middleware(['role:1|role:20'])->group(function () {

    });
    
     //Route access for admin-accountant
     Route::middleware(['role:1|role:21'])->group(function () {

     });
     
   

    // Dashboard 
    Route::middleware('role:1')->get('/home', [DashboardController::class, 'index'])->name('home');
    Route::middleware('role:1')->post('/save-default-pharmacy', [DashboardController::class, 'savePharmacy'])->name('save-default-pharmacy');
    
    // Medicine dosage - Screen for medicine dosges
    Route::get('/medicine-dosage', [MstMedicineDosageController::class, 'index'])->name('medicine.dosage.index');
    Route::get('/medicine-dosage/create', [MstMedicineDosageController::class, 'create'])->name('medicine.dosage.create');
    Route::post('/medicine-dosage/store', [MstMedicineDosageController::class, 'store'])->name('medicine.dosage.store');
    Route::delete('/medicine-dosage/destroy/{id}', [MstMedicineDosageController::class, 'destroy'])->name('medicine.dosage.destroy');
    Route::get('/medicine-dosage/edit/{id}', [MstMedicineDosageController::class, 'edit'])->name('medicine.dosage.edit');
    Route::patch('medicine-dosage/change-status/{id}', [MstMedicineDosageController::class, 'changeStatus'])->name('medicine.dosage.changeStatus');


    //Manage-Branches:
    Route::get('/branches', [MstBranchController::class, 'index'])->name('branches');
    Route::get('/branches/create', [MstBranchController::class, 'create'])->name('branches.create');
    Route::post('/branches/store', [MstBranchController::class, 'store'])->name('branches.store');
    Route::get('/branches/edit/{branch_id}', [MstBranchController::class, 'edit'])->name('branches.edit');
    Route::get('/branches/show/{branch_id}', [MstBranchController::class, 'show'])->name('branches.show');
    Route::delete('/branches/destroy/{branch_id}', [MstBranchController::class, 'destroy'])->name('branches.destroy');
    Route::patch('branches/change-status/{branch_id}', [MstBranchController::class, 'changeStatus'])->name('branches.changeStatus');
    Route::put('/branches/update/{branch_id}', [MstBranchController::class, 'update'])->name('branches.update');
    Route::post('/branches/restore', [MstBranchController::class, 'restoreBranches'])->name('branches.restore');

    // Manage user privilage 
    Route::get('/user-type/index', [UserPrivilageController::class, 'indexUserType'])->name('usertype.index');
    Route::get('/user-type/create', [UserPrivilageController::class, 'createUserType'])->name('usertype.create');
    Route::post('/user-type/store', [UserPrivilageController::class, 'storeUserType'])->name('usertype.store');
    Route::get('/user-type/edit/{id}', [UserPrivilageController::class, 'editUserType'])->name('usertype.edit');
    Route::delete('/user-type/destroy/{id}', [UserPrivilageController::class, 'destroyUserType'])->name('usertype.destroy');

    // usertype Access 
    Route::get('/user-type-access/create', [UserPrivilageController::class, 'createUserTypeAccess'])->name('usertype.access.create');

    // user wise access 
    Route::get('/user-access/create', [UserPrivilageController::class, 'createUserTypeAccess'])->name('usertype.access.create');

    //Manage-Staffs:
    Route::get('/staffs/index', [MstStaffController::class, 'index'])->name('staffs.index');
    Route::get('/staffs/create', [MstStaffController::class, 'create'])->name('staffs.create');
    Route::post('/staffs/store', [MstStaffController::class, 'store'])->name('staffs.store');
    Route::get('/staffs/edit/{staff_id}', [MstStaffController::class, 'edit'])->name('staffs.edit');
    Route::put('/staffs/update/{staff_id}', [MstStaffController::class, 'update'])->name('staffs.update');
    Route::get('/staffs/show/{staff_id}', [MstStaffController::class, 'show'])->name('staffs.show');
    Route::delete('/staffs/destroy/{staff_id}', [MstStaffController::class, 'destroy'])->name('staffs.destroy');
    Route::post('/staffs/restore', [MstStaffController::class, 'restore'])->name('staffs.restore');
    Route::patch('staffs/change-status/{staff_id}', [MstStaffController::class, 'changeStatus'])->name('staffs.changeStatus');
    Route::get('/getSalaryHeadTypes/{id}', [MstStaffController::class, 'getSalaryHeadTypes']);
    Route::post('/checkUniqueUsername', [MstStaffController::class, 'checkUniqueUsername']);
    Route::post('/checkUniqueEmail', [MstStaffController::class, 'checkUniqueEmail']);
    Route::patch('/update-status/{staffId}', [MstStaffController::class, 'updateStatus'])->name('update-status');
    //Manage-External-Doctors:
    Route::get('/externaldoctors/index', [MstExternalDoctorController::class, 'index'])->name('externaldoctors.index');
    Route::get('/externaldoctors/create', [MstExternalDoctorController::class, 'create'])->name('externaldoctors.create');
    Route::post('/externaldoctors/store', [MstExternalDoctorController::class, 'store'])->name('externaldoctors.store');
    Route::get('/externaldoctors/edit/{id}', [MstExternalDoctorController::class, 'edit'])->name('externaldoctors.edit');
    Route::get('/externaldoctors/show/{id}', [MstExternalDoctorController::class, 'show'])->name('externaldoctors.show');
    Route::delete('/externaldoctors/destroy/{id}', [MstExternalDoctorController::class, 'destroy'])->name('externaldoctors.destroy');
    Route::put('/externaldoctors/update/{id}', [MstExternalDoctorController::class, 'update'])->name('externaldoctors.update');
    Route::patch('externaldoctors/change-status/{id}', [MstExternalDoctorController::class, 'changeStatus'])->name('externaldoctors.changeStatus');
    //Manage-Therapies:
    Route::get('/therapies/index', [MstTherapyController::class, 'index'])->name('therapy.index');
    Route::get('/therapies/create', [MstTherapyController::class, 'create'])->name('therapy.create');
    Route::post('/therapies/store', [MstTherapyController::class, 'store'])->name('therapy.store');
    Route::get('/therapies/edit/{id}', [MstTherapyController::class, 'edit'])->name('therapy.edit');
    Route::delete('/therapies/destroy/{id}', [MstTherapyController::class, 'destroy'])->name('therapy.destroy');
    Route::put('/therapies/update/{id}', [MstTherapyController::class, 'update'])->name('therapy.update');
    Route::patch('therapies/change-status/{id}', [MstTherapyController::class, 'changeStatus'])->name('therapy.changeStatus');

      //Billing
      
      //consultationbilling
        Route::get('/consultation-bill/index', [ConsultationBillController::class, 'consultationBill'])->name('consultation-bill.index');
        Route::post('/consultation-bill/saveinvoice/{id}', [ConsultationBillController::class, 'saveInvoice'])->name('consultation-bill.store');
        Route::get('/consultation-bill/create/{id}', [ConsultationBillController::class, 'generateInvoice'])->name('consultation-bill.create');
        
        
        //wellnessbilling
        Route::get('/wellness-bill/index', [WellnessBillController::class, 'wellnessBill'])->name('wellness-bill.index');
        Route::post('/wellness-bill/saveinvoice/{id}', [WellnessBillController::class, 'saveInvoice'])->name('wellness-bill.store');
        Route::get('/wellness-bill/create/{id}', [WellnessBillController::class, 'generateInvoice'])->name('wellness-bill.create');
        
        //therapybilling
        Route::get('/therapy-bill/index', [TherapyBillController::class, 'therapyBill'])->name('therapy-bill.index');
        Route::post('/therapy-bill/saveinvoice/{id}', [TherapyBillController::class, 'saveInvoice'])->name('therapy-bill.store');
        Route::get('/therapy-bill/create/{id}', [TherapyBillController::class, 'generateInvoice'])->name('therapy-bill.create');
        
        //invoicesettlemnt
        Route::get('/invoice-settlemnt/index', [InvoiceSettlemntBillController::class, 'invoiceSettlemntBill'])->name('invoice-settlemnt.index');
        Route::post('/invoice-settlemnt/saveinvoice/{id}', [InvoiceSettlemntBillController::class, 'saveInvoice'])->name('invoice-settlemnt.store');
        Route::get('/invoice-settlemnt/create/{id}', [InvoiceSettlemntBillController::class, 'generateInvoice'])->name('invoice-settlemnt.create');
        


    //Manage-TimeSlots:
    Route::post('/timeslot/store', [MstTimeSlotController::class, 'store'])->name('timeslot.store');
    Route::patch('timeslot/change-status/{id}', [MstTimeSlotController::class, 'changeStatus'])->name('timeslot.changeStatus');
    Route::resource('timeslot', MstTimeSlotController::class);
    



    //Assigning timeslot for a particular staff:
    Route::get('/timeslot-staff/slot/{id}', [MstTimeSlotController::class, 'slotIndex'])->name('staff.slot');
    Route::post('/timeslot-staff/store', [MstTimeSlotController::class, 'slotStore'])->name('timeslotStaff.store');
    Route::get('/timeslot-staff/destroy/{id}', [MstTimeSlotController::class, 'slotDelete'])->name('timeslotStaff.destroy');

    // Assigning timeslot for a particular therapy room
    Route::get('/therapyroom-slot-assigning/index/{id}', [MstTherapyRoomSlotController::class, 'index'])->name('slot_assigning.index');
    Route::post('/therapyroom-slot/store', [MstTherapyRoomSlotController::class, 'store'])->name('room.slot.store');
    Route::delete('/therapyroom-slot//destroy/{id}', [MstTherapyRoomSlotController::class, 'destroy'])->name('room.slot.destroy');
    Route::patch('therapyroom-slot//change-status/{id}', [MstTherapyRoomSlotController::class, 'changeStatus'])->name('room.slot.changeStatus');

    //Manage-Patients:
    Route::get('/patients/index', [MstPatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [MstPatientController::class, 'create'])->name('patients.create');
    Route::post('/patients/store', [MstPatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/edit/{id}', [MstPatientController::class, 'edit'])->name('patients.edit');
    Route::get('/patients/show/{id}', [MstPatientController::class, 'show'])->name('patients.show');
    Route::delete('/patients/destroy/{id}', [MstPatientController::class, 'destroy'])->name('patients.destroy');
    Route::put('/patients/update/{id}', [MstPatientController::class, 'update'])->name('patients.update');
    Route::patch('patients/change-status/{id}', [MstPatientController::class, 'changeStatus'])->name('patients.changeStatus');
    Route::patch('/patients/toggle-otp-verification/{id}', [MstPatientController::class, 'toggleOTPVerification'])->name('patients.toggleOTPVerification');
    Route::patch('/patients/toggle-approval/{id}', [MstPatientController::class, 'toggleApproval'])->name('patients.toggleApproval');
    Route::get('/patients/membership-assigning/{id}', [MstPatientController::class, 'patientMembershipAssigning'])->name('patients.membership.assigning');
    
        //Pharmacy
    Route::get('/pharmacy/index', [PharmacyController::class, 'index'])->name('pharmacy.index');
    Route::get('/Pharmacy/create', [PharmacyController::class, 'create'])->name('pharmacy.create');
    Route::post('/Pharmacy/store', [PharmacyController::class, 'store'])->name('pharmacy.store');
    Route::get('/Pharmacy/edit/{id}', [PharmacyController::class, 'edit'])->name('pharmacy.edit');
    Route::get('/Pharmacy/show/{id}', [MstPatientController::class, 'show'])->name('pharmacy.show');
    Route::delete('/Pharmacy/destroy/{id}', [PharmacyController::class, 'destroy'])->name('pharmacy.destroy');
    Route::put('/Pharmacy/update/{id}', [PharmacyController::class, 'update'])->name('pharmacy.update');

    //Manage-Therapy-Rooms:
    Route::get('/therapyrooms/index', [MstTherapyRoomController::class, 'index'])->name('therapyrooms.index');
    Route::get('/therapyrooms/create', [MstTherapyRoomController::class, 'create'])->name('therapyrooms.create');
    Route::post('/therapyrooms/store', [MstTherapyRoomController::class, 'store'])->name('therapyrooms.store');
    Route::get('/therapyrooms/edit/{id}', [MstTherapyRoomController::class, 'edit'])->name('therapyrooms.edit');
    Route::delete('/therapyrooms/destroy/{id}', [MstTherapyRoomController::class, 'destroy'])->name('therapyrooms.destroy');
    Route::put('/therapyrooms/update/{id}', [MstTherapyRoomController::class, 'update'])->name('therapyrooms.update');
    Route::patch('therapyrooms/change-status/{id}', [MstTherapyRoomController::class, 'changeStatus'])->name('therapyrooms.changeStatus');

    //Manage-Memberships:
    Route::get('/membership/index', [MstMembershipController::class, 'index'])->name('membership.index');
    Route::get('/membership/create', [MstMembershipController::class, 'create'])->name('membership.create');
    Route::post('/membership/store', [MstMembershipController::class, 'store'])->name('membership.store');
    Route::get('/membership/edit/{id}/{active_tab}', [MstMembershipController::class, 'edit'])->name('membership.edit');
    Route::post('/membership/update/{id}', [MstMembershipController::class, 'update'])->name('membership.update');
    Route::delete('/membership/destroy/{id}', [MstMembershipController::class, 'destroyMembershipPackage'])->name('membership.destroy');
    Route::patch('membership/change-status/{id}', [MstMembershipController::class, 'changeStatus'])->name('membership.changeStatus');
    Route::delete('/membership/destroy-wellness/{id}', [MstMembershipController::class, 'deleteWellness'])->name('membership.destroy.wellness');
    Route::delete('/membership/destroy-benefit/{id}', [MstMembershipController::class, 'deleteBenefit'])->name('membership.destroy.benefit');
    Route::get('/membership/view/{id}', [MstMembershipController::class, 'viewMembership'])->name('membership.view');

    //Manage-Wellness:
    Route::get('/wellness/index', [MstWellnessController::class, 'index'])->name('wellness.index');
    Route::get('/wellness/create', [MstWellnessController::class, 'create'])->name('wellness.create');
    Route::post('/wellness/store', [MstWellnessController::class, 'store'])->name('wellness.store');
    Route::get('/wellness/edit/{wellness_id}', [MstWellnessController::class, 'edit'])->name('wellness.edit');
    Route::put('/wellness/update/{wellness_id}', [MstWellnessController::class, 'update'])->name('wellness.update');
    Route::get('/wellness/show/{wellness_id}', [MstWellnessController::class, 'show'])->name('wellness.show');
    Route::delete('/wellness/destroy/{wellness_id}', [MstWellnessController::class, 'destroy'])->name('wellness.destroy');
    Route::patch('wellness/change-status/{wellness_id}', [MstWellnessController::class, 'changeStatus'])->name('wellness.changeStatus');
    Route::get('/wellness/room/assign', [MstWellnessController::class, 'roomAssign'])->name('wellness.room.assign');
    Route::delete('/wellness/room/destroy/{wellness_id}', [MstWellnessController::class, 'roomDestroy'])->name('wellness.room.destroy');
    Route::get('/get-branch-wellness-room-ids/{id}', [MstWellnessController::class, 'getBranchWellnessRoomIds'])->name('get.branch.room.wellness');
    Route::post('/wellness/room/store', [MstWellnessController::class, 'roomStore'])->name('wellness.room.store');
    Route::get('/get-wellness-list/{pharmacyId}', [MstWellnessController::class, 'getWellnessList'])->name('getWellnessList');

    //Manage-Units:
    Route::get('/unit/index', [MstUnitController::class, 'index'])->name('unit.index');
    Route::get('/unit/create', [MstUnitController::class, 'create'])->name('unit.create');
    Route::post('/unit/store', [MstUnitController::class, 'store'])->name('unit.store');
    Route::get('/unit/edit/{id}', [MstUnitController::class, 'edit'])->name('unit.edit');
    Route::put('/unit/update/{id}', [MstUnitController::class, 'update'])->name('unit.update');
    Route::delete('/unit/destroy/{id}', [MstUnitController::class, 'destroy'])->name('unit.destroy');
    Route::patch('unit/change-status/{id}', [MstUnitController::class, 'changeStatus'])->name('unit.changeStatus');

    //Manage-Taxes:
    Route::get('/tax/index', [MstTaxController::class, 'index'])->name('tax.index');
    Route::get('/tax/create', [MstTaxController::class, 'create'])->name('tax.create');
    Route::post('/tax/store', [MstTaxController::class, 'store'])->name('tax.store');
    Route::get('/tax/edit/{id}', [MstTaxController::class, 'edit'])->name('tax.edit');
    Route::put('/tax/update/{id}', [MstTaxController::class, 'update'])->name('tax.update');
    Route::delete('/tax/destroy/{id}', [MstTaxController::class, 'destroy'])->name('tax.destroy');
    Route::patch('tax/change-status/{id}', [MstTaxController::class, 'changeStatus'])->name('tax.changeStatus');

    //Manage-Medicines:
    Route::get('/medicine/index', [MstMedicineController::class, 'index'])->name('medicine.index');
    Route::get('/medicine/create', [MstMedicineController::class, 'create'])->name('medicine.create');
    Route::post('/medicine/store', [MstMedicineController::class, 'store'])->name('medicine.store');
    Route::get('/medicine/edit/{id}', [MstMedicineController::class, 'edit'])->name('medicine.edit');
    Route::put('/medicine/update/{id}', [MstMedicineController::class, 'update'])->name('medicine.update');
    Route::get('/medicine/show/{id}', [MstMedicineController::class, 'show'])->name('medicine.show');
    Route::delete('/medicine/destroy/{id}', [MstMedicineController::class, 'destroy'])->name('medicine.destroy');
    Route::patch('/medicine-update-status/{medicineId}', [MstMedicineController::class,'updateStatus'])->name('medicine.update-status');
    Route::post('/validate-hsn-code', [MstMedicineController::class, 'validateHsnCode'])->name('validate.hsn_code');
    // Route::get('/medicine-stock-updations/{id}',[MstMedicineController ::class,'viewStockUpdation'])->name('viewMedicineStockUpdation.view');
    Route::get('/medicine-stock-updations',[MstMedicineController ::class,'viewStockUpdation'])->name('viewMedicineStockUpdation.view');
    Route::post('/getBatchNumbers', [MstMedicineController::class, 'getBatchNumbers'])->name('getBatchNumbers'); 
    Route::get('/initialstock/getUnitPrice/{medicineId}', [MstMedicineController::class, 'getUnitPrice'])->name('getUnitPrice'); 
    Route::get('/get-current-stock/{medicineId}/{batchNo}', [MstMedicineController::class, 'getCurrentStock']);
    Route::put('/updatestockmedicine', [MstMedicineController::class, 'updateStockMedicine'])->name('updatestockmedicine'); 

    //Consultation-Billing:
    Route::get('/consultation-billing/index', [TrnConsultationBillingController::class, 'index'])->name('consultation_billing.index');
    Route::get('/consultation-billing/create', [TrnConsultationBillingController::class, 'create'])->name('consultation_billing.create');
    Route::post('/consultation-billing/store', [TrnConsultationBillingController::class, 'store'])->name('consultation_billing.store');
    Route::get('/consultation-billing/edit/{id}', [TrnConsultationBillingController::class, 'edit'])->name('consultation_billing.edit');
    Route::put('/consultation-billing/update/{id}', [TrnConsultationBillingController::class, 'update'])->name('consultation_billing.update');
    Route::delete('/consultation-billing/destroy/{id}', [TrnConsultationBillingController::class, 'destroy'])->name('consultation_billing.destroy');
    Route::patch('consultation-billing/{id}/change-status', [TrnConsultationBillingController::class, 'changeStatus'])->name('consultation_billing.changeStatus');

    //Booking-Type:(consultation)
    Route::get('/booking-type/index', [BookingTypeController::class, 'index'])->name('booking_type.index');
    Route::get('/booking-type/show/{id}', [BookingTypeController::class, 'show'])->name('booking_type.show');

    //Booking-Type:(wellness)
    Route::get('/booking-type/wellnessIndex', [BookingTypeController::class, 'wellnessIndex'])->name('booking_type.wellnessIndex');
    Route::get('/booking-type/wellnessShow/{id}', [BookingTypeController::class, 'wellnessShow'])->name('booking_type.wellnessShow');

    //Booking-Type:(therapy)
    Route::get('/booking-type/therapyIndex', [BookingTypeController::class, 'therapyIndex'])->name('booking_type.therapyIndex');
    Route::get('/booking-type/therapyShow/{id}', [BookingTypeController::class, 'therapyShow'])->name('booking_type.therapyShow');

   //patient-search:
    Route::get('/patient-search/index', [PatientSearchController::class, 'index'])->name('patient_search.index');
    Route::get('/patient-search/show/{id}', [PatientSearchController::class, 'show'])->name('patient_search.show');
    Route::post('/add-prescription/store', [PatientSearchController::class, 'storePrescription'])->name('storePrescription.store');

      //booking-search:
     Route::get('/booking-search/index', [BookingSearchController::class, 'index'])->name('booking_search.index');
    Route::get('/booking-search/show/{id}', [BookingSearchController::class, 'show'])->name('booking_search.show');
    
    //Manage-suppliers:
    Route::get('/supplier/index', [MstSupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [MstSupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier/store', [MstSupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/edit/{id}', [MstSupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/update/{id}', [MstSupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/destroy/{id}', [MstSupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::patch('supplier/change-status/{id}', [MstSupplierController::class, 'changeStatus'])->name('supplier.changeStatus');
    Route::get('/supplier/show/{id}', [MstSupplierController::class, 'show'])->name('supplier.show');
    Route::get('/get-states/{countryId}', [MstSupplierController::class, 'getStates']);
        Route::patch('/supplier-update-status/{medicineId}', [MstSupplierController::class,'updateStatus'])->name('supplier.update-status');

    //Manage-Specialization:
    Route::get('/specialization/index/{id}', [MstStaffSpecializationController::class, 'index'])->name('specialization.index');
    Route::get('/specialization/create', [MstStaffSpecializationController::class, 'create'])->name('specialization.create');
    Route::post('/specialization/store', [MstStaffSpecializationController::class, 'store'])->name('specialization.store');
    Route::get('/specialization/edit/{id}', [MstStaffSpecializationController::class, 'edit'])->name('specialization.edit');
    Route::put('/specialization/update/{id}', [MstStaffSpecializationController::class, 'update'])->name('specialization.update');
    Route::delete('/specialization/destroy/{id}', [MstStaffSpecializationController::class, 'destroy'])->name('specialization.destroy');
    Route::patch('specialization/{id}/change-status', [MstStaffSpecializationController::class, 'changeStatus'])->name('specialization.changeStatus');

    //therapy-room-assigning:
    Route::get('/therapyroom-assigning/index/{id}', [MstTherapyRoomAssigningController::class, 'index'])->name('therapyroomassigning.index');
    Route::get('/therapyroom-assigning/create', [MstTherapyRoomAssigningController::class, 'create'])->name('therapyroomassigning.create');
    Route::post('/therapyroom-assigning/store', [MstTherapyRoomAssigningController::class, 'store'])->name('therapyroomassigning.store');
    Route::get('/therapyroom-assigning/edit/{id}', [MstTherapyRoomAssigningController::class, 'edit'])->name('therapyroomassigning.edit');
    Route::put('/therapyroom-assigning/update/{id}', [MstTherapyRoomAssigningController::class, 'update'])->name('therapyroomassigning.update');
    Route::delete('/therapyroom-assigning/destroy/{id}', [MstTherapyRoomAssigningController::class, 'destroy'])->name('therapyroomassigning.destroy');
    Route::patch('therapyroom-assigning/change-status/{id}', [MstTherapyRoomAssigningController::class, 'changeStatus'])->name('therapyroomassigning.changeStatus');
    Route::get('/get-therapy-rooms/{branchId}', [MstTherapyRoomAssigningController::class, 'getTherapyRooms']);
    
    Route::get('/therapyroom-therapymapping', [MstTherapyRoomAssigningController::class, 'roomMappingIndex'])->name('therapymapping.index');
    Route::post('/therapymap-room/store', [MstTherapyRoomAssigningController::class, 'roomMappingStore'])->name('therapy-map.room.store');
    Route::get('/check-therapyroom-availability', [MstTherapyRoomAssigningController::class, 'therapyRoomAvailability'])->name('therapyRoomAvailability');
    Route::delete('/therapy/room/destroy/{therapy_id}', [MstTherapyRoomAssigningController::class, 'roomDestroy'])->name('therapy.room.destroy');

    



    //Manage-Mastervalues:
    Route::get('/masters', [MstMasterValueController::class, 'index'])->name('mastervalues.index');
    Route::get('/masters/create', [MstMasterValueController::class, 'create'])->name('mastervalues.create');
    Route::post('/masters/store', [MstMasterValueController::class, 'store'])->name('mastervalues.store');
    Route::get('/masters/edit/{id}', [MstMasterValueController::class, 'edit'])->name('mastervalues.edit');
    Route::get('/masters/show/{id}', [MstMasterValueController::class, 'show'])->name('mastervalues.show');
    Route::put('/masters/update/{id}', [MstMasterValueController::class, 'update'])->name('mastervalues.update');
    Route::delete('/masters/destroy/{id}', [MstMasterValueController::class, 'destroy'])->name('mastervalues.destroy');
    Route::patch('masters/{id}/change-status', [MstMasterValueController::class, 'changeStatus'])->name('mastervalues.changeStatus');

    //timeslot-storing in mst_master_values table:
    Route::post('/mastervalues/store', [MstTimeSlotController::class, 'store'])->name('mastervalue.store');
    //adding timeslot for a particular staff:
    Route::get('/timeslot-staff/slot/{id}', [MstTimeSlotController::class, 'slotIndex'])->name('staff.slot');
    Route::post('/timeslot-staff/store', [MstTimeSlotController::class, 'slotStore'])->name('timeslotStaff.store');




    // Qualification - Screen for qualification
    Route::get('/qualifications', [MstQualificationController::class, 'index'])->name('qualifications.index');
    Route::get('/qualifications/create', [MstQualificationController::class, 'create'])->name('qualifications.create');
    Route::post('/qualifications/store', [MstQualificationController::class, 'store'])->name('qualifications.store');
    Route::delete('/qualifications/destroy/{id}', [MstQualificationController::class, 'destroy'])->name('qualifications.destroy');
    Route::get('/qualifications/edit/{id}', [MstQualificationController::class, 'edit'])->name('qualifications.edit');
    Route::patch('qualifications/change-status/{id}', [MstQualificationController::class, 'changeStatus'])->name('qualifications.changeStatus');



    // Leave type - Screen for leave types
    Route::get('/leave-type', [MstLeaveTypeController::class, 'index'])->name('leave.type.index');
    Route::get('/leave-type/create', [MstLeaveTypeController::class, 'create'])->name('leave.type.create');
    Route::post('/leave-type/store', [MstLeaveTypeController::class, 'store'])->name('leave.type.store');
    Route::delete('/leave-type/destroy/{id}', [MstLeaveTypeController::class, 'destroy'])->name('leave.type.destroy');
    Route::get('/leave-type/edit/{id}', [MstLeaveTypeController::class, 'edit'])->name('leave.type.edit');
    Route::patch('leave-type/change-status/{id}', [MstLeaveTypeController::class, 'changeStatus'])->name('leave.type.changeStatus');
    Route::patch('leave-type/change-deductible/{id}', [MstLeaveTypeController::class, 'changeDeductible'])->name('leave.type.changeDeductible');


    // Manufacturer- Screen for manufacturer
    Route::get('/manufacturer', [MstManufacturerController::class, 'index'])->name('manufacturer.index');
    Route::get('/manufacturer/create', [MstManufacturerController::class, 'create'])->name('manufacturer.create');
    Route::post('/manufacturer/store', [MstManufacturerController::class, 'store'])->name('manufacturer.store');
    Route::delete('/manufacturer/destroy/{id}', [MstManufacturerController::class, 'destroy'])->name('manufacturer.destroy');
    Route::get('/manufacturer/edit/{id}', [MstManufacturerController::class, 'edit'])->name('manufacturer.edit');
    Route::patch('manufacturer/change-status/{id}', [MstManufacturerController::class, 'changeStatus'])->name('manufacturer.changeStatus');

    //Manage-Tax-Groups:
    Route::get('/tax-group/index', [MstTaxGroupController::class, 'index'])->name('tax.group.index');
    Route::get('/tax-group/create', [MstTaxGroupController::class, 'create'])->name('tax.group.create');
    Route::post('/tax-group/store', [MstTaxGroupController::class, 'store'])->name('tax.group.store');
    Route::get('/tax-group/edit/{id}', [MstTaxGroupController::class, 'edit'])->name('tax.group.edit');
    Route::put('/tax-group/update/{id}', [MstTaxGroupController::class, 'update'])->name('tax.group.update');
    Route::delete('/tax-group/destroy/{id}', [MstTaxGroupController::class, 'destroy'])->name('tax.group.destroy');
    Route::patch('tax-group/change-status/{id}', [MstTaxGroupController::class, 'changeStatus'])->name('tax-group.changeStatus');

    //Manage-Account-Subhead
    Route::get('/account-sub-group/index', [AccountSubGroupController::class, 'index'])->name('account.sub.group.index');
    Route::get('/account-sub-group/create', [AccountSubGroupController::class, 'create'])->name('account.sub.group.create');
    Route::post('/account-sub-group/store', [AccountSubGroupController::class, 'store'])->name('account.sub.group.store');
    Route::get('/account-sub-group/edit/{id}', [AccountSubGroupController::class, 'edit'])->name('account.sub.group.edit');
    Route::delete('/account-sub-group/destroy/{id}', [AccountSubGroupController::class, 'destroy'])->name('account.sub.group.destroy');
    Route::put('/account-sub-group/update/{id}', [AccountSubGroupController::class, 'update'])->name('account.sub.group.update');
    Route::patch('account-sub-group/change-status/{id}', [AccountSubGroupController::class, 'changeStatus'])->name('account.sub.group.changeStatus');

    //Manage-Account-Ledger
    Route::get('/account-ledger/index', [AccountLedgerController::class, 'index'])->name('account.ledger.index');
    Route::get('/account-ledger/create', [AccountLedgerController::class, 'create'])->name('account.ledger.create');
    Route::post('/account-ledger/store', [AccountLedgerController::class, 'store'])->name('account.ledger.store');
    Route::get('/account-ledger/edit/{id}', [AccountLedgerController::class, 'edit'])->name('account.ledger.edit');
    Route::delete('/account-ledger/destroy/{id}', [AccountLedgerController::class, 'destroy'])->name('account.ledger.destroy');
    Route::put('/account-ledger/update/{id}', [AccountLedgerController::class, 'update'])->name('account.ledger.update');
    Route::patch('account-ledger/changeStatus/{id}', [AccountLedgerController::class, 'changeStatus'])->name('account.ledger.changeStatus');
    Route::patch('/get-account-sub-groups/{id}', [AccountLedgerController::class, 'getAccountSubGroups'])->name('get.account.sub.groups');


    // staff-branch-transer:
    Route::get('/staff-branch-transfer', [EmployeeBranchTransferController::class, 'index'])->name('branchTransfer.index');
    Route::post('/staff-branch-transfer/store', [EmployeeBranchTransferController::class, 'store'])->name('branchTransfer.store');
    Route::get('/get-employees/{branchId}', [EmployeeBranchTransferController::class, 'getEmployees'])->name('get.employees');
    // staff- leave
    Route::get('/staff-leave', [StaffLeaveController::class, 'index'])->name('staffleave.index');
    Route::get('/staffleave/create', [StaffLeaveController::class, 'create'])->name('staffleave.create');
     Route::get('get-staff-names', [StaffLeaveController::class, 'getStaffNames'])->name('get-staff-names');

    Route::post('/staffleave/store', [StaffLeaveController::class, 'store'])->name('staffleave.store');
    Route::get('/staffleave/show/{staffleave_id}', [StaffLeaveController::class, 'show'])->name('staffleave.show');
    Route::get('/staffleave/edit/{id}', [StaffLeaveController::class, 'edit'])->name('staffleave.edit');
    Route::put('/staffleave/update/{id}', [StaffLeaveController::class, 'update'])->name('staffleave.update');
    Route::delete('/staffleave/destroy/{id}', [StaffLeaveController::class, 'destroy'])->name('staffleave.destroy');
    Route::get('/get-total-leaves/{staffId}', [StaffLeaveController::class, 'getTotalLeaves'])->name('get-total-leaves');
    Route::post('/bookingCount', [StaffLeaveController::class, 'bookingCount'])->name('bookingCount');
    
        //Holidays
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::get('/holiday/create', [HolidayController::class, 'create'])->name('holidays.create');
    // Route::get('/get-staff-names/{branchId}', [HolidayController::class, 'getStaffNames'])->name('get-staff-names');
    Route::post('/holiday/store', [HolidayController::class, 'store'])->name('holidays.store');
    Route::get('/holiday/edit/{id}', [HolidayController::class, 'edit'])->name('holidays.edit');
    Route::put('/holiday/update/{id}', [HolidayController::class, 'update'])->name('holidays.update');
    Route::delete('/holiday/destroy/{id}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

    Route::get('/holiday/staff-mapping/{holiday_id}', [HolidayController::class, 'staffHolidayMapping'])->name('holidays.staff-mapping');

    Route::post('/holiday/storelink/{holidaymapping_id}', [HolidayController::class, 'storeHolidayMapping'])->name('holidays.storelink');
    Route::delete('/holidaymapping/destroy/{id}', [HolidayController::class, 'destroyMapping'])->name('holidaysmapping.destroy');
    
       //salary
   Route::get('/salary/index', [SalaryHeadController::class, 'index'])->name('salarys.index');
   Route::get('/salary/create', [SalaryHeadController::class, 'create'])->name('salarys.create');
   Route::post('/salary/store', [SalaryHeadController::class, 'store'])->name('salarys.store');
   Route::get('/salary/show/{master_id}', [SalaryHeadController::class, 'show'])->name('salarys.show');
   Route::get('/salary/edit/{id}', [SalaryHeadController::class, 'edit'])->name('salarys.edit');
   Route::put('/salary/update/{id}', [SalaryHeadController::class, 'update'])->name('salarys.update');
   Route::delete('/salary/destroy/{id}', [SalaryHeadController::class, 'destroy'])->name('salarys.destroy');
   
   //salary-package
   Route::get('/package/index', [SalaryPackageController::class, 'index'])->name('packages.index');
   Route::get('/package/create', [SalaryPackageController::class, 'create'])->name('packages.create');
   Route::post('/package/store', [SalaryPackageController::class, 'store'])->name('packages.store');
   Route::get('/package/show/{master_id}', [SalaryPackageController::class, 'show'])->name('packages.show');
   Route::get('/package/edit/{id}', [SalaryPackageController::class, 'edit'])->name('packages.edit');
   Route::put('/package/update/{id}', [SalaryPackageController::class, 'update'])->name('packages.update');
   Route::delete('/package/destroy/{id}', [SalaryPackageController::class, 'destroy'])->name('packages.destroy');
   Route::get('/getSalaryHeadType/{id}', [SalaryPackageController::class, 'getSalaryHeadType']);
   Route::get('/getSalaryHeadTypes/{id}', [SalaryPackageController::class, 'getSalaryHeadTypes']);

   //availableleaves
   Route::get('/availableleave/index', [AvailableLeaveController::class, 'index'])->name('availableleaves.index');
   Route::get('/availableleave/create', [AvailableLeaveController::class, 'create'])->name('availableleaves.create');
   Route::post('/availableleave/store', [AvailableLeaveController::class, 'store'])->name('availableleaves.store');
   Route::get('/availableleave/show/{staff_id}', [AvailableLeaveController::class, 'show'])->name('availableleaves.show');
   Route::get('/availableleave/edit/{id}', [AvailableLeaveController::class, 'edit'])->name('availableleaves.edit');
   Route::put('/availableleave/update/{id}', [AvailableLeaveController::class, 'update'])->name('availableleaves.update');
    
        //Attendance
    Route::get('/attendance', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');
    Route::get('/attendance/monthly', [AttendanceController::class, 'monthlyAttendance'])->name('attendance.monthly');


    // Medicine Purchase
    Route::get('/medicine-purchase/index ', [MedicinePurchaseController::class, 'index'])->name('medicine.purchase.index');
    Route::get('/medicine-purchase/create', [MedicinePurchaseController::class, 'create'])->name('medicine.purchase.create');

    // patient-membership 
    Route::get('/patients/membership/{id}', [MstPatientController::class, 'addMembershipIndex'])->name('patients.membership');
    Route::post('/patients/membership/store/{id}', [MstPatientController::class, 'patientMembershipStore'])->name('patientsMembership.store');
    Route::get('/get-wellness-details/{membershipId}', [MstPatientController::class, 'getWellnessDetails'])->name('getwellness.details');
    // });

    //Manage-Users:
    Route::get('/user/index', [MstUserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [MstUserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [MstUserController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [MstUserController::class, 'edit'])->name('user.edit');
    Route::put('/user/update/{id}', [MstUserController::class, 'update'])->name('user.update');
    Route::get('/user/show/{id}', [MstUserController::class, 'show'])->name('user.show');
    Route::delete('/user/destroy/{id}', [MstUserController::class, 'destroy'])->name('user.destroy');
    Route::patch('user/change-status/{id}', [MstUserController::class, 'changeStatus'])->name('user.changeStatus');


    // Medicine sales invoice 
    Route::get('/medicine-sales-invoices', [MedicineSalesController::class, 'index'])->name('medicine.sales.invoices.index');
    Route::get('/medicine-sales-invoices/create', [MedicineSalesController::class, 'create'])->name('medicine.sales.invoices.create');
    Route::post('/medicine-sales-invoices/store', [MedicineSalesController::class, 'store'])->name('medicine.sales.invoices.store');
    Route::delete('/medicine-sales-invoices/destroy/{id}', [MedicineSalesController::class, 'destroy'])->name('medicine.sales.invoices.destroy');
    Route::get('/medicine-sales-invoices/edit/{id}', [MedicineSalesController::class, 'edit'])->name('medicine.sales.invoices.edit');
    Route::get('/medicine-sales-invoices/view/{id}', [MedicineSalesController::class, 'show'])->name('medicine.sales.invoices.show');
    Route::get('/medicine-sales-invoices/print/{id}', [MedicineSalesController::class, 'generatePDF'])->name('medicine.sales.invoices.print');
    Route::post('/medicine-sales-invoices/update', [MedicineSalesController::class, 'update'])->name('medicine.sales.invoices.update');
    Route::patch('/get-patient-booking-ids/{id}', [MedicineSalesController::class, 'getPatientBookingIds'])->name('get.patient.booking.ids');
    Route::patch('/get-medicine-batches/{id}', [MedicineSalesController::class, 'getMedicineBatches'])->name('get.medicine.batches');
    Route::get('/getLedgerNames1', [MedicineSalesController::class, 'getLedgerNames'])->name('getLedgerNames1');
    Route::get('/check-has-credit', [MedicineSalesController::class, 'hasCreditPatent'])->name('hasCreditPatient');
    Route::post('/medicine-sales-invoices/patient-store', [MedicineSalesController::class, 'patientStore'])->name('medicine.sales.invoices.patient-store');

    // Medicine sales return 
     Route::get('/get-sale-invoice-details', [MedicineSalesReturnController::class, 'getSaleInvoiceDetails'])->name('getSaleInvoiceDetails');
    Route::get('/medicine-sales-return', [MedicineSalesReturnController::class, 'index'])->name('medicine.sales.return.index');
    Route::get('/medicine-sales-return/create', [MedicineSalesReturnController::class, 'create'])->name('medicine.sales.return.create');
    Route::post('/medicine-sales-return/store', [MedicineSalesReturnController::class, 'store'])->name('medicine.sales.return.store');
    Route::delete('/medicine-sales-return/destroy/{id}', [MedicineSalesReturnController::class, 'destroy'])->name('medicine.sales.return.destroy');
    Route::get('/medicine-sales-return/edit/{id}', [MedicineSalesReturnController::class, 'edit'])->name('medicine.sales.return.edit');
    Route::patch('/get-patient-invoice-ids/{id}', [MedicineSalesReturnController::class, 'getPatientInvoiceIds'])->name('get.patient.invoice.ids');
    Route::post('/medicine-sales-return/update', [MedicineSalesReturnController::class, 'update'])->name('medicine.sales.return.update');
    Route::get('/medicine-sales-return/show/{id}', [MedicineSalesReturnController::class, 'show'])->name('medicine.sales.return.show');
    Route::get('/medicine-sales-return/print/{id}', [MedicineSalesReturnController::class, 'generatePDF'])->name('medicine.sales.return.print');

    // Prescription Printing 
    Route::get('/prescriptions', [TrnPrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::post('/prescriptions-list', [TrnPrescriptionController::class, 'list'])->name('prescriptions.list');
    Route::get('/prescriptions-show/{id}', [TrnPrescriptionController::class, 'show'])->name('prescriptions.show');
    Route::get('/prescriptions-print/{id}', [TrnPrescriptionController::class, 'print'])->name('prescriptions.print');
    Route::get('/get-booking-ids/{id}', [TrnPrescriptionController::class, 'getPatientBookingIds'])->name('get.booking.ids');
     Route::Post('/get-patient-ids', [TrnPrescriptionController::class, 'getPatientIds'])->name('get.patient.ids');

    // Journel Entry
    Route::get('/journel-entry', [TrnJournelEntryController::class, 'index'])->name('journel.entry.index');
    Route::get('/journel-entry-create', [TrnJournelEntryController::class, 'create'])->name('journel.entry.create');
    Route::post('/journel-entry-store', [TrnJournelEntryController::class, 'store'])->name('journel.entry.store');
    Route::get('/journel-entry-show/{id}', [TrnJournelEntryController::class, 'show'])->name('journel.entry.show');
    Route::delete('/journel-entry/destroy/{id}', [TrnJournelEntryController::class, 'destroy'])->name('journel.entry.destroy');
    Route::get('/journel-entry-edit/{id}', [TrnJournelEntryController::class, 'edit'])->name('journel.entry.edit');
    Route::post('/journel-entry-update', [TrnJournelEntryController::class, 'update'])->name('journel.entry.update');

    //Medicine-Purchase-Invoice:
     Route::get('/get-medicine-details/{productId}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'getMedicineDetails'])->name('getMedicineDetails');
    Route::get('/get-credit-info/{supplierId}',[TrnMedicinePurchaseInvoiceDetailsController ::class,'getCreditInfo'])->name('medicinePurchaseInvoice.getcreditinfo'); 
    Route::get('/medicine-purchase-invoice/index', [TrnMedicinePurchaseInvoiceDetailsController::class, 'index'])->name('medicinePurchaseInvoice.index');
    Route::get('/medicine-purchase-invoice/create', [TrnMedicinePurchaseInvoiceDetailsController::class, 'create'])->name('medicinePurchaseInvoice.create');
    Route::post('/medicine-purchase-invoice/store', [TrnMedicinePurchaseInvoiceDetailsController::class, 'store'])->name('medicinePurchaseInvoice.store');
    Route::get('/medicine-purchase-invoice/edit/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'edit'])->name('medicinePurchaseInvoice.edit');
    Route::put('/medicine-purchase-invoice/update/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'update'])->name('medicinePurchaseInvoice.update');
    Route::get('/medicine-purchase-invoice/view/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'view'])->name('medicinePurchaseInvoice.view');
    Route::delete('/medicine-purchase-invoice/destroy/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'destroy'])->name('medicinePurchaseInvoice.destroy');
    Route::get('/medicine-purchase-invoice/check-invoice',[TrnMedicinePurchaseInvoiceDetailsController ::class,'checkInvoice'])->name('purchase.checkInvoice'); 
 
    Route::match(['get', 'post'], '/import/excel', [TrnMedicinePurchaseInvoiceDetailsController::class, 'create'])->name('excel.import');

    Route::get('/get-product-id/{medicineCode}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'getProductId'])->name('getProductId');
    Route::get('/get-unit-id/{medicineCode}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'getUnitId'])->name('getUnitId');
    Route::get('/getLedgerNames', [TrnMedicinePurchaseInvoiceDetailsController::class, 'getLedgerNames'])->name('getLedgerNames');
    Route::get('/get-credit-details/{supplierId}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'getCreditDetails'])->name('getCreditDetails');



    //Medicine-Purchase-Return:
    Route::get('/medicine-purchase-return/index', [TrnMedicinePurchaseReturnController::class, 'index'])->name('medicinePurchaseReturn.index');
    Route::match(['get', 'post'], '/medicine-purchase-return/create', [TrnMedicinePurchaseReturnController::class, 'create'])->name('medicinePurchaseReturn.create');
    Route::get('/medicine-purchase-return/edit/{id}', [TrnMedicinePurchaseReturnController::class, 'edit'])->name('medicinePurchaseReturn.edit');
    Route::post('/medicine-purchase-return/store', [TrnMedicinePurchaseReturnController::class, 'store'])->name('medicinePurchaseReturn.store');
    Route::put('/medicine-purchase-return/update/{id}', [TrnMedicinePurchaseReturnController::class, 'update'])->name('medicinePurchaseReturn.update');
    Route::get('/medicine-purchase-return/show/{id}', [TrnMedicinePurchaseReturnController::class, 'show'])->name('medicinePurchaseReturn.show');
    Route::get('/get-purchase-invoices', [TrnMedicinePurchaseReturnController::class, 'getPurchaseInvoices'])->name('getPurchaseInvoices');
    Route::get('/getPurchaseInvoiceDetails', [TrnMedicinePurchaseReturnController::class, 'getPurchaseInvoiceDetails'])->name('getPurchaseInvoiceDetails');
    Route::get('/get-invoice-branch',[TrnMedicinePurchaseReturnController::class,'getInvoiceBranch'])->name('getInvoiceBranch');
Route::delete('/medicine-purchase-return/destroy/{id}', [TrnMedicinePurchaseReturnController::class, 'destroy'])->name('medicinePurchaseReturn.destroy');
Route::get('/getLedgerNames2', [TrnMedicinePurchaseReturnController::class, 'getLedgerNames'])->name('getLedgerNames2');



    //Medicine Stock Updation:
    Route::get('/medicine-stock-updation/index', [TrnMedicineStockUpdationController::class, 'index'])->name('medicineStockUpdation.index');
    Route::get('/get-generic-name/{id}', [TrnMedicineStockUpdationController::class, 'getGenericName'])->name('getGenericName');
    Route::get('/get-unit-ids/{id}', [TrnMedicineStockUpdationController::class, 'getUnitId'])->name('getUnitId');
    Route::get('/get-batch-numbers/{id}', [TrnMedicineStockUpdationController::class, 'getBatchNumbers'])->name('getBatchNumbers');
    Route::get('/get-current-stock/{medicineId}/{batchNo}', [TrnMedicineStockUpdationController::class, 'getCurrentStock'])->name('getCurrentStock');
    Route::put('/update-medicine-stocks', [TrnMedicineStockUpdationController::class, 'updateMedicineStocks'])->name('update.medicine.stocks');

    // Assigning timeslot for a particular therapy room
    Route::get('/therapyroom-slot-assigning/index/{id}', [MstTherapyRoomSlotController::class, 'index'])->name('slot_assigning.index');
    Route::post('/therapyroom-slot/store', [MstTherapyRoomSlotController::class, 'store'])->name('room.slot.store');
    Route::delete('/therapyroom-slot//destroy/{id}', [MstTherapyRoomSlotController::class, 'destroy'])->name('room.slot.destroy');
    Route::patch('therapyroom-slot//change-status/{id}', [MstTherapyRoomSlotController::class, 'changeStatus'])->name('room.slot.changeStatus');
    
        //Medicine-Purchase-Invoice:
Route::get('/medicine-purchase-invoice/index',[TrnMedicinePurchaseInvoiceDetailsController ::class,'index'])->name('medicinePurchaseInvoice.index');
Route::get('/medicine-purchase-invoice/create',[TrnMedicinePurchaseInvoiceDetailsController ::class,'create'])->name('medicinePurchaseInvoice.create');
Route::post('/medicine-purchase-invoice/store', [TrnMedicinePurchaseInvoiceDetailsController::class, 'store'])->name('medicinePurchaseInvoice.store');
Route::get('/medicine-purchase-invoice/edit/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'edit'])->name('medicinePurchaseInvoice.edit');
Route::put('/medicine-purchase-invoice/update/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'update'])->name('medicinePurchaseInvoice.update');
Route::get('/medicine-purchase-invoice/show/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'show'])->name('medicinePurchaseInvoice.show');
Route::delete('/medicine-purchase-invoice/destroy/{id}', [TrnMedicinePurchaseInvoiceDetailsController::class, 'destroy'])->name('medicinePurchaseInvoice.destroy');
Route::get('/medicine-purchase-invoice/products/sample', function () {
    
    $filepath = public_path('assets/uploads/medicine_purchase_invoice_sample.xlsx');
    return Response::download($filepath); 

})->name('download.products.sample');
Route::match(['get', 'post'], '/import/excel', [TrnMedicinePurchaseInvoiceDetailsController::class, 'create'])->name('excel.import');

Route::get('/get-product-id/{medicineCode}', [TrnMedicinePurchaseInvoiceDetailsController::class,'getProductId'])->name('getProductId');
Route::get('/get-unit-id/{medicineCode}', [TrnMedicinePurchaseInvoiceDetailsController::class,'getUnitId'])->name('getUnitId');
Route::get('/getLedgerNames', [TrnMedicinePurchaseInvoiceDetailsController::class,'getLedgerNames'])->name('getLedgerNames');
Route::get('/get-credit-details/{supplierId}', [TrnMedicinePurchaseInvoiceDetailsController::class,'getCreditDetails'])->name('getCreditDetails');



//Medicine-Purchase-Return:
Route::get('/medicine-purchase-return/index',[TrnMedicinePurchaseReturnController ::class,'index'])->name('medicinePurchaseReturn.index');
Route::match(['get', 'post'],'/medicine-purchase-return/create',[TrnMedicinePurchaseReturnController::class,'create'])->name('medicinePurchaseReturn.create');
Route::get('/medicine-purchase-return/edit/{id}', [TrnMedicinePurchaseReturnController::class, 'edit'])->name('medicinePurchaseReturn.edit');
Route::post('/medicine-purchase-return/store',[TrnMedicinePurchaseReturnController::class,'store'])->name('medicinePurchaseReturn.store');
Route::put('/medicine-purchase-return/update/{id}', [TrnMedicinePurchaseReturnController::class, 'update'])->name('medicinePurchaseReturn.update');
Route::get('/medicine-purchase-return/show/{id}', [TrnMedicinePurchaseReturnController::class, 'show'])->name('medicinePurchaseReturn.show');
Route::get('/get-purchase-invoices',[TrnMedicinePurchaseReturnController::class,'getPurchaseInvoices'])->name('getPurchaseInvoices');
Route::get('/getPurchaseInvoiceDetails', [TrnMedicinePurchaseReturnController::class, 'getPurchaseInvoiceDetails'])->name('getPurchaseInvoiceDetails');


//Medicine Stock Updation:
Route::get('/medicine-stock-updation/index',[TrnMedicineStockUpdationController ::class,'index'])->name('medicineStockUpdation.index');
Route::get('/get-generic-name/{id}', [TrnMedicineStockUpdationController::class, 'getGenericName'])->name('getGenericName');
Route::get('/get-batch-numbers/{id}', [TrnMedicineStockUpdationController::class, 'getBatchNumbers'])->name('getBatchNumbers');
Route::get('/get-current-stock/{medicineId}/{batchNo}', [TrnMedicineStockUpdationController::class, 'getCurrentStock'])->name('getCurrentStock');
Route::put('/update-medicine-stocks', [TrnMedicineStockUpdationController::class, 'updateMedicineStocks'])->name('update.medicine.stocks');

//therapy-stock-transfer

Route::get('/therapy-stock-transfer', [TherapyStockTransferController::class, 'index'])->name('therapy-stock-transfers.index');
Route::get('/therapy-stock-transfer/create', [TherapyStockTransferController::class, 'create'])->name('therapy-stock-transfers.create');
Route::post('/therapy-stock-transfer/store', [TherapyStockTransferController::class, 'store'])->name('therapy-stock-transfers.store');
Route::get('/get-medicine-batch/{id}', [TherapyStockTransferController::class, 'getMedicineBatch'])->name('getMedicineBatch');
Route::get('/get-current-medicine-stock/{medicineId}/{batchNo}', [TherapyStockTransferController::class, 'getCurrentMedicineStock'])->name('getCurrentMedicineStock');

//Income-Expense
Route::get('/income-expense/index', [IncomeExpenseController::class, 'index'])->name('income-expense.index');
Route::get('/income-expense/create', [IncomeExpenseController::class, 'create'])->name('income-expense.create');
Route::post('/income-expense/store', [IncomeExpenseController::class, 'store'])->name('income-expense.store');
Route::delete('/income-expense/{id}', [IncomeExpenseController::class, 'destroy'])->name('income-expense.destroy');

//General Feedback
Route::get('/patient/feedback/index', [FeedbackController::class, 'index'])->name('customer.feedback.index');
Route::get('feedback/change-status/{feedback_id}', [FeedbackController::class, 'changeStatus'])->name('feedback.changeStatus');
Route::delete('/feedback/destroy/{feedback_id}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

//search
Route::get('/consultation-search/index', [SearchController::class, 'consultationSearch'])->name('consultation-search.index');

//Settings
Route::get('/profile', [SettingsController::class, 'ProfileIndex'])->name('profile');
Route::get('/change-password', [SettingsController::class, 'ChangePassword'])->name('change.password');
Route::post('/update-password', [SettingsController::class, 'UpdatePassword'])->name('profile.update_password');
Route::get('/application-settings', [SettingsController::class, 'SettingsIndex'])->name('application.settings');
Route::post('/settings/update/{id}', [SettingsController::class, 'UpdateSettings'])->name('settings.update');

//Salary Processing

Route::get('/staff/salary-processing/index', [SalaryProcessingController::class, 'index'])->name('salary-processing.index');
Route::get('/staff/salary-processing/create', [SalaryProcessingController::class, 'create'])->name('create.salary-processing');
Route::post('/staff/salary-processing/store', [SalaryProcessingController::class, 'store'])->name('store.salary-processing');
Route::get('/getWorkingDays', [SalaryProcessingController::class, 'getWorkingDays'])->name('getWorkingDays');
Route::get('/getStaffs/{branch_id}', [SalaryProcessingController::class, 'getStaffs'])->name('getStaffs');
Route::get('/getStaffSalary/{staff_id}', [SalaryProcessingController::class, 'getStaffSalary'])->name('getStaffSalary');
Route::get('getStaffLeaves/{staff_id}/{month}', [SalaryProcessingController::class, 'getStaffLeaves'])->name('get.staff.leaves');
Route::get('/getDeductibleLeaveCount/{staff_id}/{month}', [SalaryProcessingController::class, 'getDeductibleLeaveCount'])->name('getDeductibleLeaveCount');
Route::get('/get-salary-heads/{staff_id}', [SalaryProcessingController::class, 'getSalaryHeads'])->name('get-salary-heads');


//Reports
Route::get('/sales-report', [ReportController::class, 'SalesReport'])->name('sales.report');
Route::get('/sales/report/detail/{id}', [ReportController::class, 'SalaryReportDetail'])->name('sales.report.detail');
Route::get('/purchase-report', [ReportController::class, 'PurchaseReport'])->name('purchase-report');
Route::get('/purchase/report/detail/{id}', [ReportController::class, 'PurchaseReportDetail'])->name('purchase.report.detail');
Route::get('/purchase-return-report', [ReportController::class, 'PurchaseReturnReport'])->name('purchase.return.report');
Route::get('/purchase/return/report/detail/{id}', [ReportController::class, 'PurchaseReturnReportDetail'])->name('purchase.return.report.detail');
Route::get('/sales-return-report', [ReportController::class, 'SalesReturnReport'])->name('sales.return.report');
Route::get('/sales/return/report/detail/{id}', [ReportController::class, 'SalesReturnReportDetail'])->name('sales.return.report.detail');
Route::get('/stock-transfer-report', [ReportController::class, 'StockTransferReport'])->name('stock.transfer.report');
Route::get('/stock-transfer-report/detail/{id}', [ReportController::class, 'StockTransferReportDetail'])->name('stock-transfer.report.detail');
Route::get('/current-stocks-report', [ReportController::class, 'CurrentStockReport'])->name('current.stock.report');
Route::get('/payment-made-report', [ReportController::class, 'PaymentMadeReport'])->name('payment.made.report');
Route::get('/payment-made-report/detail/{id}', [ReportController::class, 'PaymentMadeReportDetail'])->name('payment.made.report.detail');
Route::get('/payable-report', [ReportController::class, 'PayableReport'])->name('payable.report');
Route::get('/payable-report/detail/{id}', [ReportController::class, 'PayableReportDetail'])->name('payable.report.detail');
Route::get('/ledger-report', [ReportController::class, 'ledgerReport'])->name('ledger.report');
Route::get('/ledger-report/detail/{id}', [ReportController::class, 'ledgerReportDetails'])->name('ledger.report.detail');
Route::get('/trail-balance-report', [ReportController::class, 'TrailBalanceReport'])->name('trailbalance.report');
Route::get('/profit-loss-report', [ReportController::class, 'profitLossReport'])->name('profitloss.report');
Route::get('/payment-received-report', [ReportController::class, 'paymentReceivedReport'])->name('payment.received.report');

//stock transfer to branches
Route::get('/branch/stock-transfer', [BranchStockTransferController::class, 'index'])->name('branch-transfer.index');
Route::get('/branch/stock-transfer/create', [BranchStockTransferController::class, 'create'])->name('create.branch-stock-transfer');
Route::get('/getBatchDetails', [BranchStockTransferController::class, 'getBatchDetails'])->name('getBatchDetails');
Route::post('/stockTransfer', [BranchStockTransferController::class, 'stockTransfer'])->name('stockTransfer');
Route::get('/stock-transfer-history/{id}', [BranchStockTransferController::class, 'show'])->name('stock-transfer-history.show');
Route::delete('/stock-transfer/{id}', [BranchStockTransferController::class, 'destroy'])->name('stock-transfer.destroy');

//Consultation Bookings

Route::get('/booking/consultation-booking', [BookingController::class, 'ConsultationIndex'])->name('bookings.consultation.index');
Route::get('/booking/consultation-booking/create', [BookingController::class, 'ConsultationCreate'])->name('create.consultation.booking');
Route::get('/consultation/get-staffs', [BookingController::class, 'getDoctors'])->name('consultation.getStaffs');
Route::get('/consultation/get-bookingfee', [BookingController::class, 'getBookingFee'])->name('getBookingFee');
Route::get('/consultation/getMembershipDetails', [BookingController::class, 'getMembershipDetails'])->name('getMembershipDetails');
Route::get('/consultation/getMembershipAndBookingFee', [BookingController::class, 'getMembershipAndBookingFee'])->name('getMembershipAndBookingFee');
Route::post('/booking/consultation-booking/savepatient', [BookingController::class, 'storePatient'])->name('savepatient.consultation.booking');
Route::post('/booking/consultation-booking/savemember', [BookingController::class, 'saveMember'])->name('savemember.consultation.booking');
Route::post('/booking/consultation-booking/patientbooking', [BookingController::class, 'patientBooking'])->name('patientbooking.consultation.booking');
Route::get('/booking/consultation-booking/show/{id}', [BookingController::class, 'showBooking'])->name('show.consultation.booking');
Route::delete('/booking/consultation-booking/delete/{id}', [BookingController::class, 'deleteBooking'])->name('delete.consultation.booking');
Route::get('/booking/consultation-booking/print/{id}', [BookingController::class, 'generatePDF'])->name('consultation.booking.invoices.print');


//wellness booking
Route::get('/booking/wellness-booking', [BookingController::class, 'WellnessIndex'])->name('bookings.wellness.index');
Route::get('/booking/wellness-booking/create', [BookingController::class, 'WellnessCreate'])->name('create.wellness.booking');
Route::get('/booking/getWellness', [BookingController::class, 'getWellnessList'])->name('booking.getWellness');
Route::get('/booking/wellness/bookingfee', [BookingController::class, 'wellnessFee'])->name('wellness.getBookingFee');
Route::get('/wellness/getMembershipAndBookingFee', [BookingController::class, 'wellnessMembershipandFee'])->name('wellness.getMembershipAndBookingFee');
Route::get('/wellness/getPatientMembershipDetails', [BookingController::class, 'getPatientMembershipDetails'])->name('wellness.getPatientMembershipDetails');
Route::get('/wellness/getMembershipWellness', [BookingController::class, 'getMembershipWellness'])->name('wellness.getMembershipWellness');
Route::post('/booking/wellness-booking/patientbooking', [BookingController::class, 'patientWellnessBooking'])->name('patientbooking.wellness.booking');
Route::get('/wellness-booking/view/{id}', [BookingController::class, 'viewWellnessBooking'])->name('view.wellness.booking');
Route::delete('/booking/wellness-booking/delete/{id}', [BookingController::class, 'deleteWellnessBooking'])->name('delete.wellness.booking');
Route::get('/booking/wellness-booking/print/{id}', [BookingController::class, 'generateWellnessPDF'])->name('wellness.booking.invoices.print');
Route::get('/get-available-balance', [BookingController::class, 'getWellnessAvailableBalance'])->name('wellness.booking.get-available-balance');


//Therapy Booking
Route::get('/booking/therapy-booking', [BookingController::class, 'TherapyBooking'])->name('bookings.therapy.index');
Route::get('/booking/therapy-booking/create', [BookingController::class, 'TherapyCreate'])->name('create.therapy.booking');
Route::get('/booking/getTherapy', [BookingController::class, 'getTherapyList'])->name('booking.getTherapy');
Route::get('/booking/therapy/getTherapyBookingFee', [BookingController::class, 'getTherapyBookingFee'])->name('therapy.getTherapyBookingFee');
Route::get('/getInternalDoctors', [BookingController::class, 'getInternalDoctors'])->name('getInternalDoctors');
Route::get('/getExternalDoctors', [BookingController::class, 'getExternalDoctors'])->name('getExternalDoctors');
Route::post('/booking/therapy-booking/patienttherapybooking', [BookingController::class, 'patientTherapyBooking'])->name('patientbooking.therapy.booking');
Route::get('/therapy-booking/view/{id}', [BookingController::class, 'viewTherapyBooking'])->name('view.therapy.booking');
Route::delete('/booking/therapy-booking/delete/{id}', [BookingController::class, 'deleteTherapyBooking'])->name('delete.therapy.booking');
Route::get('/booking/therapy-booking/print/{id}', [BookingController::class, 'generateTherapyPDF'])->name('therapy.booking.invoices.print');

//Therapy Refund

Route::get('/booking/therapy-refund', [BookingController::class, 'TherapyRefundindex'])->name('bookings.therapy-refund.index');
Route::get('/booking/therapy-refund/create', [BookingController::class, 'TherapyRefundCreate'])->name('create.therapy-refund');
Route::post('/booking/therapy-refund/store', [BookingController::class, 'TherapyRefundStore'])->name('store.therapy-refund');
Route::get('/booking/therapy-refund/fetchRefundBookings', [BookingController::class, 'fetchRefundBookings'])->name('fetch.refund.bookings');
Route::get('/booking/therapy-refund/fetchtherapyInfo', [BookingController::class, 'fetchtherapyInfo'])->name('fetch.refund.fetchtherapyInfo');

//Doctor - Consultation 
Route::get('/doctor/consultation/index', [ConsultationController::class, 'ConsultIndex'])->name('consultation.index');
Route::get('/doctor/consultation/prescription/add/{id}', [ConsultationController::class, 'PrescriptionAdd'])->name('doctor.precription.add');
Route::post('/doctor/consultation/prescription/store', [ConsultationController::class, 'PrescriptionStore'])->name('doctor.precription.store');
Route::get('/doctor/patient/history/{id}', [ConsultationController::class, 'PatientHistory'])->name('doctor.patient.history');
Route::get('/doctor/consultation/history', [ConsultationController::class, 'ConsultHistory'])->name('consultation.history');
Route::get('/doctor/consultation/view/{id}', [ConsultationController::class, 'viewConsultation'])->name('doctor.consultation.view');

//Accountant - Staff cash deposit
Route::get('/staff-cash-deposit/index', [TrnJournelEntryController::class, 'CashDepositIndex'])->name('staff.cash.deposit.index');
Route::get('/staff-cash-deposit/create', [TrnJournelEntryController::class, 'CashDepositCreate'])->name('create.cash.deposit');
Route::post('/staff-cash-deposit/store', [TrnJournelEntryController::class, 'CashDepositStore'])->name('staff.cash.deposit.store');
Route::get('/staff-cash-deposit/show/{id}', [TrnJournelEntryController::class, 'CashDepositShow'])->name('staff.cash.deposit.show');

//doctor-leave
Route::get('/doctor-leave/index', [DoctorLeaveRequestController::class, 'index'])->name('employee.index');
Route::get('doctor-leave/create', [DoctorLeaveRequestController::class, 'create'])->name('employee.create');
Route::post('/doctor-leave/store', [DoctorLeaveRequestController::class, 'store'])->name('employee.store');
Route::get('/doctor-leave/{staffleave_id}', [DoctorLeaveRequestController::class, 'show'])->name('employee.show');
Route::get('/doctor-leave/edit/{id}', [DoctorLeaveRequestController::class, 'edit'])->name('employee.edit');
Route::put('/doctor-leave/update/{id}', [DoctorLeaveRequestController::class, 'update'])->name('employee.update');
Route::delete('doctor-leave/destroy/{id}', [DoctorLeaveRequestController::class, 'destroy'])->name('employee.destroy');

//doctor-attendance
Route::get('/doctor-attendance', [DoctorAttendanceController::class, 'viewAttendance'])->name('doctor.attendance.view');
Route::get('/doctor-attendance/monthly', [DoctorAttendanceController::class, 'monthlyAttendance'])->name('doctorattendance.monthly');
  
//Salary process new routes

Route::patch('salary-processing/change-status/{id}', [SalaryProcessingController::class, 'changeStatus'])->name('salary_processing.changeStatus');
Route::get('salary-processing/view/{id}', [SalaryProcessingController::class, 'salaryProcessingView'])->name('salary_processing.view');
Route::get('/get-advance-salary/{staff_id}/{salary_month}', [SalaryProcessingController::class, 'getAdvanceSalary'])->name('get-advance-salary');

//HRMS - Advance Salary
Route::get('/advance-salary/index', [SalaryProcessingController::class, 'AdvanceSalaryIndex'])->name('advance-salary.index');
Route::get('/advance-salary/create', [SalaryProcessingController::class, 'AdvanceSalaryCreate'])->name('staff.advance-salary.create');
Route::post('/advance-salary/store', [SalaryProcessingController::class, 'AdvanceSalaryStore'])->name('staff.advance-salary.store');
Route::get('/advance-salary/view/{id}', [SalaryProcessingController::class, 'AdvanceSalaryView'])->name('staff.advance-salary.view');

});





