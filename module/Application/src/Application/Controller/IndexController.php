<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Services\ServicesSoundcloud;
use Services\Soundcloud\ServicesSoundcloudInvalidHttpResponseCodeException;
use Services\TestClass;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // build our API URL
        $url = "http://api.soundcloud.com/resolve.json?"
            . "url=http://soundcloud.com/"
            . "user-195672661"
            . "&client_id=13c9349570f6be07045639ca831b1b99";

        // Grab the contents of the URL
        $user_json = file_get_contents($url);

        // Decode the JSON to a PHP Object
        $user = json_decode($user_json);

        // Print out the User ID
        //echo $user->id;

        //get track
        $clientid = "13c9349570f6be07045639ca831b1b99"; // Your API Client ID
        $userid = $user->id; // ID of the user you are fetching the information for

        $soundcloud_url = "http://api.soundcloud.com/users/{$userid}/tracks.json?client_id={$clientid}";

        $tracks_json = file_get_contents($soundcloud_url);
        $tracks = json_decode($tracks_json);

        $trackArr = [];
        foreach ($tracks as $track)
        {
            $test = array('title'=>$track->title, 'id'=>$track->id);
            array_push($trackArr,$test) ;
        }
        print_r($trackArr);

        $track = file_get_contents("http://api.soundcloud.com/tracks/272519767?client_id=$clientid");
        echo "<pre>";
        print_r($track);
        echo "</pre>";

        return new ViewModel(array('authorizeUrl'=>null));
    }
}
