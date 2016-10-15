<?php
namespace AppBundle\Service;

use AppBundle\Entity\Feed;

class Scrapper
{
    private $em;

    /**
    *
    *
    */
    public function __construct(\Doctrine\ORM\EntityManager $em){
      $this->em = $em;
    }

    public function start($newspaper,$url){
      $xml = simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);

      call_user_func(array($this,$newspaper),$xml);
    }

    private function elperiodico($xml){
        $item = $xml->channel->item[0];
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $item->description, $image);

        $this->createFeed((string) $item->title, (string) $item->description, (string) $item->link, $image['src'], "El Periodico");
    }

    private function larazon($xml){
        $item = $xml->channel->item[0];
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $item->description, $image);

        $this->createFeed((string) $item->title, (string) $item->description, (string) $item->link, $image['src'], "La Razon");
    }

    private function elconfidencial($xml){
        $item = $xml->entry[0];
        $this->createFeed((string) $item->title, (string) $item->summary, (string) $item->link[0]->attributes()['href'], $item->link[1]->attributes()['href'], "El Confidencial");
    }

    private function elpais($xml){
        $item = $xml->channel->item[0];
        $content = $item->children('content', true);

        $this->createFeed((string) $item->title, (string) $content->encoded, (string) $item->link, $item->enclosure[0][0]['url'], "El Pais");
    }

    private function elmundo($xml){
        $item = $xml->channel->item[0];
        $content = $item->children('media', true);
        $image = (isset($content->thumbnail)) ? $content->thumbnail->attributes()['url'] : '';

        $this->createFeed((string) $item->title, (string) $item->description, (string) $item->link, $image , "El Mundo");
    }

    private function createFeed($title,$body,$source,$image,$publisher){
      $feed = new Feed();
      $feed->setTitle($title);
      $feed->setBody($body);
      $feed->setSource($source);
      $feed->setImage($image);
      $feed->setPublisher($publisher);
      $feed->setCreated(new \DateTime());
      $this->em->persist($feed);
      $this->em->flush();
    }
}
?>
