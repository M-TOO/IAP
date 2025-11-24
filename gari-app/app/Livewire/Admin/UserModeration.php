<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination; // 1. Import
use App\Models\User;
use Carbon\Carbon;

class UserModeration extends Component
{
    use WithPagination; // 2. Use

    public $search = '';

    // Suspension State
    public $confirmingSuspension = false;
    public $userToSuspendId = null;
    public $userToSuspendName = '';
    public $suspensionDuration = 7;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function confirmSuspension($userId)
    {
        $user = User::find($userId);
        if ($user && $user->role !== 'admin') {
            $this->userToSuspendId = $user->id;
            $this->userToSuspendName = $user->name;
            $this->confirmingSuspension = true;
            $this->suspensionDuration = 7; 
        }
    }

    public function suspendUser()
    {
        if ($this->userToSuspendId) {
            $days = (int) $this->suspensionDuration;
            $date = $days === -1 ? Carbon::now()->addYears(100) : Carbon::now()->addDays($days);

            User::find($this->userToSuspendId)->update(['suspended_until' => $date]);

            $this->confirmingSuspension = false;
            session()->flash('message', "User suspended until " . $date->format('M d, Y'));
        }
    }

    public function unsuspendUser($userId)
    {
        User::find($userId)->update(['suspended_until' => null]);
        session()->flash('message', "User access restored.");
    }

    public function cancelSuspension()
    {
        $this->confirmingSuspension = false;
    }

    public function render()
    {
        $users = User::where('role', 'customer')
                     ->where('name', 'like', '%'.$this->search.'%')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10); // 3. Paginate

        return view('admin.user-moderation', ['users' => $users]);
    }
}