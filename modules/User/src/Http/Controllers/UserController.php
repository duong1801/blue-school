<?php
namespace Modules\User\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\src\Repositories\UserRepository;

class UserController extends Controller {
    protected $userRepo;
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function index()
    {
      $pageTitle = trans('user::custom.title');
      $users = $this->userRepo->getProducts(config('config.pagination'));
      return view('user::list',compact('pageTitle','users'));
    }
    public function create()
    {
      
    }
}
