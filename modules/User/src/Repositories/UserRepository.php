<?php

namespace Modules\User\src\Repositories;

use App\Repositories\BaseRepository;
use Modules\User\src\Repositories\UserRepositoryInterface;
use Modules\User\src\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getProducts()
    {
        return $this->model->all();
    }
}
