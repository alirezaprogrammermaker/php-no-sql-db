# php-no-sql-db
very simple and easy file database without need setting just include file


## start


```
<?php

require 'NoSqlDb.php';


// make table
nsd_make_table('users');

// insert data

$id = nsd_insert_data(
    'users',
    [
        'name'       => 'Alireza',
        'lastName'   => 'Programmer',
        'experience' => 99999999,
        'age'        => 24,
    ]
);

// select data
nsd_select_data(
    'users',
    [
        'id' => 1,
    ]
);

nsd_select_data(
    'users',
    [
        // for equal where u can use 2 array below
        [
            'id' => 1,
        ],
        [
            'name', '=', 'Alireza',
        ],
        // ...
        
        // other operators > < >= <= != == === 
        // sample in below
        [
            'experience' , '>' , 100
        ],
        [
            'age' ,'<=' , 24
        ]
    ]
);
// update data

nsd_update_data(
    'users',
    // where conditions 
    [
        'user_id' => 1
    ],
    
    // update columns ...
    [
        'lastName' => 'Power Programmer'
    ]
);

// delete data

nsd_delete_data(
    'users',
    [
        'id' => 1,
    ]
);




```
