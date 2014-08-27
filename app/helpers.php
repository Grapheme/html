<?php

function is_image($filename){

    $is = @getimagesize($filename);
    if (!$is):
        return false;
    elseif (!in_array($is[2], array(1, 2, 3))):
        return false;
    else:
        return true;
    endif;
	
}
function calcProgressPercent($pages,$index = null){
		
	$countPages = [0,0,0,0];
	if(count($pages)):
		foreach($pages as $key => $value):
			if($value->progress >= 70):
				$countPages[0] += round($value->progress/100,2);
			elseif($value->progress >= 20):
				$countPages[1] += round($value->progress/100,2);
			elseif($value->progress > 0):
				$countPages[2] += round($value->progress/100,2);
			else:
				$countPages[3] += round($value->progress/100,2);
			endif;
		endforeach;
		$progress[0] = round($countPages[0]/$pages->count(),2)*100;
		$progress[1] = round($countPages[1]/$pages->count(),2)*100;
		$progress[2] = round($countPages[2]/$pages->count(),2)*100;
		$progress[3] = 100 - ($progress[0]+$progress[1]+$progress[2]);
	else:
		$progress = [0,0,0,0];
	endif;
	if(is_null($index)):
		return $progress;
	elseif(isset($progress[$index])):
		return $progress[$index];
	endif;
}