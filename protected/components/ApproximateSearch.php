<?php

/** 
 * An Approximate Search (Fuzzy Search) Algorithm using 
 * Levenshtein Distance (LD) Algorithm and 
 * Longest Common Substring (LCS) Algorithm
 */

class ApproximateSearch
{
    public $arrayObject;
    public $attribute;
    public $searchString;
    
    public $maxLD = 0.3;
    public $minLCS = 0.7;
    
    public $NOT_MATCH = 0;
    public $STR2_STARTS_WITH_STR1 = 1;
    public $STR2_CONTAINS_STR1 = 2;
    public $STR1_STARTS_WITH_STR2 = 3;
    public $STR1_CONTAINS_STR2 = 4;
    public $LEVENSHTEIN_DISTANCE_CHECK = 5;
    public $LONGEST_COMMON_SUBSTRING_CHECK = 6;
    
    function __construct($arrayObject, $attribute, $searchString)
    {
        $this->arrayObject = $arrayObject;
        $this->attribute = $attribute;
        $this->searchString = $searchString;
    }
    
    function setMaxLD($ld)
    {
        $this->maxLD = $ld;
    }
    
    function setMinLCS($lcs)
    {
        $this->minLCS = $lcs;
    }
    
    function search() 
    {
        $result = array();
        $search = strtolower($this->searchString);
        foreach ($this->arrayObject as $obj) {
            $found = false;
            foreach ($this->attribute as $attr) {
                if ($found || !isset($obj->{$attr})) {
                    continue;
                }
                $val = strtolower($obj->{$attr});
                if (!$val) {
                    continue;
                }
                $type = $this->NOT_MATCH;                
                if (strpos($search, $val) !== false && strpos($search, $val) == 0) {                    
                    $type = $this->STR2_STARTS_WITH_STR1;
                    $type_val = strlen($val);
                } elseif (strpos($search, $val) > 0) {
                    $type = $this->STR2_CONTAINS_STR1;
                    $type_val = strlen($val);
                } elseif (strpos($val, $search) !== false && strpos($val, $search) == 0) {                    
                    $type = $this->STR1_STARTS_WITH_STR2;
                    $type_val = strlen($val);
                } elseif (strpos($val, $search) > 0) {                    
                    $type = $this->STR1_CONTAINS_STR2;
                    $type_val = strlen($val);                    
                } elseif ($this->checkLD($ld = levenshtein($val, $search), $search)) {
                    $type = $this->LEVENSHTEIN_DISTANCE_CHECK;
                    $type_val = $ld / strlen($search);
                } else {                    
                    $lcs = $this->getLCS($val, $search);
                    $similar_percent = strlen($lcs) / strlen($search);                    
                    if ($similar_percent > $this->minLCS) {
                        $type = $this->LONGEST_COMMON_SUBSTRING_CHECK;
                        $type_val = strlen($lcs) / strlen($val) * (-1);                    
                    }
                }                
                if ($type != $this->NOT_MATCH) {
                    array_push ($result, array($obj, $attr, $type, $type_val));
                    $found = true;
                }                                 
            }
        }
        usort($result, array($this, 'sortArray'));
        return $result;
    }        
    
    private function checkLD($ld, $str)
    {
        $length = strlen($str);
        if ($ld/$length < $this->maxLD) {
            return true;
        }
        return false;
    }
    
    private function getLCS($string_1, $string_2)
    {
        $string_1_length = strlen($string_1);
        $string_2_length = strlen($string_2);
        $return          = '';
 
        if ($string_1_length === 0 || $string_2_length === 0){            
            return $return;
        }
        $longest_common_substring = array();        
        for ($i = 0; $i < $string_1_length; $i++){
            $longest_common_substring[$i] = array();
            for ($j = 0; $j < $string_2_length; $j++){
                $longest_common_substring[$i][$j] = 0;
            }
        }
        $largest_size = 0;
        for ($i = 0; $i < $string_1_length; $i++){
            for ($j = 0; $j < $string_2_length; $j++){                
                if ($string_1[$i] === $string_2[$j]){                    
                    if ($i === 0 || $j === 0){                        
                        $longest_common_substring[$i][$j] = 1;
                    } else {                         
                        $longest_common_substring[$i][$j] = $longest_common_substring[$i - 1][$j - 1] + 1;
                    }
                    if ($longest_common_substring[$i][$j] > $largest_size){                        
                        $largest_size = $longest_common_substring[$i][$j];                        
                        $return       = '';                        
                    }
                    if ($longest_common_substring[$i][$j] === $largest_size){                        
                        $return = substr($string_1, $i - $largest_size + 1, $largest_size);
                    }
                }                
            }
        }       
        return $return;
    }
    
    function sortArray($arr1, $arr2)
    {
        if ($arr1[2] == $arr2[2]) {
            if ($arr1[3] == $arr2[3]) {
                return 0;
            } else {
                return $arr1[3] < $arr2[3] ? -1 : 1;
            }
        } else {
            return $arr1[2] < $arr2[2] ? -1 : 1;
        }         
    }
}
?>
