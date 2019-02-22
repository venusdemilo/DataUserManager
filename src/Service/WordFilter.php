<?php
namespace App\Service;

class WordFilter
{
  private $patterns = array();
  private $replacements=array();





    public function __construct()
    {
      $this->patterns[0]='/ê/';
      $this->patterns[1]='/é/';
      $this->patterns[2]='/è/';
      $this->patterns[3]='/à/';
      $this->patterns[4]='/ù/';
      $this->patterns[5]='/î/';
      $this->patterns[6]='/ê/';
      $this->patterns[7]='/ü/';
      $this->patterns[8]='/ô/';
      $this->patterns[9]='/ç/';
      $this->patterns[10]='/â/';
      $this->patterns[11]='/û/';
      //
      $this->replacements[0]='e';
      $this->replacements[1]='e';
      $this->replacements[2]='e';
      $this->replacements[3]='a';
      $this->replacements[4]='u';
      $this->replacements[5]='i';
      $this->replacements[6]='e';
      $this->replacements[7]='u';
      $this->replacements[8]='o';
      $this->replacements[9]='c';
      $this->replacements[10]='a';
      $this->replacements[11]='u';
    }
  public function getFilter($arr)
  {
    if (is_array($arr))
    {
      foreach ($arr as $val)
      { // keywords filter
        $str[] = preg_replace($this->patterns,$this->replacements,$val->getName());
      }
      $str=implode('|',$str);
    }
    else
    { // rss titles filter
      $str = preg_replace($this->patterns,$this->replacements,$arr);
    }


    return $str;
  }

}// end class
