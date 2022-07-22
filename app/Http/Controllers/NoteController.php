<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // show all records 
        // $userId = Auth::id();
        // $notes = Note::where('user_id', $userId)->get();
        // dd($notes);

        // show only the title of note
        // $notes = Note::where('user_id', Auth::id())->get();
        // $notes->each(function($note){
        //     dump($note->title);
        // });

        // show the title in order "updated_at"
        // $notes = Note::where('user_id', Auth::id())->latest('updated_at')->get();
        // $notes->each(function($note){
        //     dump($note->title);
        // });

        // show all records 
        // $notes = Note::where('user_id', Auth::id())->latest('updated_at')->get();
        //return view('notes.index')->with('notes', $notes); // "notes.index" this is the path where the file is

        // show records with pagination
        // $notes = Note::where('user_id', Auth::id())->latest('updated_at')->paginate(5);
        // $notes = Auth::user()->notes()->latest('updated_at')->paginate(5);
        $notes = Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes', $notes);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        // One way to save the records
        /*
            $note = new Note([
                'user_id' => Auth::id(),
                'title'   => $request->title,
                'text'   => $request->text
            ]);
            $note->save();
        */

        // Another way to save the records
        Auth::user()->notes()->create([
            'uuid' => Str::uuid(),
            'title'   => $request->title,
            'text'   => $request->text
        ]);

        return to_route('notes.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 1  - away 
    public function show(Note $note)
    {
        // to compare two records on the same module
        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        return view('notes.show')->with('note', $note);
    }

    // 2 - away "findkeyAmora"
    /*
        public function show($uuid)
        {
            $note = Note::where('uuid',$uuid)->where('user_id', Auth::id())->firstOrFail();
            return view('notes.show')->with('note', $note);
        }
    */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note )
    {
        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        return view('notes.edit')->with('note', $note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {

        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        $note->update([
            'title' => $request->title,
            'text' => $request->text
        ]);

        return to_route('notes.show', $note)->with('success', 'Note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        $note->delete();

        return to_route('notes.index')->with('success', 'Note movedto trash');
    }
}
