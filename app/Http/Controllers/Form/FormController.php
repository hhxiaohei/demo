<?php

namespace App\Http\Controllers\Form;

use App\Models\Form;
use Forone\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\OperationLog;

class FormController extends BaseController
{
    const URI = 'form';
    const NAME = '表单组件';
    protected $uri = 'form';

    protected $rules = ['title' => 'required', 'type' => 'required', 'time' => 'required', 'contents' => 'required'];
    protected $message = ['title.required' => '请填写标题', 'type.required' => '请选择类型', 'time.required' => '请选择时间', 'contents.required' => '请填写内容'];

    public function index(Request $request)
    {
        $results = [
            'columns' => [
                ['ID', 'id'],
                ['标题', 'title'],
                ['类型', 'type'],
                ['Tag' , 'tag'],
                ['分级' , 'rate'],
                ['标注时间', 'time'],
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
        $paginate = Form::orderBy('created_at', 'desc');
        foreach ($data as $key => $value) {
            if ($key == 'keywords') {
                $paginate = $paginate->where('title', $value);
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
        Form::create([
            'title'    => $request->title,
            'type'     => $request->type,
            'time'     => $request->time,
            'contents' => $request->contents,
            'column'   => $request->column,
            'level'    => $request->level,
            'note'     => $request->note,
            'notetime' => $request->notetime,
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
        $data = Form::find($id);
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
        $data = Form::find($id);
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

        Form::find($id)->update([
            'title'    => $input['title'],
            'type'     => $input['type'],
            'time'     => $input['time'],
            'contents' => $input['contents'],
            'column'   => $input['column'],
            'level'    => $input['level'],
            'note'     => $input['note'],
            'notetime' => $input['notetime'],
        ]);

        return redirect()->to($return_url);
    }
}
