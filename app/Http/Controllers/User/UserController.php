<?php
namespace App\Http\Controllers\User;

use Forone\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
class UserController extends BaseController
{
    const URI = 'user';
    const NAME = '用户管理';
    protected $uri = 'user';

    public function index(Request $request){

        $results = [
            'columns' => [
                ['会员ID', 'id'],
                ['会员名称', 'name'],
                ['会员邮箱', 'email'],
                ['最近登录', 'updated_at'],
                ['创建时间', 'created_at'],
            ]
        ];
        $data = $request->except(['page']);
        $paginate = User::orderBy('created_at', 'desc');
        foreach ($data as $key => $value) {
            if ($key == 'keywords') {
                $paginate = $paginate->where('id', $value);
            }
        }
        $results['items'] = $paginate->paginate(10)->appends($data);

        return view(self::URI . ".index", compact('results'));
    }


    public function show()
    {

    }

    public function orders()
    {


    }
}