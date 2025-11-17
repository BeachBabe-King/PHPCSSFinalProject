<?php

class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($firstName, $lastName, $email, $hashedPassword, $phone = null, $address = null)
    {
        $sql = "INSERT INTO FPAdmin (firstName, lastName, email, password, phone, address, isAdmin) 
                VALUES (:firstName, :lastName, :email, :password, :phone, :address, FALSE)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":firstName" => $firstName,
            ":lastName" => $lastName,
            ":email" => $email,
            ":password" => $hashedPassword,
            ":phone" => $phone,
            ":address" => $address
        ]);

        return $this->pdo->lastInsertId();
    }

    public function emailExists($email)
    {
        $sql = "SELECT userId FROM FPAdmin WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function findByEmail($email)
    {
        $sql = "SELECT userId, firstName, lastName, email, password, isAdmin 
                FROM FPAdmin 
                WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        return $stmt->fetch();
    }
}

?>