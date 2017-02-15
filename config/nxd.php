<?php

function getDebugId($id)
{
    $ids = [
        'oTQT5jpZ9dIBshDoTLx5J65afj-4'=>'odryWw8e7D2iIP2cTsxWwxkYg9p8', //
        'oTQT5jgiVUEU2z7uSfpBTt7_qbBM'=>'odryWw4vOxs9th1p3J8cmKvHzFpM',  //李建平
        'oTQT5jlAbzPK_EuBpsKf9_OK5yjI'=>'odryWw8ysiP4NB-x0AMFo5GvbRis',
        'oTQT5jqs6hD5hE97De1SZJhG-4cI'=>'odryWw5PC1yES2_hfX3dkVik6dkI',
        'oTQT5jrVUoo9FfFi5cnpgdpg0g0M'=>'odryWw-5ihf5EWxetaCmoAXc9rSg', //高宁
        'oTQT5jin-TOn60VFSLOI-x6pJqak' => 'odryWw-mZEV6spse99JaxaZ6pzM0',  //久州

        'oTQT5jm2NruGovl_ygU7hvDHvMuY'=>'odryWw0UAq3k9fv5qR9yDxwY5Rqo', //章庭
        'oTQT5jqfv7dUWSZyCephLNjr4Y9Y'=>'odryWw5fcukYKoGIR-lBICzg10rE', //杜萌萌
        'oTQT5jkRRcF6V37U5EG-nufl8O2Y'=>'odryWw1qvVC8wJj6_xcyY7Yg9Il0', //王艺斌
        'oTQT5jmEV-ItYsbZukL8Q2sz0c4k'=>'odryWw1MOesjlxw39FzZxSW-d59A', //张宏毅
    ];
    if (isset($ids[$id])) {
        return $ids[$id];
    }
}

return [
    'hashid_salt' => env('SALT', 'nxdai'),
    'hashid_len'  => env('HASH_ID_LEN', 4),
    'order_hashid_salt' => 'nxstorewechatNoSalt',
    'order_hashid_len' => 15,
    'alphabet' => '1234567890abcdef',
];