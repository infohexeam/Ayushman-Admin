<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trn_Medicine_Sales_Invoice extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'trn__medicine__sales__invoices';
    protected $primaryKey = 'sales_invoice_id';

    protected $fillable = [
        'sales_invoice_number',
        'patient_id',
        'booking_id',
        'invoice_date',
        'branch_id',
        'sales_person_id',
        'notes',
        'terms_and_conditions',
        'sub_total',
        'total_tax_amount',
        'total_amount',
        'discount_amount',
        'payable_amount',
        'paid_amount',
        'financial_year_id',
        'deposit_to',
        'payment_mode',
        'is_deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    public function Staff()
    {
        return $this->belongsTo(Mst_Staff::class, 'sales_person_id','staff_id');
    }
    
    public function patient()
    {
        return $this->belongsTo(Mst_Patient::class, 'patient_id','id');
    }
    
    public function Branch()
    {
        return $this->belongsTo(Mst_Branch::class, 'branch_id');
    }
    public function salesInvoiceDetails()
    {
        return $this->hasMany(Trn_Medicine_Sales_Invoice_Details::class, 'sales_invoice_id', 'sales_invoice_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Mst_Pharmacy::class, 'pharmacy_id','id');
    }
    
    public function salesInvoicePayments()
    {
        return $this->hasMany(Trn_Sales_Invoice_Payment::class, 'sales_invoice_id', 'sales_invoice_id');
    }

}
