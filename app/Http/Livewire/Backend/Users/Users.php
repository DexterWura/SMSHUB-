<?php

namespace App\Http\Livewire\Backend\Users;

use App\Models\User;
use App\Models\WalletLog;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component {

    use WithPagination;

    public $search = '', $message = '', $user = null, $wallet = 0, $order = 'asc', $orderby = 'id';

    public function updateCredits() {
        $this->validate([
            'wallet' => 'required|min:0|max:99999999'
        ]);
        $user = User::find($this->user);
        if ($user) {
            try {
                DB::beginTransaction();
                $log = new WalletLog;
                $log->user_id = $user->id;
                $log->before = $user->wallet;
                $log->after = $this->wallet;
                $log->log = 'Update by Admin - ' . Auth::user()->id . ':' . Auth::user()->name;
                $log->save();
                $user->wallet = $this->wallet;
                $user->save();
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                return;
            }
        }
        $this->wallet = 0;
        $this->user = null;
    }

    public function showWalletDialog($user_id) {
        $this->user = $user_id;
        $this->wallet = User::find($user_id)->wallet;
    }

    public function changeOrder() {
        if ($this->order == 'asc') {
            $this->order = 'desc';
        } else {
            $this->order = 'asc';
        }
    }

    public function render() {
        if ($this->search) {
            $users = User::where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%')->orderBy($this->orderby, $this->order)->paginate(15);
        } else {
            $users = User::orderBy($this->orderby, $this->order)->paginate(15);
        }
        if (count($users) == 0 && $this->search) {
            $this->message = 'No Users Found';
        } else {
            $this->message = '';
        }
        return view('backend.users.users', [
            'users' => $users
        ]);
    }
}
