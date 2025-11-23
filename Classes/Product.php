<?php
class Product
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Create new product (addProduct)
    public function createProduct($name, $author, $description, $price, $image, $category, $pageCount, $stock)
    {
        $sql = ("INSERT INTO FPproduct (name, author, description, price, image, category, pageCount, stock)
                    VALUES (:name, :author, :description, :price, :image, :category, :pageCount, :stock)");

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":name" => $name,
            ":author" => $author,
            ":description" => $description ?: null,
            ":price" => $price,
            ":image" => $image ?: null,
            ":category" => $category ?: null,
            ":pageCount" => $pageCount ?: null,
            ":stock" => $stock
        ]);

        return $this->pdo->lastInsertId();
    }

    // Get all products (manageProducts)
    public function getAllProducts() {
        $sql = "SELECT id, name, author, price, category, stock, createdAt
        FROM FPproduct
        ORDER BY createdAt DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single product using id
    public function getProduct($id) {
        $sql = "SELECT * FROM FPproduct WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update product (edit product)
    public function updateProduct($id, $name, $author, $description, $price, $image, $category, $pageCount, $stock)
    {
        $sql = "UPDATE  FPproduct
                SET name = :name,
                    author = :author,
                    description = :description,
                    price = :price,
                    image = :image,
                    category = :category,
                    pageCount = :pageCount,
                    stock = :stock
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":name" => $name,
            ":author" => $author,
            ":description" => $description ?: null,
            ":price" => $price,
            ":image" => $image ?: null,
            ":category" => $category ?: null,
            ":pageCount" => $pageCount ?: null,
            ":stock" => $stock,
            ":id" => $id
        ]);
    }

        // Delete product
        public function deleteProduct($id)
        {
            $sql = "DELETE FROM FPproduct WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([":id" => $id]);
        }

        // Checks if product exists
        public function productExists($id) {
            $sql = "SELECT id FROM FPproduct WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":id" => $id]);

            return $stmt->fetch() ? true : false;
    }
}
?>