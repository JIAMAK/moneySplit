<?php
class Event
{
    public $params;
    public $id;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function get()
    {
        if ($this->conn) {
            $query = "SELECT a.id, a.event_name, b.member_id, b.name 
                FROM main a 
                JOIN members b ON a.id=b.event_id
                WHERE a.id=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $this->id);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response = array();
                foreach ($result as $value) {
                    $id = $value['id'];
                    if (count($response) === 0) {
                        $response = array(
                            'event_id' => $id,
                            'event_name' => $value['event_name'],
                            'members' => array()
                        );
                    }
                    array_push($response['members'], array('member_id' => $value['member_id'], 'name' => $value['name']));
                }
                return json_encode(array(
                    'code' => 1,
                    'data' => $response
                ));
            } else {
                return json_encode(array(
                    'code' => 0,
                    'data' => $stmt->errorInfo()
                ));
            }
        } else {
            echo json_encode(array(
                'code' => 0,
                'data' => 'Could not connect to database!'
            ));
        }
    }

    public function add()
    {
        if ($this->conn) {
            $query = "INSERT INTO main (
                id,
                event_name,
                creator,
                creator_email
            )
            VALUES (
                :id,
                :event_name,
                :creator,
                :creator_email
            )";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $this->params['id']);
            $stmt->bindValue(':event_name', $this->params['eventName']);
            $stmt->bindValue(':creator', $this->params['creatorName']);
            $stmt->bindValue(':creator_email', $this->params['creatorEmail']);
            if ($stmt->execute()) {
                $this->conn->beginTransaction();
                $event_id = $this->params['id'];
                $name = $this->params['creatorName'];
                $stmt = $this->conn;
                $stmt->exec("INSERT INTO members (event_id, name) VALUES ('$event_id', '$name')");
                foreach ($this->params['members'] as $value) {
                    $name = $value['name'];
                    $stmt->exec("INSERT INTO members (event_id, name) VALUES ('$event_id', '$name')");
                }
                $this->conn->commit();
                return json_encode(array(
                    'code' => 1,
                    'data' => "Add event success"
                ));
            } else {
                return json_encode(array(
                    'code' => 0,
                    'data' => $stmt->errorInfo()
                ));
            }
        } else {
            echo json_encode(array(
                'code' => 0,
                'data' => 'Could not connect to database!'
            ));
        }
    }
}
