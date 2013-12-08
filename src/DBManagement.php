<?php

include_once (dirname(__FILE__) . '/DBConnection.php');
include_once (dirname(__FILE__) . '/ContributionInfo.php');
include_once (dirname(__FILE__) . '/PostInfo.php');


class DBManagement {
  
  
/********************************************************
* Compare avec le tableau si les éléments existe déjà
* Si n'existe pas, il appelle la fonction insertIntoTable
* la vérification se fait à l'aide du nom d'utilisateur
* du user ID et du siteweb d'ou provient la contribution
*********************************************************/
  
  public static function compareContributionIfInTable($uneContrib, $dataBase) {
    
//   $result =  $dataBase -> prepare('SELECT * FROM contributions WHERE ID ='  . $uneContrib->getUserID() . ' AND timestamp =' . $uneContrib->getPagesID() . 'AND website =' . $uneContrib->getWebSite());
//    $result->execute();
//    $row_count = $result -> rowCount();
//    return !($row_count == 0);

      $uneContribPID = $uneContrib->getPagesID();
      $uneContribUID = $uneContrib->getUserID();
      $uneContribWEB = $uneContrib->getWebSite();

      $result = $dataBase -> query("SELECT * FROM contributions WHERE page_id='$uneContribPID' AND ID='$uneContribUID' AND website='$uneContribWEB'");
      if(!$result){
          print_r($dataBase->errorInfo());
      }
      $count = $result->rowCount();
      return ($count>0);
  }


  
  
/**********************************************************
* Insert dans la table table les informations de la contributions
****************************************************************/ 
  public static function insertContributionIntoTable($uneContrib, $dataBase){
    
    if (!DBManagement::compareContributionIfInTable($uneContrib, $dataBase)){
      
//      $contributionIDToInsert = {$uneContrib->ContributionInfo::getPagesId()};
//      $contributionOldVersionToInsert = {$uneContrib->ContributionInfo::getOldVersion()};
//      $contributionUserVersionToInsert = {$uneContrib->ContributionInfo::getUserVersion()};
//      $contributionUsertimestampToInsert = {$uneContrib->ContributionInfo::getUsertimestamp()};
//      $contributionWebsiteToInsert = {$uneContrib->ContributionInfo::getWebsite()};
//      $contributorID = {$uneContrib->ContributionInfo::getUserID()};


            $contributionIDToInsert = $uneContrib->getPagesId();
            $contributionOldVersionToInsert = $uneContrib->getOldVersion();
            $contributionUserVersionToInsert = $uneContrib->getUserVersion();
            $contributionUsertimestampToInsert = $uneContrib->getUsertimestamp();
            $contributionWebsiteToInsert = $uneContrib->getWebsite();
            $contributorID = $uneContrib->getUserID();


// echo "PID\n";
//echo $unPostPID;
//echo "UID\n";
// echo $unPostUID;
//echo "WEB\n";
// echo $unPostWEB;


        $result = $dataBase ->prepare("INSERT INTO talk(ID,page_id,rev_id,parent_id,time,website) VALUES(:contributorID,:contributionIDToInsert,:contributionUserVersionToInsert,:contributionOldVersionToInsert,:contributionUsertimestampToInsert,:contributionWebsiteToInsert)");
        $result->execute(array(':contributorID' => $contributorID, ':contributionIDToInsert' => $contributionIDToInsert, ':contributionUserVersionToInsert' => $contributionUserVersionToInsert, ':contributionOldVersionToInsert' => $contributionOldVersionToInsert, ':contributionUsertimestampToInsert' => $contributionUsertimestampToInsert, ':contributionWebsiteToInsert' => $contributionWebsiteToInsert));
        if(!$result){
            print_r($dataBase->errorInfo());
        }
        $count = $result->rowCount();
        return ($count>0);


        //$dataBase -> exec("INSERT INTO contributions('ID','page_id','rev_id','parent_id','time','website') VALUES('" . $contributorID . "','" . $contributionIDToInsert . "','" . $contributionUserVersionToInsert . "','" . $contributionOldVersionToInsert . "','" . $contributionUsertimestampToInsert . "','" . $contributionWebsiteToInsert . "')");
      
      
    }      
  }
  
  
  
