<?php

//THis class is written by Hamidreza azhdar

class convert
{

var $number = array (

'0' => 'و' ,

'1' => 'يك' ,
'2' => 'دو' ,
'3' => 'سه' ,
'4' => 'چهار' ,
'5' => 'پنج' ,
'6' => 'شش' ,
'7' => 'هفت' ,
'8' => 'هشت' ,
'9' => 'نه' ,


'11' => 'يازده' ,
'12' => 'دوازده' ,
'13' => 'سيزده' ,
'14' => 'چهارده' ,
'15' => 'پانزده' ,
'16' => 'شانزده' ,
'17' => 'هفده' ,
'18' => 'هجيده' ,
'19' => 'نوزده' ,

'10' => 'ده' ,
'20' => 'بيست' ,
'30' => 'سی' ,
'40' => 'چهل' ,
'50' => 'پنجاه' ,
'60' => 'شصت' ,
'70' => 'هفتاد' ,
'80' => 'هشتاد' ,
'90' => 'نود' ,

'100' => 'يكصد' ,
'200' => 'دويست' ,
'300' => 'سيصد' ,
'400' => 'چهارصد' ,
'500' => 'پانصد' ,
'600' => 'ششصد' ,
'700' => 'هفتصد' ,
'800' => 'هشتصد' ,
'900' => 'نهصد' ,


't' => 'هزار' ,
'm' => 'ميليون' ,
'b' => 'ميليارد' ,
'tr' => 'تريليون' ,

);



function calc1($num)
{

return $this->number[$num] ;

}//End of function calc1



function calc2($num)
{

if (fmod($num, 10) == 0 || ($num >= 11 && $num <= 19))
{
$str =  $this->number[$num] ;
}
else
{

$first = fmod($num, 10);
$second = floor(($num / 10));
$second *= 10  ;


$str = $this->number[$second].'&nbsp;'.$this->number[0].'&nbsp;'.$this->number[$first];

}

return $str ;


}//End of function calc2





function calc3($num)
{


if (fmod($num, 100) == 0)
{
$str =  $this->number[$num] ;
}
else
{

$first = fmod($num, 100);

$second = floor(($num / 100));
$second *= 100  ;



if ($first >= 1 && $first <= 9)

{

$first_str = $this->calc1($first) ;

}
else

{

$first_str = $this->calc2($first) ;

}



$str = $this->number[$second].'&nbsp;'.$this->number[0].'&nbsp;'.$first_str;

}

return $str ;



} //End of function calc3




function maincalc($num)

{

if ($num >= 1 && $num <= 9)
{
$str = $this->calc1($num) ;
}

if ($num >= 10 && $num <= 99)
{
$str = $this->calc2($num) ;
}

if ($num >= 100 && $num <= 999)
{
$str = $this->calc3($num) ;
}

return $str ;

} // End Of Function maincalc










function calc6($num)
{


if (fmod($num, 1000) == 0)
{

$num2 = $num/1000 ;
$num3 = $this->maincalc($num2) ;


$str =  $num3.'&nbsp;'.$this->number['t'] ;
}
else

{

$first = fmod($num, 1000);
$first_str = $this->maincalc($first) ;


$second = floor(($num / 1000));
$second_str = $this->maincalc($second) ;

$str = $second_str.'&nbsp;'.$this->number['t'].'&nbsp;'.$this->number[0].'&nbsp;'.$first_str ;



}


return $str ;

} //End of function calc6




function calc9($num)
{


if (fmod($num, 1000000) == 0)
{

$num2 = $num/1000000 ;
$num3 = $this->maincalc($num2) ;


$str =  $num3.'&nbsp;'.$this->number['m'] ;
}
else

{

$first = fmod($num, 1000000);



if ($first >= 1 && $first <= 999)
{
$first_str = $this->maincalc($first) ;
}
else
{
$first_str = $this->calc6($first) ;
}


$second = floor(($num / 1000000));
$second_str = $this->maincalc($second) ;

$str = $second_str.'&nbsp;'.$this->number['m'].'&nbsp;'.$this->number[0].'&nbsp;'.$first_str ;



}


return $str ;

} //End of function calc9




function calc12($num)
{


if (fmod($num, 1000000000) == 0)
{

$num2 = $num/1000000000 ;
$num3 = $this->maincalc($num2) ;


$str =  $num3.'&nbsp;'.$this->number['b'] ;
}
else

{

$first = fmod($num, 1000000000);
if ($first >= 1 && $first <= 999)
{
$first_str = $this->maincalc($first) ;
}
elseif($first >= 1000 && $first <= 999999)
{
$first_str = $this->calc6($first) ;
}
else
{
$first_str = $this->calc9($first) ;
}


$second = floor(($num / 1000000000));
$second_str = $this->maincalc($second) ;

$str = $second_str.'&nbsp;'.$this->number['b'].'&nbsp;'.$this->number[0].'&nbsp;'.$first_str ;



}


return $str ;

} //End of function calc12




function calc15($num)
{


if (fmod($num, 1000000000000) == 0)
{

$num2 = $num/1000000000000 ;
$num3 = $this->maincalc($num2) ;


$str =  $num3.'&nbsp;'.$this->number['tr'] ;
}
else

{

$first = fmod($num, 1000000000000);
if ($first >= 1 && $first <= 999)
{
$first_str = $this->maincalc($first) ;
}
elseif($first >= 1000 && $first <= 999999)
{
$first_str = $this->calc6($first) ;
}
elseif($first >= 1000000 && $first <= 999999999)
{
$first_str = $this->calc9($first) ;
}
else
{
$first_str = $this->calc12($first) ;
}


$second = floor(($num / 1000000000000));
$second_str = $this->maincalc($second) ;

$str = $second_str.'&nbsp;'.$this->number['tr'].'&nbsp;'.$this->number[0].'&nbsp;'.$first_str ;



}


return $str ;

} //End of function calc15




function finalcalc($num)

{
if ($num >= 1 && $num <= 9)
{
$str = $this->calc1($num) ;
}

elseif ($num >= 10 && $num <= 99)
{
$str = $this->calc2($num) ;
}

elseif ($num >= 100 && $num <= 999)
{
$str = $this->calc3($num) ;
}

elseif ($num >= 1000 && $num <= 999999)
{
$str = $this->calc6($num) ;
}

elseif ($num >= 1000000 && $num <= 999999999)
{
$str = $this->calc9($num) ;
}

elseif ($num >= 1000000000 && $num <= 999999999999)
{
$str = $this->calc12($num) ;
}


elseif ($num >= 1000000000000 && $num <= 999999999999999)
{
$str = $this->calc15($num) ;
}



return $str ;


}


}// End Of Class




?>
