<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Topic;
use App\Services\YouTubeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * @author Truong
     */
    protected YouTubeService $youtubeService;

    public function __construct(YouTubeService $youtubeService)
    {
        $this->youtubeService = $youtubeService;

        // Policy tự động cho show/update/destroy; các action còn lại tự authorize theo Topic/Course
        $this->authorizeResource(Lesson::class, 'lesson', [
            'except' => ['index', 'store', 'upload', 'reorder'],
        ]);
    }

    /**
     * GET /admin/topics/{topic}/lessons
     * Query: page, per_page, search
     */
    public function index(Request $request, Topic $topic): JsonResponse
    {
        $this->authorize('view', $topic->course);

        $q = $topic->lessons()->orderBy('order')->orderBy('id');

        if ($request->filled('search')) {
            $kw = (string) $request->query('search');
            $q->where('title', 'like', "%{$kw}%");
        }

        $perPage = (int) $request->integer('per_page', 50);
        return response()->json($q->paginate($perPage));
    }

    /**
     * POST /admin/topics/{topic}/lessons
     * Body: { title, content?, order, video_url?, video_id? }
     * (tạo lesson metadata-only, không upload file)
     */
    public function store(Request $request, Topic $topic): JsonResponse
    {
        $this->authorize('update', $topic->course);

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['nullable', 'string'],
            'order'        => ['required', 'integer', 'min:1'],
            'video_url'    => ['nullable', 'url'],
            'video_id'     => ['nullable', 'string', 'max:255'],
            'video_provider' => ['nullable', 'string', 'in:youtube'],
        ]);

        // Nếu có video_url YouTube mà chưa có video_id -> parse
        if (empty($data['video_id']) && !empty($data['video_url'])) {
            $parsed = YouTubeService::parseVideoId($data['video_url']);
            if ($parsed) {
                $data['video_id'] = $parsed;
                $data['video_provider'] = 'youtube';
            }
        }

        $lesson = $topic->lessons()->create([
            'title'          => $data['title'],
            'content'        => $data['content'] ?? null,
            'order'          => $data['order'],
            'video_provider' => $data['video_provider'] ?? null,
            'video_id'       => $data['video_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'created',
            'data'    => $lesson->only(['id', 'title', 'content', 'order', 'topic_id', 'video_provider', 'video_id']),
        ], 201);
    }

    /**
     * POST /admin/topics/{topic}/lessons/upload
     * Body (multipart): title, content?, order?, file(video: mp4/mov/mkv)
     * Upload lên YouTube qua YouTubeService rồi lưu record.
     */
    public function upload(Request $request, Topic $topic): JsonResponse
    {
        $this->authorize('update', $topic->course);

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'order'   => ['nullable', 'integer', 'min:1'],
            'file' => [
                'required',
                'file',
                'mimes:mp4,mov,mkv',
                'max:2048000'
            ],
        ]);

        // ===== Xử lý order =====
        if (!isset($data['order'])) {
            $data['order'] = ($topic->lessons()->max('order') ?? 0) + 1;
        } else {
            $newOrder = $data['order'];

            // Kiểm tra trùng
            $exists = $topic->lessons()->where('order', $newOrder)->exists();
            if ($exists) {
                return response()->json([
                    'message' => 'Thứ tự đã tồn tại trong topic này.',
                    'errors'  => ['order' => ['Thứ tự đã tồn tại trong topic này.']]
                ], 422);
            }

            // Nếu không trùng thì dịch các lesson sau xuống
            $topic->lessons()
                ->where('order', '>=', $newOrder)
                ->increment('order');
        }

        $file = $request->file('file');

        try {
            $videoId = $this->youtubeService->uploadVideo(
                $file,
                $data['title'],
                $data['content'] ?? ''
            );

            if (!$videoId) {
                return response()->json(['message' => 'Upload video lên YouTube thất bại.'], 500);
            }

            $lesson = $topic->lessons()->create([
                'title'          => $data['title'],
                'content'        => $data['content'] ?? null,
                'order'          => $data['order'],
                'video_provider' => 'youtube',
                'video_id'       => $videoId,
            ]);

            $embedUrl = "https://www.youtube-nocookie.com/embed/{$videoId}?rel=0&modestbranding=1&playsinline=1";

            return response()->json([
                'message'  => 'created',
                'videoId'  => $videoId,
                'embedUrl' => $embedUrl,
                'data'     => $lesson->only(['id', 'title', 'content', 'order', 'topic_id', 'video_provider', 'video_id']),
            ], 201);
        } catch (\Exception $e) {
            Log::error('YouTube Upload Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $status = in_array($e->getCode(), [400, 401, 403, 404]) ? $e->getCode() : 500;

            return response()->json([
                'message' => $e->getMessage() ?: 'Đã xảy ra lỗi khi upload video.'
            ], $status);
        }
    }

    /**
     * GET /admin/lessons/{lesson}
     * Trả chi tiết lesson; nếu YouTube thì kèm embed_url.
     */
    public function show(Lesson $lesson): JsonResponse
    {
        $data = $lesson->only([
            'id',
            'title',
            'content',
            'order',
            'topic_id',
            'video_provider',
            'video_id',
            'created_at',
            'updated_at'
        ]);
        $data['embed_url'] = ($lesson->video_provider === 'youtube' && $lesson->video_id)
            ? "https://www.youtube-nocookie.com/embed/{$lesson->video_id}?rel=0&modestbranding=1&playsinline=1"
            : null;

        return response()->json(['data' => $data]);
    }

    /**
     * PATCH /admin/lessons/{lesson}
     * Cập nhật metadata lesson (không xử lý upload tại đây).
     */
    public function update(Request $request, Lesson $lesson): JsonResponse
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['nullable', 'string'],
            'order'          => ['required', 'integer', 'min:1'],
            'video_url'      => ['nullable', 'url'],
            'video_id'       => ['nullable', 'string', 'max:255'],
            'video_provider' => ['nullable', 'string', 'in:youtube'],
        ]);

        if (empty($data['video_id']) && !empty($data['video_url'])) {
            $parsed = YouTubeService::parseVideoId($data['video_url']);
            if ($parsed) {
                $data['video_id'] = $parsed;
                $data['video_provider'] = 'youtube';
            }
        }

        unset($data['video_url']); // không lưu trực tiếp url

        $lesson->update($data);

        return response()->json([
            'message' => 'updated',
            'data'    => $lesson->only(['id', 'title', 'content', 'order', 'topic_id', 'video_provider', 'video_id']),
        ]);
    }

    /**
     * DELETE /admin/lessons/{lesson}
     */
    public function destroy(Lesson $lesson): JsonResponse
    {
        // Nếu trước đây có lưu file local (video_path) thì xoá file
        if (!empty($lesson->video_path)) {
            Storage::disk('public')->delete($lesson->video_path);
        }

        $lesson->delete();

        return response()->json(['message' => 'deleted']);
    }

    /**
     * PATCH /admin/topics/{topic}/lessons/reorder
     * Body: { ids: number[] } – danh sách id theo thứ tự mới (1-based).
     */
    public function reorder(Request $request, Topic $topic): JsonResponse
    {
        $this->authorize('update', $topic->course);

        $data = $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct'],
        ]);

        $ids = $data['ids'];

        // Đảm bảo tất cả ids thuộc cùng topic
        $count = Lesson::where('topic_id', $topic->id)->whereIn('id', $ids)->count();
        if ($count !== count($ids)) {
            return response()->json(['message' => 'Một hoặc nhiều lesson không thuộc topic này.'], 422);
        }

        DB::transaction(function () use ($ids, $topic) {
            foreach ($ids as $index => $id) {
                Lesson::where('id', $id)
                    ->where('topic_id', $topic->id)
                    ->update(['order' => $index + 1]);
            }
        });

        return response()->json(['message' => 'reordered']);
    }
}
