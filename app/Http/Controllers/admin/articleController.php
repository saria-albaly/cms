<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests;
use App\Http\Requests\CreatearticleRequest;
use App\Http\Requests\UpdatearticleRequest;
use App\Repositories\articleRepository;
use App\Models\article;
use Flash;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use DB;


class articleController extends Controller
{
    /** @var  articleRepository */
    private $articleRepository;

    public function __construct(articleRepository $articleRepo)
    {
        $this->articleRepository = $articleRepo;
    }

    /**
     * Display a listing of the article.
     *
     * @return Response
     */
    public function index(Request $request)
    {
//        if(auth()->user()->can('add users'))
//        {
            $articles = $this->articleRepository->all();
//        }

//        if (isset($request['user']))
//            $user_id=$request['user'];
//        else
//            $user_id=auth()->user()->id;
//        DB::enableQueryLog();
        return view('article.index')->with('articles', $articles);
    }

    /**
     * Show the form for creating a new article.
     *
     * @return Response
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * Store a newly created article in storage.
     *
     * @param CreatearticleRequest $request
     *
     * @return Response
     */
    public function store(CreatearticleRequest $request)
    {
       $input = $request->all();
        $car = $this->articleRepository->create($input);

        return redirect(route('article.index'));
}

    /**
     * Display the specified article.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('Article not found');

            return redirect(route('article.index'));
        }

        return view('article.show')->with('article', $article);
    }

    /**
     * Show the form for editing the specified article.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('article not found');

            return redirect(route('article.index'));
        }

        return view('article.edit')->with('article', $article);
    }

    /**
     * Update the specified article in storage.
     *
     * @param  int              $id
     * @param UpdatearticleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatearticleRequest $request)
    {
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('article not found');

            return redirect(route('admin.article.index'));
        }

        $article = $this->articleRepository->update($request->all(), $id);

        Flash::success('article updated successfully.');

        return redirect(route('article.index'));
    }

    /**
     * Remove the specified article from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            Flash::error('article not found');

            return redirect(route('article.index'));
        }

        $this->articleRepository->delete($id);

//        Flash::success('تم حذف الرسالة بنجاح');

        return redirect(route('admin.article.index'));
    }
}
