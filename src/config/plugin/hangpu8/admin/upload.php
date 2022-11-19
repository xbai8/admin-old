<?php
return [
    'action'                    => '/hpadmin/SystemUpload/upload',
    'headers'                   => [
        'X-Requested-With'      => 'XMLHttpRequest',
    ],
    'data'                      => [
        'dir_name'              => 'system_name',
    ]
];
