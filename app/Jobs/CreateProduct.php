<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\TrackProduct;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateProduct extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $item;
    protected $brand;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($item, $brand)
    {
        $this->item = $item;
        $this->brand = $brand;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = TrackProduct::where('our_source_code', '=', (int)$this->item[4])->first();
        if (!$product) {
            TrackProduct::create([
                'enabled'         => 0,
                'name'            => '盐池滩羊',
                'num'             => 1,
                'birthday'        => !empty($this->item[9]) ? $this->item[9] : $this->item[8].'-01-01',
                'brand_id'        => $this->brand->id,
                'config'          => [
                    '产地'   => $this->item[0] == $this->item[2] ? $this->item[0] : $this->item[0] . $this->item[2],
                    '溯源编码' => $this->item[5] ? $this->item[5] : $this->item[4],
                ],
                'sex'             => $this->item[7] == '母' ? 1 : 0,
                'our_source_code' => is_numeric($this->item[4]) ? number_format($this->item[4], 0, '', '') : $this->item[4],
                'source_code'     => (int)$this->item[5],
                'extra'           => $this->item[6] ? $this->item[6] : '',
                'cover'           => 'dyyy/IMG_' . $this->item[6] . '.jpg',
                'banner'         => 'dyyy/IMG_' . $this->item[6] . '.jpg'
            ]);
        }else{
            $product->brand_id = $this->brand->id;
            $product->save();
        }
    }
}
