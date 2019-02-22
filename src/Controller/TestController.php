<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(Request $request)
    {


      $pattern=['fln','gilet','trump','rejet','dans','alstom','macron'];
      $pattern=implode('|',$pattern);

//$rss = "https://www.oooojournal.net/rss";
//$rss="https://feeds.feedburner.com/sophos/dgdY";
$rss="https://www.senat.fr/rss/rapports.xml";//Atom
$crawler = new Crawler();
$crawler->addXmlContent(file_get_contents($rss));

//Channel type
$channelTypes = ['rss','feed','rdf'];
$feedType = $crawler->filterXPath('//*')->nodeName();


  if ($feedType == 'feed')
  {
  $atomFeedFields = [


    //Required feed elements
    'feedUri' => 'id',                      //web address
    'feedTitle' => 'title',
    'feedDate' => 'updated',


    //Recommended feed elements
    'feedAuthor' => 'author',
    'feedAuthorName' => 'name',
    'feedAuthorEmail' => 'Email',
    'feedAuthorUri' => 'uri',
    'feedLink '=> 'link',
    //Optionnal feed element
    'feedCategory' => 'category',
    'feedContributor' => 'contributor',
    'feedGenerator' => 'generator',
    'feedIcon' => 'icon',
    'feedLogo' => 'logo',
    'feedRights '=> 'rights',
    'feedSubtitle '=> 'subtitle',
  ];

  $itemPattern = 'entry';
  $atomItemFields = [

    //Required item enlements
    'itemId' => 'id',
    'itemTitle' => 'title',
    'itemDate' => 'updated',
    //Recomended item enlements
    'itemAuthor' => 'author',
    'itemAuthorName' => 'name',
    'itemContent' => 'content',
    'itemlink' => 'link',
    'itemSummary' => 'summary',
    //Optional item enlements
    'itemCategory' => 'category',
    'itemContributor' => 'contributor',
    'itemContributorName' => 'name',
    'itemPublished' => 'published',
    'itemSource' => 'source',
    'itemSourdeId' => 'id',
    'itemSourceTitle' => 'title',
    'itemSourceUpdated' => 'date',
    'itemSourceRights' => 'rights',
  ];
}
switch($feedType){
  case 'rss': //rss2.0
    $channelPattern = 'channel';
    $sommairePattern = 'description';
    $imagePattern = 'image';
    $channelDatePattern = 'lastBuildDate';
    break;

  case 'rdf': //rss1.0
    $channelPattern = 'channel';
    $sommairePattern = 'description';
    $imagePattern = 'image';
    $channelDatePattern = 'dc:date';
    break;
}
//Channel Crawler
foreach ($atomFeedFields as $key => $val)
{
  $feed[$key]= $crawler->filterXPath('//'.$feedType)->children($val)->text('');
  if($key == 'feedAuthor'){
    $feed['feedAuthorName']= $crawler->filterXPath('//'.$feedType)->children($val)->children('name')->text('');
    $feed['feedAuthorEmail']= $crawler->filterXPath('//'.$feedType)->children($val)->children('email')->text('');
    $feed['feedAuthorUri']= $crawler->filterXPath('//'.$feedType)->children($val)->children('uri')->text('');
  }
}

$itemTitles=$crawler->filterXPath('//'.$feedType.'/'.$itemPattern)->extract(['_text']);
  foreach ($atomItemFields as $key => $val)
  {
    $listItem[$key] = 'coco';
  }



/*
$feedUri = $crawler->filterXPath('//'.$feedPattern)->children($feedUri)->text('');
$feedTitle = $crawler->filterXPath('//'.$feedPattern)->children($feedTitle)->text('');
$feedDate = $crawler->filterXPath('//'.$feedPattern)->children($feedDate)->text('');
$channelDescription = $crawler->filterXPath('//'.$channelPattern)->children($sommairePattern)->text('');
$channelImageUrl = $crawler->filterXPath('//'.$channelPattern)->children($imagePattern)->children('url')->text('');
*/

/*
//  items Crawler
$titles=$crawler->filterXPath('//channel/item/title')->extract(['_text']);
$i=0;
foreach($titles as $title)
{
  $link = $crawler->filterXPath('//channel/item/link')->eq($i)->text('Valeur indéfinie');
  $pubDate= $crawler->filterXPath('//channel/item/pubDate')->eq($i)->text('Valeur indéfinie');
  $guid= $crawler->filterXPath('//channel/item/guid')->eq($i)->text('Valeur indéfinie');
  $description= $crawler->filterXPath('//channel/item/description')->eq($i)->text('Valeur indéfinie');
  $category= $crawler->filter('channel > item > category')->eq($i)->text('');
  $author= $crawler->filterXPath('//channel/item/author')->eq($i)->text('');

  if (1 == preg_match("#.$pattern.#i",$title))
  {
  $arr[]=[
    'title'=>$title,
    'link'=> $link ,
    'pubDate'=> $pubDate,
    'guid'=>$guid,
    'description'=>$description,
    'category'=>$category,
    'author'=>$author,
  ];
  }
  $i++;
}
*/

        return $this->render('test/index.html.twig',array(
          'feed' => $feed,
          'feedType' => $feedType,
        )
      );
    }//end return
}// end class
