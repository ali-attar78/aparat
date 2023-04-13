<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\ChangeCommentStateRequest;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\ListCommentRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(ListCommentRequest $request)
    {
        return CommentService::index($request);
    }

    public function create(CreateCommentRequest $request)
    {
        return CommentService::create($request);
    }

    public function changeState(ChangeCommentStateRequest $request)
    {
        return CommentService::changeState($request);
    }

    public function delete(DeleteCommentRequest $request)
    {
        return CommentService::delete($request);
    }


}
