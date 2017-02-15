<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\OperationLog;
use Forone\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OperationLogsController extends BaseController
{

    protected $rules = [
    ];

    const URI = 'operations';
    const NAME = '操作日志';

    function __construct()
    {
        parent::__construct();
        view()->share('page_name', self::NAME);
        view()->share('uri', self::URI);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $results = [
            'columns' => [
                [
                    'method',
                    'methodName',
                ],
                ['IP', 'ip'],
                ['时间', 'created_at'],
                ['操作人', 'admin.name'],
                [
                    '操作',
                    'buttons',
                    100,
                    function ($data) {
                        $buttons = [
                            ['查看']
                        ];

                        return $buttons;
                    }
                ],
            ]
        ];
        $data = $request->except(['page']);
        $paginate = OperationLog::with('admin')->orderBy('created_at', 'desc');
        foreach ($data as $key => $value) {
            if ($key == 'keywords') {
                $paginate = $paginate->where('ip', $value);
            } else {
                $paginate = $paginate->where($key, $value);
            }
        }
        $filters = [
            'method' => array_merge([['label' => '选择方法', 'value' => '']], select_label_trans(OperationLog::REMAP)),
        ];
        $results['items'] = $paginate->paginate(10)->appends($data);
        $results['filters'] = $filters;
        return view(self::URI . '.index', compact("results"));
    }

    public function records()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('red-envelop-types/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        OperationLog::create($request->all());

        return Redirect::route('admin.red-envelop-types.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $data = OperationLog::find($id);
        if ($data) {
            return view(self::URI . "/show", compact('data'));
        } else {
            return $this->redirectWithError('数据未找到');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = OperationLog::find($id);
        if ($data) {
            return view("red-envelop-types/edit", compact('data'));
        } else {
            return redirect()->to($this->getRedirectUrl())
                ->withErrors(['default' => '未找到数据'], $this->errorBag());
        }
    }

    /**
     * Update the specified resource in storage.
     *
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
                $return_url = 'admin/red-envelop-types';
                $id = $data['id'];
                break;
            case 'PUT':
                $this->validate($request, $this->rules);
                $return_url = 'admin/red-envelop-types/' . $id;
                break;
        }
        $input = array_except($data, ['_method', '_token', 'id']);
        OperationLog::find($id)->update($input);

        return redirect()->to($return_url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
