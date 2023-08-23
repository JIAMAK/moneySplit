<?php
class Payments
{
    public $params;
    public $id;
    private $conn;
    private $members;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPayments()
    {
        $query = "SELECT 
            m.name, 
            p.*
        FROM members m 
        JOIN payments p ON m.member_id=p.member_id 
        WHERE m.event_id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        if ($stmt->execute()) {
            return json_encode(array(
                'code' => 1,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ));
        } else {
            return json_encode(array(
                'code' => 0,
                'data' => $stmt->errorInfo()
            ));
        }
    }

    public function updatePayments()
    {
        $query = "UPDATE `payments` 
            SET 
            member_id = :member_id,
            price = :price,
            payment_name = :payment_name,
            debtor_ids=:debtor_ids,
            ch_datetime=CURRENT_DATE
            WHERE payment_id=:payment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':payment_id', $this->params['payment_id']);
        $stmt->bindValue(':member_id', $this->params['member_id']);
        $stmt->bindValue(':price', $this->params['summ']);
        $stmt->bindValue(':payment_name', $this->params['name']);
        $stmt->bindValue(':debtor_ids', json_encode($this->params['debtor_ids']));
        if ($stmt->execute()) {
            return json_encode(array(
                'code' => 1,
                'data' => "Update payment success"
            ));
        } else {
            return json_encode(array(
                'code' => 0,
                'data' => $stmt->errorInfo()
            ));
        }
    }

    public function addPayments()
    {
        // $this->params['debtor_ids'] = $this->calcDebtor();
        $query = "INSERT INTO payments (
            member_id, 
            price, 
            payment_name,
            debtor_ids
        ) VALUES 
        (
            :member_id,
            :price,
            :payment_name,
            :debtor_ids
        );";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':member_id', $this->params['member_id']);
        $stmt->bindValue(':price', $this->params['summ']);
        $stmt->bindValue(':payment_name', $this->params['name']);
        $stmt->bindValue(':debtor_ids', json_encode($this->params['debtor_ids']));
        if ($stmt->execute()) {
            return json_encode(array(
                'code' => 1,
                'data' => "Add payment success"
            ));
        } else {
            return json_encode(array(
                'code' => 0,
                'data' => $stmt->errorInfo()
            ));
        }
    }

    public function getPayback()
    {
        $query = "SELECT 
        p.member_id,
        p.price,
        p.debtor_ids as debtors
        FROM members m 
        JOIN payments p ON m.member_id=p.member_id 
        WHERE m.event_id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $payback = array();
            foreach ($result as $value) {
                if (!array_key_exists($value['member_id'], $payback)) {
                    $payback[$value['member_id']] = [];
                }
                $debtors = json_decode($value['debtors']);
                $debtors_count = count($debtors);
                foreach ($debtors as $key => $d) {
                    if ($value['member_id'] == $d) continue;
                    $price = round($value['price'] / $debtors_count, 1);
                    if (array_key_exists($d, $payback) && array_key_exists($value['member_id'], $payback[$d])) {
                        $calc = $payback[$d][$value['member_id']] - $price;
                        if ($calc < 0) {
                            $payback[$value['member_id']][$d] = $calc * (-1);
                            unset($payback[$d][$value['member_id']]);
                        } else {
                            $payback[$d][$value['member_id']] -= $price;
                        }
                        continue;
                    }
                    if (!array_key_exists($d, $payback[$value['member_id']])) {
                        $payback[$value['member_id']][$d] = $price;
                    } else {
                        $payback[$value['member_id']][$d] += $price;
                    }
                }
            }
            $this->getMembers();
            $payback_transaction = array();
            foreach ($payback as $key_p => $value) {
                foreach ($value as $key => $p) {
                    array_push($payback_transaction, [
                        "receiver_name" => $this->members->{$key_p},
                        "sender_name" => $this->members->{$key},
                        "price" => $p,
                    ]);
                }
            }
            // var_dump($payback_transaction);
            return json_encode(array(
                'code' => 1,
                'data' => $payback_transaction
            ));
        }
    }

    private function getMembers()
    {
        $query = "SELECT m.member_id, m.name FROM members m WHERE m.event_id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        if ($stmt->execute()) {
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = array();
            foreach ($response as $value) {
                $result[$value['member_id']] = $value['name'];
            }
            $this->members = (object)$result;
        }
    }
}
