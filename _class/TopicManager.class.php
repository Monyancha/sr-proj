<?php
include_once 'Topic.class.php';

class TopicManager{
    private $_db;

    //constructor
    public function __construct($db){
        $this->setDB($db);
    }
    //setters
    public function setDB(PDO $temp_db){
        $this->_db = $temp_db;
    }
    //methods
    public static function addNew($topic, $first_project, $db){
        $quest = $db->prepare("INSERT INTO topics (topic, hits, projects) VALUES (?, 1, ?)");
        $quest->execute(array($topic, $first_project));
    }
    public function get($topic){
        $quest = $this->_db->prepare("SELECT * FROM topics WHERE topic = ?");
        $quest->execute(array($topic));
        $response_data = $quest->fetch(PDO::FETCH_ASSOC);
        return new Topic($response_data);
    }
    public static function getTopics($topic, $db){
        $quest = $db->prepare("SELECT * FROM topics");
        $quest->execute();
        return $quest->fetchAll();
    }
    public static function getPopularTopics($db){
        $quest = $db->prepare("SELECT * FROM topics ORDER BY hits DESC LIMIT 10");
        $quest->execute();
        return $quest->fetchAll();
    }
    public static function addProject($topic, $new_project, $db){
        $quest = $db->prepare("UPDATE topics SET projects = CONCAT(projects, ';', ?) WHERE topic = ?");
        $quest->execute(array($new_project, $topic));
        $quest = $db->prepare("UPDATE topics SET hits = hits+1 WHERE topic = ?");
        $quest->execute(array($topic));
    }
    public static function topicExists($topic, PDO $db){
        $quest = $db->prepare("SELECT topic FROM topics WHERE topic = ?");
        $quest->execute(array($topic));
        if(!empty($quest->fetchAll())) return true;
        else return false;
    }
}
?>