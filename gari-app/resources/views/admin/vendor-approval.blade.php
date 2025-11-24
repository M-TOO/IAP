<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination; // 1. Import Pagination
use App\Models\Vendor;
use Carbon\Carbon;

class VendorApproval extends Component
{
    use WithPagination; // 2. Use it

    public $statusMessage = '';
    public $search = ''; // 3. Add Search State
    
    // Suspension Modal State
    public $confirmingSuspension = false;
    public $vendorToSuspendId = null;
    public $vendorToSuspendName = '';
    public $suspensionDuration = 7;

    // Reset pagination when searching
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function approve($vendorId)
    {
        Vendor::find($vendorId)->update([
            'status' => 'approved', 
            'approved_at' => Carbon::now(),
            'suspended_until' => null
        ]);
        $this->statusMessage = "Vendor approved successfully.";
    }

    public function reject($vendorId)
    {
        Vendor::find($vendorId)->update(['status' => 'rejected']);
        $this->statusMessage = "Vendor application rejected.";
    }

    public function confirmSuspension($vendorId)
    {
        $vendor = Vendor::find($vendorId);
        $this->vendorToSuspendId = $vendor->id;
        $this->vendorToSuspendName = $vendor->owner_name;
        $this->confirmingSuspension = true;
        $this->suspensionDuration = 7; 
    }

    public function suspendVendor()
    {
        if ($this->vendorToSuspendId) {
            $days = (int) $this->suspensionDuration;
            $date = $days === -1 ? Carbon::now()->addYears(100) : Carbon::now()->addDays($days);

            Vendor::find($this->vendorToSuspendId)->update([
                'status' => 'suspended', 
                'suspended_until' => $date
            ]);

            $this->confirmingSuspension = false;
            session()->flash('message', "Vendor suspended until " . $date->format('M d, Y'));
        }
    }

    public function unsuspendVendor($vendorId)
    {
        Vendor::find($vendorId)->update([
            'status' => 'approved', 
            'suspended_until' => null
        ]);
        session()->flash('message', "Vendor reactivated.");
    }

    public function cancelSuspension()
    {
        $this->confirmingSuspension = false;
    }

    public function render()
    {
        // Query for Active Vendors with Search
        $approvedVendors = Vendor::whereIn('status', ['approved', 'suspended'])
            ->where(function($query) {
                $query->where('owner_name', 'like', '%'.$this->search.'%')
                      ->orWhere('shop_description', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->orderBy('approved_at', 'desc')
            ->paginate(10); // 4. Paginate results

        return view('admin.vendor-approval', [
            'pendingVendors' => Vendor::where('status', 'pending_approval')->orderBy('created_at', 'desc')->get(),
            'approvedVendors' => $approvedVendors
        ]);
    }
}