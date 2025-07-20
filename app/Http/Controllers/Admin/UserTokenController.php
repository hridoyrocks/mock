<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEvaluationToken;
use Illuminate\Http\Request;

class UserTokenController extends Controller
{
    /**
     * Show user tokens management page
     */
    public function index()
    {
        $users = User::with('evaluationTokens')
            ->where('is_admin', false)
            ->orderBy('name')
            ->paginate(20);
        
        return view('admin.user-tokens.index', compact('users'));
    }
    
    /**
     * Show form to manage user tokens
     */
    public function edit(User $user)
    {
        $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
        
        // Get token history
        $tokenHistory = \DB::table('token_transactions')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('admin.user-tokens.edit', compact('user', 'tokenBalance', 'tokenHistory'));
    }
    
    /**
     * Add tokens to user
     */
    public function addTokens(Request $request, User $user)
    {
        $request->validate([
            'tokens' => 'required|integer|min:1|max:1000',
            'reason' => 'required|string|max:255'
        ]);
        
        $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
        $tokenBalance->addTokens($request->tokens, 'admin_grant');
        
        // Log the transaction
        \DB::table('token_transactions')->insert([
            'user_id' => $user->id,
            'type' => 'admin_grant',
            'amount' => $request->tokens,
            'balance_after' => $tokenBalance->available_tokens,
            'reason' => $request->reason,
            'admin_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', "Successfully added {$request->tokens} tokens to {$user->name}");
    }
    
    /**
     * Deduct tokens from user
     */
    public function deductTokens(Request $request, User $user)
    {
        $request->validate([
            'tokens' => 'required|integer|min:1',
            'reason' => 'required|string|max:255'
        ]);
        
        $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
        
        if ($tokenBalance->available_tokens < $request->tokens) {
            return redirect()->back()->with('error', 'User does not have enough tokens');
        }
        
        $tokenBalance->available_tokens -= $request->tokens;
        $tokenBalance->save();
        
        // Log the transaction
        \DB::table('token_transactions')->insert([
            'user_id' => $user->id,
            'type' => 'admin_deduct',
            'amount' => -$request->tokens,
            'balance_after' => $tokenBalance->available_tokens,
            'reason' => $request->reason,
            'admin_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', "Successfully deducted {$request->tokens} tokens from {$user->name}");
    }
    
    /**
     * Set exact token balance
     */
    public function setTokens(Request $request, User $user)
    {
        $request->validate([
            'tokens' => 'required|integer|min:0',
            'reason' => 'required|string|max:255'
        ]);
        
        $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
        $oldBalance = $tokenBalance->available_tokens;
        $tokenBalance->available_tokens = $request->tokens;
        $tokenBalance->save();
        
        $difference = $request->tokens - $oldBalance;
        
        // Log the transaction
        \DB::table('token_transactions')->insert([
            'user_id' => $user->id,
            'type' => 'admin_set',
            'amount' => $difference,
            'balance_after' => $tokenBalance->available_tokens,
            'reason' => $request->reason,
            'admin_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', "Successfully set token balance to {$request->tokens} for {$user->name}");
    }
}
