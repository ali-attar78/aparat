<?php

namespace App\Services;




 use App\Http\Requests\Comment\ChangeCommentStateRequest;
 use App\Http\Requests\Comment\CreateCommentRequest;
 use App\Http\Requests\Comment\DeleteCommentRequest;
 use App\Http\Requests\Comment\ListCommentRequest;
 use App\Http\Requests\Playlist\ListPlaylistRequest;
 use App\Http\Requests\Playlist\MyPlaylistRequest;
 use App\Http\Requests\Playlist\PlaylistCreateRequest;
 use App\Http\Requests\Tag\ListTagRequest;
 use App\Models\Comment;
 use App\Models\Playlist;
 use App\Models\Video;

 class CommentService extends BaseService
{


     public static function index(ListCommentRequest $request)
     {
         $comments =  Comment::channelComments($request->user()->id);

         if ($request->has('state')){
             $comments->where('comments.state' , $request->state);
         }

         return $comments->get();
     }

     public static function create(CreateCommentRequest $request)
     {

         $user = $request->user();
         $video = Video::find($request->video_id);
          $comment = $user->comments()->create([
             'video_id' => $request->video_id,
             'parent_id' => $request->parent_id,
             'body' => $request->body,
             'state' => $video->user_id == $user->id
                 ?  Comment::STATE_ACCEPTED
                 : Comment::STATE_PENDING,
         ]);

         return $comment;

     }

     public static function changeState(ChangeCommentStateRequest $request)
     {

         $comment = $request->comment;
         $comment->state = $request->state;
         $comment->save();

         return response(['message'=>'با موفقیت تغییر یافت'],200);

     }

     public static function delete(DeleteCommentRequest $request)
     {

         $request->comment->delete();
         return response(['message'=>'با موفقیت حذف شد'],200);

     }


 }
