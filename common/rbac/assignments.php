<?php



$common_rules= [
    1 => [
        'admin',
    ],
    142 => [
        'backend',
        'map',
    ],
    144 => [
        'backend',
    ],
    304 => [
        'admin',
    ],
    165 => [
        'map',
    ],
    333 => [
        'map','admin'
    ],
   
];

$user = common\models\User::find()
->where(['email'=>'menedzerservis346@gmail.com']);
if($user->exists())
{
$additional=[
    $user->one()->id => [
        'manager'
    ],
];
$common_rules=$common_rules+$additional;
}

return $common_rules;
