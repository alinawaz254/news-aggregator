<?php


namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     operationId="getArticles",
     *     tags={"Articles"},
     *     summary="Fetch articles with pagination and filtering options",
     *     description="Retrieve a list of articles with support for pagination and filtering by keyword, category, source, and date range.",
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword to search for in article title or content",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by article category, possible values: NewsAPI, New York Times, & BBC News",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter by article source",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date for filtering articles (format: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date for filtering articles (format: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
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
     *                         @OA\Property(property="source", type="string", example="NewsAPI"),
     *                         @OA\Property(property="category", type="string", example="General"),
     *                         @OA\Property(property="published_at", type="string", format="date-time", example="2024-11-14T16:34:06Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=50),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10)
     *             ),
     *             @OA\Property(property="message", type="string", example="Articles retrieved successfully"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Invalid parameters provided"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function getArticles(Request $request)
    {
        $query = Article::query();

        // Apply filters if any
        if ($request->has('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%')
                ->orWhere('content', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('published_at', [$request->start_date, $request->end_date]);
        }

        // Paginate results
        $articles = $query->paginate(10);

        return $this->respond(200, $articles, 'Articles retrieved successfully', true);

    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     operationId="getArticle",
     *     tags={"Articles"},
     *     summary="Fetch a single article's details",
     *     description="Retrieve details of a specific article by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Article Title"),
     *                 @OA\Property(property="content", type="string", example="Article content..."),
     *                 @OA\Property(property="source", type="string", example="NewsAPI"),
     *                 @OA\Property(property="category", type="string", example="General"),
     *                 @OA\Property(property="published_at", type="string", format="date-time", example="2024-11-14T16:34:06Z")
     *             ),
     *             @OA\Property(property="message", type="string", example="Article retrieved successfully"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Article not found"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function getArticle($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return $this->respond(404, [], 'Article not found', false);

        }
        return $this->respond(200, $article, 'Article retrieved successfully', true);
    }
}
