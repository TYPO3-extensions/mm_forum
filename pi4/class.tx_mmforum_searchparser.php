<?php
class tx_mmforum_searchparser {

	function parseSearchString($sStr) {

		$sStr = str_replace('(', ' ( ', $sStr);
		$sStr = str_replace(')', ' ) ', $sStr);
		$sStr = str_replace('+', ' + ', $sStr);
		$sStr = str_replace('-', ' - ', $sStr);

		$rawData = t3lib_div::trimExplode(' ',$sStr);
		foreach($rawData as $r)
			if(strlen($r)>0) $rawData2[] = $r;

		$curSWord = array();
		$sWords = array();
		$sWord_open = false;
		for($i=0; $i < count($rawData2); $i ++) {
			$r=$rawData2[$i];
			$r = strtolower($r);

			if($sWord_open) {
				$curSWord['word'].= ' '.$r;
				if(substr($r,-1,1)=='"') {
					$sWord_open = false;
					$curSWord['word'] = substr($curSWord['word'],0,strlen($curSWord['word'])-1);
					$sWords[] = $curSWord;
					$curSWord = array();
				}
				continue;
			}

			    if($r == '+' || $r == 'and') $curSWord['op'] = 'and';
			elseif($r == '-' || $r == 'not') $curSWord['op'] = 'not';
			elseif($r == '|' || $r == 'or')  $curSWord['op'] = 'or';
			else {
				if($r{0}=='+') {
					$curSWord['op'] = 'and';
					$r = substr($r,1);
				}
				if($r{0}=='-') {
					$curSWord['op'] = 'not';
					$r = substr($r,1);
				}
				if(!isset($curSWord['op'])) $curSWord['op'] = 'and';

				if($r{0}=='(') {
					$bracket .= substr($r,1);
					$open = 1; $closed = 0;
					while($open > $closed) {
						$i ++; if($i > count($rawData2)) break;
						$r = $rawData2[$i];
						if($r{0}=='(') $open ++;
						if($r{strlen($r)-1}==')') $closed ++;

						$bracket .= ' '.$r;
						if($open == $closed) {
							$bracket = substr($bracket,0,strlen($bracket)-1);
							$bracket = trim($bracket);
							break;
						}
					}

					$curSWord['subquery'] = $this->parseSearchString($bracket);
					$curSWord['braceContent'] = $bracket;
					$sWords[] = $curSWord;
					$curSWord = array();
					continue;
				}

				if($r{0}=='"') {
					$sWord_open = true;
					$r = substr($r,1);
					$curSWord['word'] = $r;
					$curSWord['special'] = 'no_index';

					if(substr($r,-1,1)=='"') {
						$sWord_open = false;
						$curSWord['word'] = substr($curSWord['word'],0,strlen($curSWord['word'])-1);
						$sWords[] = $curSWord;
						$curSWord = array();
					}
				}
				else {
					$curSWord['word'] = $r;
					$sWords[] = $curSWord;
					$curSWord = array();
				}
			}
		}

		return $sWords;
	}
}
?>
