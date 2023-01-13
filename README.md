# php-no-sql-db
Very simple and easy file database without need setting just include file


## Start with me 😁

### Include The Database File
```

require 'NoSqlDb.php';

```

### Make Table

```

nsd_make_table('users');

```
### Insert Data
#### automatic generate columns
##### - id
##### - changed_at
##### - created_at
```

$id = nsd_insert_data(
    'users',
    [
        'name'       => 'Alireza',
        'lastName'   => 'Programmer',
        'experience' => 99999999,
        'age'        => 24,
    ]
);

```

### Select Data

```

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

```

### Update Data
#### automatic update column
##### - changed_at
```

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

```

### Delete Data

```

nsd_delete_data(
    'users',
    [
        'id' => 1,
    ]
);

```

### Setting

##### Change table address
```
define('tables_dir' , 'tables/');
```
##### Change date time format for columns _created_at_ and _changed_at_
```
define('date_format' , 'Y-m-d H:i:s');
```
