<?php namespace Codengine\Awardbank\Components;

use Addgod\MandrillTemplate\Mandrill\Message;
use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use \Cms\Classes\ComponentBase;
use Auth;
use Codengine\Awardbank\Models\SocialPost;
use Codengine\Awardbank\Models\SocialPostResponse;
use Event;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use October\Rain\Argon\Argon;
use October\Rain\Database\Attach\Resizer;
use October\Rain\Exception\AjaxException;
use RainLab\User\Models\User;
use Response;

/**
 * Class SocialFeed
 * @package Codengine\Awardbank\Components
 */

class SocialFeed extends ComponentBase
{
    private $user;
    private $program;
    private $manager = false;

    public function componentDetails()
    {
        return [
            'name'        => 'Social Feed',
            'description' => 'Social Feed'
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }

    public function init()
    {
        $this->user = Auth::getUser();
        if ($this->user && $this->user->currentProgram) {
            $this->program = $this->user->currentProgram;
            $this->manager = $this->checkIfManager();
        }
    }

    public function onRun()
    {

    }

    public function onRender()
    {
        $this->page['mode'] = $this->property('mode');
        $this->page['manager'] = $this->manager;
        $this->coreLoadSequence();
    }

    public function coreLoadSequence()
    {
        $this->generateHtml();
    }

    public function generateHtml()
    {
        if (stripos($this->page->page->getAttribute('url'),'/social-post') !== false) {
            $this->page['post'] = SocialPost::find($this->param('id'));
            $this->page['view_mode'] = 'post-page';
        } else {
            $this->page['posts'] = $this->getPosts();
            $this->page['view_mode'] = $this->page->page->getAttribute('url') == '/social-feed' ? 'page' : 'component';
        }

        $this->page['user'] = $this->user;
    }

    private function checkIfManager()
    {
        $isProgramManager = $this->user->currentProgram->checkIfManager($this->user);
        return $isProgramManager && $this->user->roll!='Symbion Staff';
    }

    private function getPosts($page = 1)
    {
        $posts = SocialPost::where('program_id', '=', $this->user->current_program_id)
            ->orderBy('pinned', 'DESC')
            ->orderBy('created_at', 'DESC');

        if (!empty(Request::get('hash_tag'))) {
            $posts->where('tags', 'like', '%"' . Request::get('hash_tag') . '"%');
        }

        if ($this->page['mode'] == 'dashboard') {
            return $posts->limit(1)->get();
        } else {
            return $posts->paginate(10, $page);
        }
    }

    private function searchPosts($text)
    {
        $posts_id_list = $store_posts = $posts = [];

        //In first step look for users using store name
        $storeIDs = User::where('business_name', 'LIKE', '%' . $text . '%')
            ->orWhere('full_name', 'LIKE', '%' . $text . '%')
            ->orderBy('business_name', 'asc')
            ->orderBy('full_name', 'asc')
            ->pluck('id')
            ->toArray();

        if (!empty($storeIDs)) {
            $store_posts = SocialPost::where('program_id', '=', $this->user->current_program_id)
                ->whereIn('poster_id', $storeIDs)
                ->orderBy('pinned', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get();

            if (!empty($store_posts)) {
                foreach ($store_posts as $store_post) {
                    $posts_id_list[] = $store_post->id;
                }
            }
        }

        $posts = SocialPost::where('program_id', '=', $this->user->current_program_id)
            ->where('content', 'LIKE', '%' . $text . '%')
            ->whereNotIn('id', $posts_id_list)
            ->orderBy('pinned', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $posts_id_list[] = $post->id;
            }
        }

        //Merge the results
        if (!empty($store_posts)) {
            $posts = $store_posts->merge($posts);
        }

        //Add matching comments
        $comments = SocialPostResponse::where('response_type','=','comment')
            ->where('response_content', 'LIKE', '%' . $text . '%')
            ->orderBy('created_at', 'DESC')
            ->pluck('social_post_id');

        if (!empty($comments)) {
            $comment_posts = SocialPost::whereIn('id', $comments)->get();
            foreach ($comment_posts as $comment_post) {
                if (!in_array($comment_post->id, $posts_id_list)) {
                    $posts[] = $comment_post;
                }
            }
        }

        return $posts;
    }

    public function onPostsPaginate()
    {
        $posts = $this->getPosts(post('page'));
        $html = $this->renderPartial('@postfeed.htm',
            [
                'user' => $this->user,
                'posts' => $posts,
                'manager' => $this->manager,
            ]
        );

        $result['html']['#socialfeed'] = $html;
        return $result;
    }

    public function onPostsSearch()
    {
        $posts = $this->searchPosts(post('text'));
        $html = $this->renderPartial('@postfeed.htm',
            [
                'user' => $this->user,
                'posts' => $posts,
                'manager' => $this->manager,
            ]
        );

        $result['html']['#socialfeed'] = $html;
        return $result;
    }

    public function onPost()
    {
        if (empty(post('content')) && empty(request('files'))) {
            throw new AjaxException(
                [
                    'X_OCTOBER_ERROR_FIELDS' => [
                        'content' => ['Content is required']
                    ],
                    'X_OCTOBER_ERROR_MESSAGE' => 'Please write a message or attach a file first.'
                ]
            );
        }

        $post = new SocialPost();
        $post->program_id = $this->user->current_program_id;
        if (!empty(post('content'))) {
            $post->content = $this->processPostContent(post('content'));
            $post->tags = $this->parseContentTags(post('content'));
        } else {
            $post->content = '';
        }
        $post->poster_id = $this->user->id;
        $post->pinned = 0;

        if ($post->save()) {
            if (request()->has('files') && !empty(request('files'))) {
                foreach (request('files') as $uploaded_file) {
                    $file = new \System\Models\File;
                    $file->data = $uploaded_file;
                    $file->is_public = true;
                    $file->save();

                    $post->attachments()->add($file);
                }
            }

            return [
                '^#socialfeed' => $this->renderPartial('@post', [
                    'post' => $post,
                    'manager' => $this->manager,
                    'addwrapper' => true,
                    'user' => $this->user
                ]),
                '#newpostwrapper' => $this->renderPartial('@newpost', [
                    'user' => $this->user
                ])
            ];
        }
    }

    private function processPostContent($post)
    {
        //Replace all links with anchor elements
        $post = trim($post);
        $post = strip_tags($post);
        $post = stripslashes($post);
        $post = htmlspecialchars($post);

        //Replace vimeo link with embed
        if (stripos($post, 'vimeo.com') !== false) {
            $post = preg_replace(
                '/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/',
                '<iframe src="//player.vimeo.com/video/$3" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
                $post
            );

            return $post;
        }

        //I want to share this video https://vimeo.com/123478917
        //Replace vimeo link with embed
        if (stripos($post, 'vimeo.com/video') !== false) {
            $post = preg_replace(
                '/\s*[a-zA-Z\/\/:\.]*player.vimeo.com\/video\/([0-9]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)\??/',
                '<iframe src="//player.vimeo.com/video/$1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
                $post
            );

            return $post;
        }

        //Replace youtube link with embed
        if (stripos($post, 'be.com/watch') !== false) {
            $post = preg_replace(
                "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                "<iframe src=\"https://www.youtube.com/embed/$2\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>",
                $post
            );

            return $post;
        }

        //Replace all links with anchor elements
        $post = preg_replace('/(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/im', '<a href="$0" target="_blank">$0</a>', $post);

        //Replace all tags with anchors
        $post = preg_replace('/#(\w+)/', '<a href="/social-feed?hash_tag=$1">#$1</a>', $post);

        $post = str_replace([PHP_EOL, '\r'], ['<br>', '<br>'], $post);

        return $post;
    }

    private function parseContentTags($post)
    {
        preg_match_all('/(?<!\w)#\w+/', $post, $allMatches);
        $allMatches = array_map(function($val) { return str_replace('#', '', $val); }, $allMatches);

        return $allMatches[0] ?? [];
    }

    public function onPin()
    {
        if (request()->has('social_post_id')) {
            $post = SocialPost::find(request()->get('social_post_id'));
            if ($post) {
                $post->pinned = 1;
                if ($post->save()) {
                    $postHtml = $this->renderPartial('@post', [
                        'post' => $post,
                        'manager' => $this->manager,
                    ]);

                    $result['html']['#social-post-wrapper-' . $post->id] = $postHtml;
                    $result['updatesucess'] = "Post has been pinned to the top.";
                    return $result;
                }
            }
        }
    }

    public function onUnpin()
    {
        if (request()->has('social_post_id')) {
            $post = SocialPost::find(request()->get('social_post_id'));
            if ($post) {
                $post->pinned = 0;
                if ($post->save()) {
                    $postHtml = $this->renderPartial('@post', [
                        'post' => $post,
                        'manager' => $this->manager,
                    ]);

                    $result['html']['#social-post-wrapper-' . $post->id] = $postHtml;
                    $result['updatesucess'] = "Post has been unpinned from the top.";
                    return $result;
                }
            }
        }
    }

    public function onDelete()
    {
        if (request()->has('social_post_id')) {
            $post = SocialPost::find(request()->get('social_post_id'));
            if ($post) {
                if ($this->manager || $post->poster->id == $this->user->id) {
                    if ($post && $post->delete()) {
                        $post->pinned = 0;
                        $result['html']['#social-post-wrapper-' . $post->id] = '';
                        $result['updatesucess'] = "Post has been deleted.";
                    }

                    return $result;
                }
            }
        }
    }

    public function onLike()
    {
        if (request()->has('social_post_id')) {
            $postResponse = SocialPostResponse::where('social_post_id', '=', post('social_post_id'))
                ->where('response_type', '=', 'like')
                ->where('poster_id', '=', $this->user->id)
                ->first();
            if ($postResponse) {
                $postResponse->delete();
            } else {
                $postResponse = new SocialPostResponse();
                $postResponse->social_post_id = post('social_post_id');
                $postResponse->response_type = 'like';
                $postResponse->response_content = 1;
                $postResponse->poster_id = $this->user->id;
                $postResponse->save();
            }

            $likehtml = $this->renderPartial('@postlike', [
                    'post' => $postResponse->social_post
                ]);

            $statshtml = $this->renderPartial('@poststats', [
                'post' => $postResponse->social_post
            ]);

            $result['html']['#social-post-control-like-' . post('social_post_id')] = $likehtml;
            $result['html']['#social-post-stats-' . post('social_post_id')] = $statshtml;
            return $result;
        }
    }

    public function onComment()
    {
        if (empty(post('comment')) && empty(request('attachment'))) {
            throw new AjaxException(
                [
                    'X_OCTOBER_ERROR_FIELDS' => [
                        'content' => ['Content is required']
                    ],
                    'X_OCTOBER_ERROR_MESSAGE' => 'Please write a message or attach a file first.'
                ]
            );
        }

        if (request()->has('social_post_id')) {
            $postResponse = new SocialPostResponse();
            $postResponse->social_post_id = post('social_post_id');
            $postResponse->response_type = 'comment';
            $postResponse->response_content = post('comment') ?? '';
            $postResponse->poster_id = $this->user->id;
            if ($postResponse->save()) {
                $post = SocialPost::find(post('social_post_id'));
                if ($post) {
                    $postOwnerName = empty($post->poster->name) ?
                        $post->poster->full_name : $post->poster->name;

                    $posterName = empty($this->user->full_name) ?
                        trim(implode(" ", [$this->user->name, $this->user->surname])) :
                        $this->user->full_name;

                    //Send notification to post creator
                    if ($post->poster->id != $this->user->id) {
                        $this->sendNotificationEmail(
                            'someone-commented-on-your-5step-post-xtend-2-0-1',
                            $post->poster->email,
                            'Someone has commented on your 5STEP Social Post',
                            [
                                'post_url' => Config::get('app.url') . '/social-post/' . $post->id,
                                'poster_name' => $posterName,
                                'FNAME' => $postOwnerName
                            ]
                        );
                    }
                    //Send notification to last 3 persons that commented on this post - EXCLUDING THE THE CURRENT USER
                    if (!empty($post->recentCommentPosters)) {
                        foreach ($post->recentCommentPosters as $recentCommentPoster) {
                            $recentCommentPosterName = empty($recentCommentPoster->name) ?
                                $recentCommentPoster->full_name : $recentCommentPoster->name;

                            $this->sendNotificationEmail(
                                'fname-commented-on-fname-s-post-xtend-2-0',
                                $recentCommentPoster->email,
                                $posterName . ' commented on ' . $postOwnerName . '`s post',
                                [
                                    'post_url' => Config::get('app.url') . '/social-post/' . $post->id,
                                    'poster_name' => $posterName,
                                    'FNAME' => $recentCommentPosterName
                                ]
                            );
                        }
                    }
                }
            }

            if (request()->has('attachment') && !empty(request('attachment'))) {
                $file = new \System\Models\File;
                $file->data = request('attachment');
                $file->is_public = true;
                $file->save();

                $postResponse->attachment()->add($file);
            }

            $commentshtml = $this->renderPartial('@postcomments', [
                'post' => $postResponse->social_post
            ]);

            return [
                '#social-post-control-comments-' . post('social_post_id') => $commentshtml,
                '#social-post-control-new-comment-wrapper-' . post('social_post_id') => $this->renderPartial(
                    '@newcomment', [
                    'post' => $postResponse->social_post
                    ]
                )
            ];
        }
    }

    public function onCommentLike()
    {
        if (request()->has('comment_id')) {
            $comment = SocialPostResponse::find(request()->get('comment_id'));
            if ($comment) {
                if (empty($comment->likes)) {
                    $comment->likes = [$this->user->id];
                } else {
                    $likes = $comment->likes;
                    if (in_array($this->user->id, $comment->likes)) {
                        $valueIndex = array_search($this->user->id, $likes);
                        unset($likes[$valueIndex]);
                    } else {
                        $likes[] = $this->user->id;
                    }
                    $comment->likes = $likes;
                }

                $comment->save();

                $result['html']['#social-post-comment-interactive-controls-' . $comment->id] = $this->renderPartial(
                        '@commentlike', [
                            'comment' => $comment
                        ]
                    );
                return $result;
            }
        }
    }

    public static function onExportSocialFeedReport($program_id, $start_date, $end_date)
    {
        $filename = storage_path('/csv/export/SocialFeedReport.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Created At',
            'Content Type',
            'Username',
            'Business Name',
            'CRM',
            'State',
            'KAM',
            'Hashtags',
            'Content',
            'Comment text',
            'Likes',
            'Post Association'
        ];

        fputcsv($handle, $outputarray);

        $posts_data = SocialPost::whereBetween('created_at', [$start_date, $end_date])
            ->where('program_id', '=', $program_id)
            ->get();
        if (!empty($posts_data)) {
            foreach ($posts_data as $row) {
                $tags = '';
                if (is_array($row->tags) && !empty($row->tags)) {
                    $tags = implode(', ', $row->tags);
                }

                fputcsv($handle, [
                    $row->created_at->setTimezone("Australia/Sydney"),
                    'Social Post',
                    isset($row->poster) ? $row->poster->full_name : '',
                    isset($row->poster) ? $row->poster->business_name : '',
                    isset($row->poster) ? $row->poster->crm : '',
                    isset($row->poster) ? $row->poster->state : '',
                    isset($row->poster) ? $row->poster->kam : '',
                    $tags,
                    strip_tags($row->content),
                    '',
                    count($row->likes),
                    $row->id
                ]);
            }
        }

        $comments_data = SocialPostResponse::whereBetween('created_at', [$start_date, $end_date])
            ->where('response_type', '=', 'comment')
            ->get();

        if (!empty($comments_data)) {
            foreach ($comments_data as $row) {
                fputcsv($handle, [
                    $row->created_at,
                    'Comment',
                    isset($row->poster) ? $row->poster->full_name : '',
                    isset($row->poster) ? $row->poster->business_name : '',
                    isset($row->poster) ? $row->poster->crm : '',
                    isset($row->poster) ? $row->poster->state : '',
                    isset($row->poster) ? $row->poster->kam : '',
                    '',
                    '',
                    $row->response_content,
                    0,
                    $row->social_post_id
                ]);
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'SocialFeedReport.csv', $headers);
    }

    public static function onExportSocialFeedMediaReport($program_id, $start_date, $end_date)
    {
        $filename = storage_path('/csv/export/SocialFeedMediaReport.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Post ID',
            'Activity',
            'Content Type',
            'Media link'
        ];

        fputcsv($handle, $outputarray);

        $posts_data = SocialPost::whereBetween('created_at', [$start_date, $end_date])
            ->where('program_id', '=', $program_id)
            ->get();

        if (!empty($posts_data)) {
            foreach ($posts_data as $post) {
                foreach ($post->attachments as $attachment) {
                    fputcsv($handle, [
                        $post->id,
                        'Post',
                        $attachment->content_type,
                        $attachment->getPath()
                    ]);
                }
            }
        }

        $comments = SocialPostResponse::whereBetween('created_at', [$start_date, $end_date])
            ->where('response_type', '=', 'comment')
            ->get();

        if (!empty($comments)) {
            foreach ($comments as $comment) {
                if ($comment->attachment) {
                    fputcsv($handle, [
                        $comment->social_post_id,
                        'Comment',
                        $comment->attachment->content_type,
                        $comment->attachment->getPath()
                    ]);
                }
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'SocialFeedMediaReport.csv', $headers);
    }

    public static function onExportSocialFeedHashTagsReport($program_id, $start_date, $end_date)
    {
        $filename = storage_path('/csv/export/SocialFeedHashTagsReport.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'CRM',
            'Username',
            'Business Name',
            'Hashtag',
            'Date',
            'PostID',
        ];

        fputcsv($handle, $outputarray);

        $posts_data = SocialPost::whereBetween('created_at', [$start_date, $end_date])
            ->where('program_id', '=', $program_id)
            ->whereRaw('LENGTH(tags)>4')
            ->get();

        if (!empty($posts_data)) {
            foreach ($posts_data as $row) {
                foreach($row->tags as $tag) {
                    fputcsv($handle, [
                        $row->poster->crm,
                        $row->poster->username,
                        $row->poster->business_name,
                        $tag,
                        $row->created_at,
                        $row->id
                    ]);
                }
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'SocialFeedHashTagsReport.csv', $headers);
    }

    private function sendNotificationEmail($template, $toEmail, $subject, $vars = [])
    {
        if (!empty($toEmail)) {
            if (env('APP_ENV') == 'staging') {
                $toemails = ['hq@evtmarketing.com.au'];
            } elseif (env('APP_ENV') == 'local') {
                $toemails = ['mar.herko@gmail.com'];
            }
            else {
                $toemails = [$toEmail];
            }

            $template = new Template($template);
            $message = New Message();
            if (!empty($subject)) {
                $message->setSubject($subject);
            }

            $message->setFromEmail('noreply@xtendsystem.com');
            $message->setMergeVars($vars);

            foreach ($toemails as $toemail) {
                $recipient = new Recipient($toemail, null, Recipient\Type::TO);
                $recipient->setMergeVars($vars);
                $message->addRecipient($recipient);
            }

            MandrillTemplateFacade::send($template, $message);
        }
    }
}
