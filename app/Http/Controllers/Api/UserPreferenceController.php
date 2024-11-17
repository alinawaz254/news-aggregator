<?php


namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserPreferenceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/preferences",
     *     operationId="getPreferences",
     *     tags={"User Preferences"},
     *     summary="Get the authenticated user's preferences",
     *     description="Retrieve the preferences of the authenticated user, including preferred news sources, categories, and authors.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="news_sources", type="array",
     *                     @OA\Items(type="string", example="BBC News")
     *                 ),
     *                 @OA\Property(property="categories", type="array",
     *                     @OA\Items(type="string", example="Technology")
     *                 ),
     *                 @OA\Property(property="authors", type="array",
     *                     @OA\Items(type="string", example="John Doe")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="User preferences retrieved successfully"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function getPreferences()
    {
        $user = Auth::user();
        $preferences = UserPreference::where('user_id', $user->id)->first();
        return $this->respond(200, $preferences, 'User preferences retrieved successfully', true);
    }


    /**
     * @OA\Post(
     *     path="/api/user/preferences",
     *     operationId="setPreferences",
     *     tags={"User Preferences"},
     *     summary="Set the authenticated user's preferences",
     *     description="Save or update the authenticated user's preferences for news sources, categories, and authors.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="news_sources", type="array",
     *                 @OA\Items(type="string", example="BBC News")
     *             ),
     *             @OA\Property(property="categories", type="array",
     *                 @OA\Items(type="string", example="Technology")
     *             ),
     *             @OA\Property(property="authors", type="array",
     *                 @OA\Items(type="string", example="John Doe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="news_sources", type="array",
     *                     @OA\Items(type="string", example="BBC News")
     *                 ),
     *                 @OA\Property(property="categories", type="array",
     *                     @OA\Items(type="string", example="Technology")
     *                 ),
     *                 @OA\Property(property="authors", type="array",
     *                     @OA\Items(type="string", example="John Doe")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="User preferences saved successfully"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Invalid input data"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function setPreferences(Request $request)
    {
        $request->validate([
            'news_sources' => 'nullable|array',
            'categories' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);

        $user = Auth::user();
        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            $request->only('news_sources', 'categories', 'authors')
        );

        return $this->respond(200, $preferences, 'User preferences saved successfully', true);
    }

    /**
     * @OA\Get(
     *     path="/api/user/personalized-feed",
     *     operationId="getPersonalizedFeed",
     *     tags={"User Preferences"},
     *     summary="Fetch a personalized news feed based on user preferences",
     *     description="Retrieve a paginated list of news articles that match the authenticated user's preferences for sources, categories, and authors.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Article Title"),
     *                         @OA\Property(property="content", type="string", example="Article content..."),
     *                         @OA\Property(property="source", type="string", example="BBC News"),
     *                         @OA\Property(property="category", type="string", example="Technology"),
     *                         @OA\Property(property="author", type="string", example="John Doe"),
     *                         @OA\Property(property="published_at", type="string", format="date-time", example="2024-11-14T16:34:06Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10)
     *             ),
     *             @OA\Property(property="message", type="string", example="Personalized feed retrieved successfully"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User preferences not set",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="User preferences not set"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function getPersonalizedFeed()
    {
        $user = Auth::user();
        $preferences = UserPreference::where('user_id', $user->id)->first();

        if (!$preferences) {
            return $this->respond(404, [], 'User preferences not set', false);
        }

        $articles = Article::whereIn('source', $preferences->news_sources)
            ->whereIn('category', $preferences->categories)
            ->whereIn('author', $preferences->authors)
            ->paginate(10);

        return $this->respond(200, $articles, 'Personalized feed retrieved successfully', true);
    }
}
