<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;


class BookController extends Controller
{
        /**
     * @OA\GET(
     *      path="/v1/books",
     *      operationId="getAllBooks",
     *      tags={"Books"},
     *      security={{"apiAuth":{}}},
     *      summary="Get list of books",
     *      description="Returns list of books",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *     )
     *   ),
     *   @OA\Response(
     *       response=401,
     *        description="Unauthenticated",
     *   ),
     *   @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *    ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getAllBooks()
    {
      return BookResource::collection(Book::with('ratings')->paginate(25));
    }

    /**
     * @OA\Post(
     *      path="/v1/create/book",
     *      operationId="storeBook",
     *      tags={"Books"},
     *      summary="Store a given book",
     *      description="Store a given book",
     *      security={{"apiAuth":{}}},
     *      operationId="storeBook",
     * 
     *  @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="author",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *       response=200,
     *       description="Successfully created a book",
     *     @OA\MediaType(
     *        mediaType="application/json",
     *     )
     *   ),
     *   @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *    ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function storeBook(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'author' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $book = new Book;
        $book->user_id = $request->user()->id;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->save();

        return response()->json([
            'status'=>true,
            'message' => 'Successfully created a book'
        ], 200);
    }

    /**
     * @OA\GET(
     *      path="/v1/get/book/{id}",
     *      operationId="showGivenBook",
     *      tags={"Books"},
     *      summary="Get a given book by ID",
     *      description="Returns choosen book",
     *      security={{"apiAuth":{}}},
     *      @OA\Parameter(
     *         description="ID of book to fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     */
    public function showGivenBook(book $book)
    {
        return new BookResource($book);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function updateBook(Request $request, Book $book)

    // {

    //   // check if currently authenticated user is the owner of the book

    //   if ($request->user()->id !== $book->user_id) {

    //     return response()->json(['error' => 'You can only edit your own books.'], 403);

    //   }

    //   $book->update($request->only(['title','author', 'description']));

    //   return new BookResource($book);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function deleteBook(Request $request,Book $book)
    // {
    //   if($request->user()->id != $book->user_id){
    //     return response()->json(['error' => 'You can only delete your own books.'], 403);
    //   }
    //     $book ->delete();
    //     return response()->json(null,204);
    // }
}
