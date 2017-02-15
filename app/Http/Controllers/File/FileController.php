<?php

namespace App\Http\Controllers\File;

use Illuminate\Http\Request;
use Forone\Controllers\BaseController;
use App\Http\Requests;
use App\Models\File;

class FileController extends BaseController
{
    const URI = 'files';
    const NAME = '文件管理';
    protected $uri = 'files';

    protected $rules = ['title' => 'required'];
    protected $message = ['title.required' => '请填写标题'];

    public function index(Request $request)
    {
        $results = [
            'columns' => [
                ['ID', 'id'],
                ['标题', 'title'],
                ['文件', 'file'],
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
        $paginate = File::orderBy('id', 'asc');
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
        File::create([
            'title'   => $request->title,
            'file'    => $request->file,
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
        $data = File::find($id);
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
        $data = File::find($id);
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

        File::find($id)->update([
            'title'   => $input['title'],
            'file'    => $input['file'],
        ]);

        return redirect()->to($return_url);
    }
}
