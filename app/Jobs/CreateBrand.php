<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\TrackProduct;
use App\Models\TrackBrand;


class CreateBrand extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $address;
    protected $industry_id;
    protected $item;
    protected $admin;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($address , $industry_id , $item , $admin)
    {
        $this->address = $address;
        $this->industry_id = $industry_id;
        $this->item = $item;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $brand = TrackBrand::create([
            'enabled'     => 0,
            'industry_id' => $this->industry_id,
            'name'        => '养殖户' . $this->item[3],
            'address'     => $this->address ? $this->address : '',
            'admin_id'    => $this->admin->id,
        ]);
        $this->createProduct($this->item, $brand);
        \Log::info($this->item[3]);
    }

    private function createProduct($item, $brand)
    {
        $product = TrackProduct::where('our_source_code', '=', (int)$item[4])->first();
        if (!$product) {
            TrackProduct::create([
                'enabled'         => 0,
                'name'            => '盐池滩羊',
                'num'             => 1,
                'birthday'        => !empty($item[9]) ? $item[9] : $item[8].'-01-01',
                'brand_id'        => $brand->id,
                'config'          => [
                    '产地'   => $item[0] == $item[2] ? $item[0] : $item[0] . $item[2],
                    '溯源编码' => $item[5] ? $item[5] : $item[4],
                ],
                'sex'             => $item[7] == '母' ? 1 : 0,
                'our_source_code' => is_numeric($item[4]) ? number_format($item[4], 0, '', '') : $item[4],
                'source_code'     => (int)$item[5],
                'extra'           => $item[6] ? $item[6] : '',
                'cover'           => 'dyyy/IMG_' . $item[6] . '.jpg',
                'banner'         => 'dyyy/IMG_' . $item[6] . '.jpg'
            ]);
        }else{
            $product->brand_id = $brand->id;
            $product->save();
        }

    }
}
