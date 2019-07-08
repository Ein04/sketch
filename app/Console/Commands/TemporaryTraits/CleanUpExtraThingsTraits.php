<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CleanUpExtraThingsTraits{

    public function cleanUpExtraThings()
    {
        $this->mainCleanUp();
        $this->simplifyBooks();
        $this->simplifyChapters();
        $this->simplifyThreads();
        $this->simplifyPosts();
        $this->simplifyVolumns();
    }
    public function mainCleanUp()
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('collaborations');
        Schema::dropIfExists('collection_lists');
        Schema::dropIfExists('comments_to_quotes');
        Schema::dropIfExists('long_comments');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('pool_responses');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('recommend_books');
        Schema::dropIfExists('shengfans');
        Schema::dropIfExists('vote_posts');
        Schema::dropIfExists('xianyus');
        Schema::dropIfExists('session_statuses');

        echo "dropped extra tables\n";
    }

    public function simplifyBooks()
    {
        if (Schema::hasColumn('books', 'lastresponded_at')){
            Schema::table('books', function($table){
                $table->dropColumn(['original','lastresponded_at','lastaddedchapter_at','deleted_at','created_at','updated_at','total_char','last_chapter_id','indentation','weighted_jifen','tongren_yuanzhu','tongren_cp']);
                echo "simplified books table.\n";
            });
        }
    }
    public function simplifyChapters()
    {
        if (Schema::hasColumn('chapters', 'book_id')){
            Schema::table('chapters', function($table){
                $table->dropColumn(['book_id','title','deleted_at','created_at','updated_at','characters','viewed','responded','edited_at']);
                echo "simplified chapters table.\n";
            });
        }
    }
    public function simplifyThreads()
    {
        if (Schema::hasColumn('threads', 'label_id')){
            Schema::table('threads', function($table){
                $table->dropColumn(['book_id','label_id','updated_at','homework_id','post_id','show_homework_profile','top','jinghua','recommended', 'old_list_id']);
                echo "simplified threads table.\n";
            });
        }
    }

    public function simplifyPosts()
    {
        if (Schema::hasColumn('posts', 'long_comment')){
            Schema::table('posts', function($table){
                $table->dropColumn(['updated_at','chapter_id','long_comment','long_comment_id','popular','recommended','as_longcomment']);
                echo "simplified posts table.\n";
            });
        }
    }

    public function simplifyViewHistories()
    {
        if (Schema::hasColumn('view_histories', 'updated_at')){
            DB::table('view_histories')
            ->where('post_id','>',0)
            ->delete();
            Schema::table('view_histories', function($table){
                $table->dropColumn(['updated_at','post_id']);
                echo "simplified books table.\n";
            });
        }
    }

    public function simplifyVolumns(){
        echo "start cleanupVolumn\n";
        if(Schema::hasColumn('volumns', 'book_id')){
            Schema::table('volumns', function($table){
                $table->dropColumn(['volumn_order','deleted_at','created_at','updated_at']);
                $table->renameColumn('book_id', 'thread_id');
                $table->string('title',30)->change();
                $table->string('brief',50)->nullable();
                $table->integer('order_by')->default(0);
            });
            echo "dropped old columns of volumn table\n";
        }
    }
}