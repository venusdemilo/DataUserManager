<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class RssController extends AbstractController
{
    /**
     * @Route("/rss", name="rss")
     */
    public function index()

  {

$replist=[];
$i=0;
$mes="start";
$arr=['notre','permis','trump','rejet','dans','alstom','macron'];
$str=implode('|',$arr);
$xml_list =
[
  'http://www.lefigaro.fr/rss/figaro_actualites.xml',
  'https://www.lemonde.fr/rss/une.xml',
  'https://www.la-croix.com/RSS/UNIVERS',
  'http://www.nouvelobs.com/rss.xml',
  'http://www.nouvelobs.com/monde/rss.xml',

]
;

$guid = 'http://www.nouvelobs.com/monde/20190206.OBS9796/paris-juge-pas-acceptable-la-rencontre-entre-di-maio-et-les-gilets-jaunes.html?xtor=RSS-14';


foreach ($xml_list as $xml)
{
  $rss_list = simplexml_load_file($xml);
  $channel_name = $rss_list->channel->title;
foreach ($rss_list->channel->item as $item)
{
  if($item->guid == $guid)
  {
    break;
  }
  if(preg_match("#.$str.#i", $item->title))
  {
    $i++;
    $replist[] = $item->title.' ('.$channel_name.' )';
  }
  else {
    $mes = $i."réponses";
  }
}
}


        return $this->render('rss/index.html.twig', [
            'controller_name' => 'RssController',
            'rss_list' => $rss_list,
            'mes' =>$mes,
            'replist'=> $replist,
            'str' =>$str,

        ]);
    }

}