  public static function compareUserIfInTable($username,$dataBase) {
      echo "av compare prepare<br>";
      $result = $dataBase -> query("SELECT * FROM contributor WHERE contributor_username='$username'");
      echo "- Compare exec fait<br>";
    //$row_count = $result -> rowCount();
      if(!$result){
         print_r($dataBase->errorInfo());
      }
      $count = $result->rowCount();
      return ($count>0);
  }
  
  public static function insertUserIntoTable($usernameToInsert, $dataBase) {
      echo " |Insert av if compare<br>";
    if (!DBManagement::compareUserIfInTable($usernameToInsert, $dataBase)) {
        //echo " |Insert av execute<br>";
        $result = $dataBase -> prepare("INSERT INTO contributor(contributor_username) VALUES(:username)");
        //echo " |Insert av bind<br>";
        $result->bindParam(":username",$username);
        //echo " |Insert av exec2<br>";
        $username = $usernameToInsert;
        $result -> execute();
        //echo " |Insert execute fait<br>";
    }
  }
        
  public static function retrieveUserID($username, $dataBase) {
       $result = $dataBase -> prepare("SELECT ID FROM contributor WHERE contributor_username='$username'");
       $result->execute();
       $row_count = $result -> rowcount();
       $theResult = $result->fetch();
       return ($row_count == 0) ? null : $theResult[0] ;
  }
        
        
        
/********************************************************
* Compare avec le tableau si les éléments d'un talk existe déjà
* Si n'existe pas, il appelle la fonction insertPostIntoTable
* la vérification se fait à l'aide du nom d'utilisateur
* du user ID et du siteweb d'ou provient le post
*********************************************************/
        
        public static function comparePostIfInTable($unPost, $dataBase) {
          
          //$resultTalk = $dataBase -> exec("SELECT * FROM talk where page_id =".$unPost->getPagesId(). "AND ID=".$unPost->getUserID()."AND website=".$unPost->getWebSite());
          //$row_count = $resultTalk -> rowcount();
          //return $row_count == 0;

          $unPostPID = $unPost->getPagesId();
          $unPostUID = $unPost->getUserID();
          $unPostWEB = $unPost->getWebSite();
          $unPostRID = $unPost->getRevId();

//       echo "\n";
//       echo "PID\n";
//       echo $unPostPID;
//       echo "\n";
//       echo "UID\n";
//       echo $unPostUID;
//       echo "\n";
//       echo "WEB\n";
//       echo $unPostWEB;
//       echo "\n";
//       echo $unPostRID;
//       echo "\n";

          $result = $dataBase -> prepare("SELECT * FROM talk WHERE rev_id='$unPostRID' AND page_id='$unPostPID' AND ID='$unPostUID' AND website='$unPostWEB'");
          $result->execute();

            if(!$result){
                print_r($dataBase->errorInfo());
            }

            $count = $result->rowcount();

//       echo "COUNT\n";
//       echo $count;
//       echo "\n";

            return ($count>0);
        }
        
        
/**********************************************************
* Insert dans la table table les informations d'un post
****************************************************************/ 
        public static function insertPostIntoTable($unPost, $dataBase){
          
          if (!DBManagement::comparePostIfInTable($unPost, $dataBase)) {
            $postIDToInsert = $unPost->getPagesId();
            $postWebsiteToInsert = $unPost->getWebsite();
            $userID = $unPost->getUserID();
            $revID = $unPost->getRevID();

                //$dataBase -> prepare("INSERT INTO talk('ID','website','page_id','post') VALUES('" . $userID . "','" . $postWebsiteToInsert . "','" . $postIDToInsert . "','" . $revID . "')");
             $result = $dataBase ->prepare("INSERT INTO talk(ID,website,page_id,rev_id) VALUES(:userID,:postWebsiteToInsert,:postIDToInsert,:revID)");
             $result->execute(array(':postIDToInsert' => $postIDToInsert, ':postWebsiteToInsert' => $postWebsiteToInsert, ':userID' => $userID, ':revID' => $revID));
              if(!$result){
                  print_r($dataBase->errorInfo());
              }
              $count = $result->rowCount();
              return ($count>0);

          }
        }
    }
?>
