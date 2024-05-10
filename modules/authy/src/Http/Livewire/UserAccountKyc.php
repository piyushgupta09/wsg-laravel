<?php

namespace Fpaipl\Authy\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Fpaipl\Authy\Models\Account;
use Fpaipl\Authy\Models\Address;
use Fpaipl\Authy\Events\Approved;

class UserAccountKyc extends Component
{
    public $accountId;
    public $account;
    public $docs = [];
    public $reason;
    public $blocked;
    
    public function mount($modelId)
    {
        $this->accountId = $modelId;
        $this->account = Account::find($modelId);
        $this->blocked = !$this->account->user->active;
        if ($this->account->gstin) {
            $this->docs['gstin'] = $this->account->gstin;
        }
        if ($url = $this->account->getFirstMediaUrl(Account::GST_FILE)) {
            $this->docs['gstin_file'] = $url;
        }
        if ($this->account->pan) {
            $this->docs['pan'] = $this->account->pan;
        }
        if ($url = $this->account->getFirstMediaUrl(Account::PAN_FILE)) {
            $this->docs['pan_file'] = $url;
        }
        if ($this->account->aadhar) {
            $this->docs['aadhar'] = $this->account->aadhar;
        }
        if ($url = $this->account->getFirstMediaUrl(Account::AADHAR_FILE)) {
            $this->docs['aadhar_file'] = $url;
        }
        if ($this->account->bank) {
            $this->docs['bank'] = $this->account->bank;
        }
        if ($url = $this->account->getFirstMediaUrl(Account::BANK_FILE)) {
            $this->docs['bank_file'] = $url;
        }  
        if ($this->account->other) {
            $this->docs['other'] = $this->account->other;
        }
        if ($url = $this->account->getFirstMediaUrl(Account::OTHER_FILE)) {
            $this->docs['other_file'] = $url;
        }  
        $this->docs['created_at'] = date_format($this->account->created_at, 'Y-m-d H:i:s');
        $this->docs['updated_at'] = Carbon::parse($this->account->updated_at)->diffForHumans();
    }

    public function approveAccount()
    {
        $this->account->update([
            'status' => 'approved',
            'reason' => $this->reason,
            'approver_name' => auth()->user()->name,
        ]);

        event(new Approved($this->account->user));
    
        return redirect()->route('accounts.show', $this->account->id)->with('toast', [
            'class' => 'success',
            'text' => 'Account Approved successfully.',
        ]);
    }
    
    public function rejectAccount(): void
    {
        if ($this->reason == null) {
            session()->flash('message', 'Please enter a reason for rejection.');
            return;
        }
        $this->account->update([
            'status' => 'rejected',
            'reason' => $this->reason,
            'approver_name' => auth()->user()->name,
        ]);
        session()->flash('message', 'rejected successfully.');
    }

    public function archiveAccount(): void
    {
        $this->account->delete();
        session()->flash('message', 'deleted successfully.');
    }

    public function blockAccount(): void
    {
        $this->account->user->active = false;
        $this->account->user->save();
        // invalidate all tokens
        $this->account->user->tokens()->delete();
        session()->flash('message', 'blocked successfully.');
    }
    
    public function unBlockAccount(): void
    {
        $this->account->user->active = true;
        $this->account->user->save();
        session()->flash('message', 'unblocked successfully.');
    }

    public function deleteAccount()
    {
        $this->account->fill([
            'kycstep' => 'business',
            'status' => 'pending',
            'reason' => null,
            'approver_name' => null,
            'name' => $this->account->user->name,
            'type' => null,
            'lifespan' => null,
            'turnover' => null,
            'address' => null,
            'city' => null,
            'pincode' => null,
            'state' => null,
            'contact' => null,
            'location' => null,
            'gstin' => null,
            'aadhar' => null,
            'other' => null,
            'pan' => null,
            'bank' => null,
            'tags' => null,
        ]);
        $this->account->save();

        // Delete all attached document
        $this->account->clearMediaCollection(Account::GST_FILE);
        $this->account->clearMediaCollection(Account::PAN_FILE);
        $this->account->clearMediaCollection(Account::AADHAR_FILE);
        $this->account->clearMediaCollection(Account::BANK_FILE);
        $this->account->clearMediaCollection(Account::OTHER_FILE);

        session()->flash('message', 'deleted successfully.');
        return redirect()->route('accounts.index')->with('toast', [
            'class' => 'success',
            'text' => 'Account Deleted successfully.',
        ]);
    }

    public function reKyc()
    {
        $this->account->update([
            'kycstep' => 'business',
            'status' => 'pending',
            'reason' => null,
        ]);

        return redirect()->route('accounts.index')->with('toast', [
            'class' => 'success',
            'text' => 'Account Reset successfully.',
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('authy::livewire.user-account-kyc');
    }
}
