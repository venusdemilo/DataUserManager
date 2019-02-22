<?php

namespace App\Controller;

use SimplePie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SimplePieController extends AbstractController
{

    /**
     * @Route("/simple/pie", name="simple_pie")
     */
    public function index()
    {
// 'https://www.oooojournal.net/rss',

$feeds = [
  'http://www.oooojournal.net/current.rss',
  'https://cdn-elle.ladmedia.fr/var/plain_site/storage/flux_rss/fluxToutELLEfr.xml',
  'https://www.femmeactuelle.fr/',
  'https://www.parismatch.com/Services/RSS',
  'http://www.leparisien.fr/services/rss/',
  'http://marianne.net',
  'https://www.telerama.fr',
  'http://www.lemonde.fr',
  'http://www.lefigaro.fr',
  'https://www.la-croix.com',
  'http://www.nouvelobs.com',


];
$i = 0;
$kw = ['valls','iran','bête','pied','lol','victime','Christophe Dettinger','endométriose','arrêtez','Bârthês'];
$patterns = array();
$replacements = array();
$patterns[0]='/ê/';
$patterns[1]='/é/';
$patterns[2]='/è/';
$patterns[3]='/à/';
$patterns[4]='/ù/';
$patterns[5]='/î/';
$patterns[6]='/ê/';
$patterns[7]='/ü/';
$patterns[8]='/ô/';
$patterns[9]='/ç/';
$patterns[10]='/â/';
$replacements=array();
$replacements[0]='e';
$replacements[1]='e';
$replacements[2]='e';
$replacements[3]='a';
$replacements[4]='u';
$replacements[5]='i';
$replacements[6]='e';
$replacements[7]='u';
$replacements[8]='o';
$replacements[9]='c';
$replacements[10]='a';



foreach ($kw as $val) {
  $str[] = preg_replace($patterns,$replacements,$val);
}


$str=implode('|',$str);
$feedList = [];
foreach ($feeds as $value) {
  $error="";
  $feed = new SimplePie();

  $feed->set_cache_location('/Users/philippe/simplepie');
  $feed->set_feed_url($value);
  $feed->init();
  $feed->handle_content_type();

  if ($feed->error())
{
	$error = "Une erreur s'est produite en récupérant ce flux : <a href=\"".$value."\">".$value."</a><br/>
  <i>(".$feed->error().")</i>";
}

    if (empty($error))
    {
      $linkList = $feed->get_all_discovered_feeds();
      foreach ($feed->get_items() as  $item) {//remove French typographic accents
        $title = preg_replace($patterns,$replacements,$item->get_title());
        if(preg_match("#\b.$str.\b#i", $title,$matche)) //whole word case-insensitive
        {

          echo '[ '.$matche[0].' ]<br/>';
          echo '<b style="bg-color:red">channel:</b> '.$feed->get_title().'<br/>';
          echo '<b style="bg-color:red">channel link:</b> '.$feed->get_link().'<br/>';
          echo '<b style="bg-color:red">titre:</b> '.$item->get_title().'<br/>';
          echo '<b style="bg-color:red">lien:</b> <a href="'.$item->get_link().'">lien</a><br/>';
          echo '<b style="bg-color:red">date item:</b> '.$item->get_date().'<br/>';
          echo '------------------------------<br/>';
        }
      }//end foreach
    } //end if
    $feedList[$i]=['feed' => $feed,'error'=>$error, 'linkList'=>$linkList];

  //$feedList[$i] = [ 'feed' => $feed , 'error' =>  $error, 'matches' => $matches];
  $i++;
  $feed->__destruct();
  unset($feed);
//  echo "Memory usage: " . number_format(memory_get_usage());
}//end foreach

  return $this->render('simple_pie/index.html.twig', [

      'feedList' => $feedList,
      'linkList' => $linkList,
  ]);



    }//end func
}//end class
