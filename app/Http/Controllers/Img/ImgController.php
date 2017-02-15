<?php

namespace App\Http\Controllers\Img;

use Illuminate\Http\Request;
use Forone\Controllers\BaseController;
use App\Http\Requests;
use App\Models\Img;

class ImgController extends BaseController
{
    const URI = 'imgs';
    const NAME = '图片管理';
    protected $uri = 'imgs';

    protected $rules = ['title' => 'required', 'img' => 'required'];
    protected $message = ['title.required' => '请填写标题', 'img.required' => '请上传图片'];

    public function index(Request $request)
    {
        $results = [
            'columns' => [
                ['ID', 'id'],
                ['标题', 'title'],
                ['图片', 'img'],
                ['创建时间', 'created_at'],
                [
                    '操作',
                    'buttons',
                    function ($data) {
                        $buttons = [
                            ['查看'],
                            ['编辑'],
                        ];
                        return $buttons;
                    },
                ],
            ],
        ];
        $data = $request->except(['page']);
        $paginate = Img::orderBy('id', 'asc');
        foreach ($data as $key => $value) {
            if ($key == 'keywords') {
                $paginate = $paginate->where('id', $value);
            }
        }
        $results['items'] = $paginate->paginate(10)->appends($data);
        return view(self::URI . ".index", compact('results'));
    }

    /**
     * 新建
     * @return \Illuminate\Support\Facades\View
     */
    public function create()
    {
        return $this->view(self::URI . '.create');
    }

    /**
     * 保存
     * @param CreateBannerRequest $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->message);
        Img::create([
            'title' => $request->title,
            'img'   => $request->img,
        ]);
        return redirect('admin/' . self::URI);
    }

    /**
     * Display the specified resource.
     * 查看
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $data = Img::find($id);
        if ($data) {
            return view(self::URI . "/show", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 编辑
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Img::find($id);
        if ($data) {
            return view(self::URI . "/edit", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Update the specified resource in storage.
     * 更新
     * @param  int $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, $this->rules, $this->message);
        $data = $request->all();
        $return_url = '';
        switch ($data['_method']) {
            case 'PATCH':
                $this->validate($request, ['id' => 'required']);
                $return_url = 'admin/' . self::URI;
                $id = $data['id'];
                break;
            case 'PUT':
                $this->validate($request, $this->rules);
                $return_url = 'admin/' . self::URI . '/' . $id;
                break;
        }
        $input = array_except($data, ['_method', '_token', 'id']);

        Img::find($id)->update([
            'title' => $input['title'],
            'img'  => $input['img'],
        ]);

        return redirect()->to($return_url);
    }
}
