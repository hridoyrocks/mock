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
    public function index(Request $request)
    {
        $query = User::with('evaluationTokens')
            ->where('is_admin', false);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        // Filter by token balance
        if ($request->filled('token_filter')) {
            switch($request->token_filter) {
                case 'has_tokens':
                    $query->whereHas('evaluationTokens', function($q) {
                        $q->where('available_tokens', '>', 0);
                    });
                    break;
                case 'no_tokens':
                    $query->where(function($q) {
                        $q->whereDoesntHave('evaluationTokens')
                          ->orWhereHas('evaluationTokens', function($subQ) {
                              $subQ->where('available_tokens', '=', 0);
                          });
                    });
                    break;
                case 'low_tokens':
                    $query->whereHas('evaluationTokens', function($q) {
                        $q->whereBetween('available_tokens', [1, 10]);
                    });
                    break;
            }
        }
        
        // Sort functionality
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'tokens') {
            $query->leftJoin('user_evaluation_tokens', 'users.id', '=', 'user_evaluation_tokens.user_id')
                  ->orderBy('user_evaluation_tokens.available_tokens', $sortOrder)
                  ->select('users.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $users = $query->paginate(20)->withQueryString();
        
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
        \Log::info('Add tokens request', [
            'user_id' => $user->id,
            'tokens' => $request->tokens,
            'reason' => $request->reason,
            'admin_id' => auth()->id()
        ]);

        try {
            $request->validate([
                'tokens' => 'required|integer|min:1|max:1000',
                'reason' => 'required|string|max:255'
            ]);
            
            // Get or create token balance
            $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
            $oldBalance = $tokenBalance->available_tokens;
            
            // Add tokens using the model method
            $tokenBalance->addTokens((int)$request->tokens, 'admin_grant');
            
            // Log the transaction
            \DB::table('token_transactions')->insert([
                'user_id' => $user->id,
                'type' => 'admin_grant',
                'amount' => (int)$request->tokens,
                'balance_after' => $tokenBalance->available_tokens,
                'reason' => $request->reason,
                'admin_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            \Log::info('Tokens added successfully', [
                'user_id' => $user->id,
                'old_balance' => $oldBalance,
                'new_balance' => $tokenBalance->available_tokens,
                'tokens_added' => $request->tokens
            ]);
            
            return redirect()->back()->with('success', "Successfully added {$request->tokens} tokens to {$user->name}");
            
        } catch (\Exception $e) {
            \Log::error('Failed to add tokens', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to add tokens: ' . $e->getMessage());
        }
    }
    
    /**
     * Deduct tokens from user
     */
    public function deductTokens(Request $request, User $user)
    {
        try {
            $request->validate([
                'tokens' => 'required|integer|min:1',
                'reason' => 'required|string|max:255'
            ]);
            
            $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
            
            if ($tokenBalance->available_tokens < $request->tokens) {
                return redirect()->back()->with('error', 'User does not have enough tokens');
            }
            
            $oldBalance = $tokenBalance->available_tokens;
            $tokenBalance->available_tokens -= (int)$request->tokens;
            $tokenBalance->save();
            
            // Log the transaction
            \DB::table('token_transactions')->insert([
                'user_id' => $user->id,
                'type' => 'admin_deduct',
                'amount' => -(int)$request->tokens,
                'balance_after' => $tokenBalance->available_tokens,
                'reason' => $request->reason,
                'admin_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            \Log::info('Tokens deducted successfully', [
                'user_id' => $user->id,
                'old_balance' => $oldBalance,
                'new_balance' => $tokenBalance->available_tokens,
                'tokens_deducted' => $request->tokens
            ]);
            
            return redirect()->back()->with('success', "Successfully deducted {$request->tokens} tokens from {$user->name}");
            
        } catch (\Exception $e) {
            \Log::error('Failed to deduct tokens', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return redirect()->back()->with('error', 'Failed to deduct tokens: ' . $e->getMessage());
        }
    }
    
    /**
     * Set exact token balance
     */
    public function setTokens(Request $request, User $user)
    {
        try {
            $request->validate([
                'tokens' => 'required|integer|min:0',
                'reason' => 'required|string|max:255'
            ]);
            
            $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
            $oldBalance = $tokenBalance->available_tokens;
            $tokenBalance->available_tokens = (int)$request->tokens;
            $tokenBalance->save();
            
            $difference = (int)$request->tokens - $oldBalance;
            
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
            
            \Log::info('Token balance set successfully', [
                'user_id' => $user->id,
                'old_balance' => $oldBalance,
                'new_balance' => $tokenBalance->available_tokens,
                'difference' => $difference
            ]);
            
            return redirect()->back()->with('success', "Successfully set token balance to {$request->tokens} for {$user->name}");
            
        } catch (\Exception $e) {
            \Log::error('Failed to set token balance', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return redirect()->back()->with('error', 'Failed to set token balance: ' . $e->getMessage());
        }
    }
}
