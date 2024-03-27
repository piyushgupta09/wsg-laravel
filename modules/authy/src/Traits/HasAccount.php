<?php

namespace Fpaipl\Authy\Traits;

use Fpaipl\Authy\Models\Account;

trait HasAccount {

    public function hasAccount()
    {
        return $this->account()->exists();
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function accountAll()
    {
        return $this->hasOne(Account::class)->withoutGlobalScope('excludePending');
    }
    
}
