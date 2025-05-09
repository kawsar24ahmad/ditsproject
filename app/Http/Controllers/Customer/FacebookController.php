<?php

namespace App\Http\Controllers\Customer;

use App\Models\FacebookPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class FacebookController extends Controller
{
    public function posts($id)
    {
        $page = FacebookPage::find($id);
        if (!$page) {
            return redirect()->back()->with('error', 'Page not found.');
        }

        $pageAccessToken = $page->page_access_token;
        $pageId = $page->page_id;

        // Step 1: Get posts (basic data only)
        $response = Http::withToken($pageAccessToken)
            ->get("https://graph.facebook.com/{$pageId}/posts", [
                'fields' => 'id,message,created_time,attachments{media_type,media,url,subattachments}'
            ]);

        $posts = $response->json()['data'] ?? [];

        $results = [];

        // Step 2: Loop and fetch insights, likes, comments, shares per post
        foreach ($posts as $post) {
            $postId = $post['id'];

            // Fetch insights
            $insightResponse = Http::withToken($pageAccessToken)
                ->get("https://graph.facebook.com/{$postId}/insights", [
                    'metric' => 'post_impressions'
                ]);

                // dd($insightResponse->json());
            $insightData = $insightResponse->json()['data'] ?? [];

            $reach = 0;
            $engaged = 0;
            foreach ($insightData as $item) {
                if ($item['name'] === 'post_impressions') {
                    $reach = $item['values'][0]['value'] ?? 0;
                }
                if ($item['name'] === 'post_engaged_users') {
                    $engaged = $item['values'][0]['value'] ?? 0;
                }
            }

            // Fetch likes/comments/shares
            $metaResponse = Http::withToken($pageAccessToken)
                ->get("https://graph.facebook.com/{$postId}", [
                    'fields' => 'likes.summary(true),comments.summary(true),shares',
                ]);

            $meta = $metaResponse->json();


            $mediaItems = [];
            if (isset($post['attachments']['data'])) {
                foreach ($post['attachments']['data'] as $attachment) {
                    $attachments = $attachment['subattachments']['data'] ?? [$attachment];
                    foreach ($attachments as $media) {
                        if (($media['media_type'] ?? '') !== 'video') {
                            $mediaItems[] = [
                                'type' => $media['media_type'] ?? '',
                                'media_url' => $media['media']['image']['src'] ?? '',
                                'link' => $media['url'] ?? '',
                            ];
                        }
                    }
                }
            }

            // Skip posts with no message and no image
            if (empty($post['message']) && empty($mediaItems)) {
                continue;
            }


            $results[] = [
                'id' => $postId,
                'message' => $post['message'] ?? '',
                'created_time' => $post['created_time'],
                'likes' => $meta['likes']['summary']['total_count'] ?? 0,
                'comments' => $meta['comments']['summary']['total_count'] ?? 0,
                'shares' => $meta['shares']['count'] ?? 0,
                'reach' => $reach,
                'engagement' => $engaged,
                'media' => $mediaItems,
            ];
        }

            // Manual Laravel pagination
        $currentPage = request()->get('page', 1);
        $perPage = 2;

        $collection = collect($results);
        $pagedData = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('facebook.index', ['results' => $pagedData]);
    }

    public function videos($id){
        $page = FacebookPage::findOrFail($id);
        $pageId = $page->page_id;
        $pageAccessToken = $page->page_access_token;
        // === 2. Get Videos ===
        $videoResponse = Http::withToken($pageAccessToken)
        ->get("https://graph.facebook.com/{$pageId}/videos", [
            'fields' => 'id,description,created_time,length,thumbnails,format,permalink_url,title,post_id'
        ]);

        $videos = $videoResponse->json('data', []);


        $videoResults = [];

        foreach ($videos as $video) {
        $videoId = $video['id'];


        $postId = $video['post_id'] ?? null;

        if ($postId) {
            if (!str_contains($postId, '_')) {
                $postId = "{$pageId}_{$postId}";
            }

            $postMetaResponse = Http::withToken($pageAccessToken)
                ->get("https://graph.facebook.com/{$postId}", [
                    'fields' => 'likes.summary(true),comments.summary(true),shares'
                ]);
            $postMeta = $postMetaResponse->json();
            // dd($postMeta);

            $likesCount = $postMeta['likes']['summary']['total_count'] ?? 0;
            $commentsCount = $postMeta['comments']['summary']['total_count'] ?? 0;
            $sharesCount = $postMeta['shares']['count'] ?? 0;
            // $sharesCount = $postMeta['shares']['count'] ?? 0;
            // ... likes, comments etc
        }

        $videoInsightsResponse = Http::withToken($pageAccessToken)
            ->get("https://graph.facebook.com/{$videoId}/video_insights", [
                'metric' => implode(',', [
                    'post_video_avg_time_watched',
                    'post_video_social_actions',
                    'post_video_view_time',
                    'post_impressions_unique',
                    'blue_reels_play_count',
                    'fb_reels_total_plays',
                    'fb_reels_replay_count',
                    'post_video_retention_graph',
                    'post_video_followers',
                ])
            ]);

        $videoInsights = $videoInsightsResponse->json('data', []);
        $metrics = [
            'avg_time_watched' => 0,
            'social_actions' => [],
            'view_time' => 0,
            'reach' => 0,
            'reels_play_count' => 0,
            'total_plays' => 0,
            'replay_count' => 0,
            'retention_graph' => [],
            'followers' => 0,
        ];

        foreach ($videoInsights as $item) {
            $value = $item['values'][0]['value'] ?? 0;
            switch ($item['name']) {
                case 'post_video_avg_time_watched':
                    $metrics['avg_time_watched'] = $value;
                    break;
                case 'post_video_social_actions':
                    $metrics['social_actions'] = $value;
                    break;
                case 'post_video_view_time':
                    $metrics['view_time'] = $value;
                    break;
                case 'post_impressions_unique':
                    $metrics['reach'] = $value;
                    break;
                case 'blue_reels_play_count':
                    $metrics['reels_play_count'] = $value;
                    break;
                case 'fb_reels_total_plays':
                    $metrics['total_plays'] = $value;
                    break;
                case 'fb_reels_replay_count':
                    $metrics['replay_count'] = $value;
                    break;
                case 'post_video_retention_graph':
                    $metrics['retention_graph'] = $value;
                    break;
                case 'post_video_followers':
                    $metrics['followers'] = $value;
                    break;
            }
        }



        $thumbnail = $video['thumbnails']['data'][0]['uri'] ?? null;

        $videoResults[] = array_merge([
            'type' => 'video',
            'id' => $videoId,
            'message' => $video['description'] ?? '',
            'created_time' => $video['created_time'],
            'likes' => $likesCount,
            'comments' => $commentsCount,
            'shares' => $sharesCount,
            'media' => [[
                'type' => 'video',
                'media_url' => "https://www.facebook.com/watch/?v={$videoId}",
                'thumbnail' => $thumbnail,
            ]],
        ], $metrics);
        }
            // Manual Laravel pagination
            $currentPage = request()->get('page', 1);
            $perPage = 1;

            $collection = collect($videoResults);
            $pagedData = new LengthAwarePaginator(
                $collection->forPage($currentPage, $perPage),
                $collection->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return view('facebook.videos', ['videoResults' => $pagedData]);

        // return view('facebook.videos', compact('videoResults'));
    }
}
