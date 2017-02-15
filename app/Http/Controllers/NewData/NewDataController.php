<?php

namespace App\Http\Controllers\Newdata;

use App\Models\Data;
use Forone\Controllers\BaseController;
use Illuminate\Http\Request;
use Forone\Models\Button;
use Maatwebsite\Excel\Facades\Excel;

class NewDataController extends BaseController
{
    const URI = 'newdata';
    const NAME = '数据列表';
    protected $uri = 'newdata';

    public function index(Request $request)
    {
        $results = [
            'columns' => [
                ['ID', 'id'],
                ['名字', 'name'],
                ['Tag', 'tag'],
                ['年龄', 'age'],
                ['城市', 'city'],
                ['类型', 'type'],
                ['等级', 'rate'],
                ['ICON', 'flag'],
                [
                    '操作',
                    'buttons',
                    function ($data) {
                        $buttons = [];
                        if ($data->enabled) {
                            array_push($buttons, ['禁用']);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'POST',
                                    'id'     => null,
                                    'class'  => 'btn-warning',
                                ],
                            ]);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'GET',
                                    'id'     => null,
                                    'class'  => 'btn btn-default btn-info waves-effect',
                                ],
                            ]);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'GET',
                                    'id'     => null,
                                    'class'  => 'btn btn-default btn-dark waves-effect',
                                ],
                            ]);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'GET',
                                    'id'     => null,
                                    'class'  => 'btn btn-default',
                                ],
                            ]);
                        } else if ($data->id % 4 == 0) {
                            array_push($buttons, ['启用']);
                        } else if ($data->id % 5 == 0) {
                            array_push($buttons, ['启用']);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'GET',
                                    'id'     => null,
                                    'class'  => 'btn btn-default btn-danger',
                                ],
                            ]);
                        } else {
                            array_push($buttons, ['启用']);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'POST',
                                    'id'     => null,
                                ],
                            ]);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'POST',
                                    'id'     => null,
                                    'class'  => 'btn btn-default btn-info',
                                ],
                            ]);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'GET',
                                    'id'     => null,
                                    'class'  => 'btn btn-default btn-danger',
                                ],
                            ]);
                            array_push($buttons, [
                                [
                                    'name'   => '按钮',
                                    'uri'    => '',
                                    'method' => 'GET',
                                    'id'     => null,
                                    'class'  => 'btn btn-default',
                                ],
                            ]);
                        }
                        return $buttons;
                    },
                ],
            ],
        ];
        $data = $request->except(['page']);
        $paginate = Data::orderBy('id', 'asc');
        foreach ($data as $key => $value) {
            if ($key == 'keywords') {
                $paginate = $paginate->where('email', $value)
                    ->orWhere('name', 'like' , '%'.$value.'%')
                    ->orWhere('city', $value);
            } elseif ($key == 'begin') {
                $paginate->where('created_at', '>=', $value);
            } elseif ($key == 'end') {
                $paginate->where('created_at', '<=', $value);
            } else {
                $paginate = $paginate->where($key, '=', $value);
            }
        }
        $filters = [
            'type'    => array_merge([['label' => '选择类型', 'value' => '']], select_label_trans(Data::$type)),
            'enabled' => array_merge([['label' => '是否开启', 'value' => '']], select_label_trans(Data::$enabled)),
        ];
        $results['filters'] = $filters;
        $results['items'] = $paginate->paginate(10)->appends($data);
        $results['buttons'] = [
            new Button('&#43; 导出Excel', route('admin.newdata.export'),
                ['class' => 'btn-success']),
        ];
        return view(self::URI . ".index", compact('results'));
    }

    public function export()
    {
        $cellData = [
            ['名称', '邮箱', '年龄', '城市', '创建时间'],
        ];
        $datas = Data::get();
        foreach ($datas as $data) {
            array_push($cellData, [$data->name, $data->email, $data->age, $data->city, $data->created_at]);
        }
        Excel::create('导出数据', function ($excel) use ($cellData) {
            $excel->sheet('1', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
        return false;
    }

    public function store()
    {
        return redirect()->to('admin/' . self::URI);
    }

    /**
     * Update the specified resource in storage.
     * 更新
     * @param  int $id
     * @return Response
     */
    public function update($id, Request $request)
    {
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
        if ($input['enabled'] == true) {
            $input['enabled'] = 1;
        } else {
            $input['enabled'] = 0;
        }
        Data::find($id)->update($input);

        return redirect()->to($return_url);
    }
}
