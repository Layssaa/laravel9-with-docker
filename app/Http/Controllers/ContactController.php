<?php

namespace App\Http\Controllers;

use App\Http\Requests\PutContactRequest;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contacts;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
       $contacts = Contacts::select('id', 'name', 'email')->get();
       return response()->json($contacts);
    }
    public function show($id){
        $contact = Contacts::select('id', 'name', 'email')
                ->where('id', $id)
                ->first();

        if(!$contact){
            return response()->json(['message' => 'Contato não foi encontrado.'], 404);
        }

       return response()->json($contact);

    }

    public function store(StoreContactRequest $request){
        $contactAlreadyExists = Contacts::where('email', $request->email)->first();

        if($contactAlreadyExists) {
            return response()->json([
                'message' => 'Contact already exists!'
            ], 404);
        }
        
        $contact = Contacts::create([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return response()->json($contact);
    }

    public function update($id, PutContactRequest $request){
        $contact = Contacts::find($id);

        if(!$contact) {
            return response()->json([
                'message' => 'Contact not found.'
            ], 404);
        }

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->save();

        return $contact;

    }

    public function destroy($id){
        // existe?
        $contact = Contacts::find($id);

        //se não existe erro
        if(!$contact){
            return response()->json(['message'=>'Contact not found.'], 404);
        }

        // se existe delete
        $contact->delete();
        
        // retorne resposta confirmando deleção
        return response()->json(['message'=>'Contact deleted with successfully'], 200);
    }
}
