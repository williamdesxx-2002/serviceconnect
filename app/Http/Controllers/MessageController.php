<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Récupérer les conversations (utilisateurs avec qui on a échangé des messages)
        $conversations = Message::where('sender_id', $user->id)
                              ->orWhere('receiver_id', $user->id)
                              ->with(['sender', 'receiver'])
                              ->orderBy('created_at', 'desc')
                              ->get()
                              ->groupBy(function($message) use ($user) {
                                  return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
                              })
                              ->map(function($group) {
                                  return $group->first();
                              });
        
        // Récupérer tous les messages envoyés et reçus
        $sentMessages = $user->sentMessages()
            ->with('receiver')
            ->latest()
            ->get();
            
        $receivedMessages = $user->receivedMessages()
            ->with('sender')
            ->latest()
            ->get();
            
        return view('messages.index', compact('conversations', 'sentMessages', 'receivedMessages'));
    }
    
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())
                     ->where('is_active', true)
                     ->orderBy('name')
                     ->get();
        
        return view('messages.create', compact('users'));
    }
    
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        
        $users = User::where('id', '!=', auth()->id())
                     ->where('is_active', true)
                     ->where(function($q) use ($query) {
                         $q->where('name', 'like', "%{$query}%")
                           ->orWhere('email', 'like', "%{$query}%");
                     })
                     ->limit(10)
                     ->get(['id', 'name', 'email', 'role', 'avatar']);
        
        return response()->json($users);
    }
    
    public function show(User $user)
    {
        $authUser = auth()->user();
        
        // Empêcher un utilisateur de s'envoyer des messages à lui-même
        if ($authUser->id === $user->id) {
            abort(403, 'Non autorisé - Vous ne pouvez pas vous envoyer des messages à vous-même');
        }
        
        // Récupérer les messages entre les deux utilisateurs (peut être vide)
        $messages = Message::where(function($query) use ($authUser, $user) {
            $query->where('sender_id', $authUser->id)
                  ->where('receiver_id', $user->id)
                  ->orWhere(function($subQuery) use ($authUser, $user) {
                      $subQuery->where('sender_id', $user->id)
                             ->where('receiver_id', $authUser->id);
                  });
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();
        
        // Marquer les messages reçus comme lus
        Message::where('sender_id', $user->id)
               ->where('receiver_id', $authUser->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);
        
        return view('messages.show', compact('messages', 'user'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);
        
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'is_read' => false,
        ]);
        
        return back()->with('success', 'Message envoyé avec succès');
    }
    
    public function markAsRead(Message $message)
    {
        // Vérifier si le message appartient à l'utilisateur connecté
        if ($message->receiver_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }
        
        $message->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
}
