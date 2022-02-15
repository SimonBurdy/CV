<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommentController extends Controller
{
   
    protected $modelsNamespace = 'App\Models';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->validate($request, [
            'modelClass' => 'required',
            'modelId' => 'required'
        ]);

        $modelClass = $this->modelsNamespace.'\\'.$request->input('modelClass');

        $modelId = $request->input('modelId');

        return $modelClass::findOrFail($modelId)->comments()->with('user')->get()->sortByDesc('created_at')->values();


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'modelClass' => 'required',
            'modelId' => 'required',
            'body' => 'required'
        ]);


        $modelClass = $this->modelsNamespace.'\\'.$request->input('modelClass');

        $modelId = $request->input('modelId');

        $body = $request->input('body');

        $newComment = $modelClass::findOrFail($modelId)->writeComment($body)->load('user');
        //$newComment->from_url = $request->input('from_url');

        return $newComment;

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $comment = Comment::with('user')->findOrFail($id);

        $comment->body = $request->input('body');

        $comment->save();

        return $comment;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::destroy($id);

        return 'ok';
    }
}
