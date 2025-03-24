<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class WallController extends Controller
{
    public function postMessage(Request $request)
    {
        // Validation des données
        $request->validate([
            'message' => 'required|max:255',
        ]);

        // Création du message et enregistrement dans la base de données
        $message = new Message;
        $message->message = $request->message;
        $message->save();
        

        // Stockage du message dans la session pour l'afficher dans la vue
        session()->flash('message', $request->message);

        // Redirection avec un message de succès
        return back()->with('status', 'Message posté avec succès!');
    }

     public function dashboard()
    {
       $messages =Message::all();
       return view('dashboard',['messages'=>$messages]);
    }

        public function deleteMessage(Request $request)
    {
        $message = Message::findOrFail($request->id);
         $message->delete(); // Suppression du message
        return redirect()->route('dashboard');
    }
}
