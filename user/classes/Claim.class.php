<?php
require_once '../config/Database.php';

class Claim {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Initialize Database connection
    }

    // Fetch the claim status for a specific item
    public function fetchClaimStatus($itemId, $itemType) {
        $table = ($itemType === 'lost') ? 'lost_items' : 'found_items';
        $sql = "SELECT * FROM claims WHERE item_id = :item_id AND item_type = :item_type";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':item_id', $itemId);
        $stmt->bindParam(':item_type', $itemType);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new claim for a lost or found item
    public function createClaim($userId, $itemId, $itemType) {
        try {
            // Check if the claim already exists
            $claimStatus = $this->fetchClaimStatus($itemId, $itemType);
            if ($claimStatus) {
                throw new Exception("This item has already been claimed.");
            }

            $sql = "INSERT INTO claims (user_id, item_id, item_type, claim_date) 
                    VALUES (:user_id, :item_id, :item_type, NOW())";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':item_id', $itemId);
            $stmt->bindParam(':item_type', $itemType);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception("Error creating claim: " . $e->getMessage());
        }
    }

    // Mark a claim as resolved (item has been claimed)
    public function resolveClaim($claimId) {
        try {
            $sql = "UPDATE claims SET status = 'resolved' WHERE claim_id = :claim_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':claim_id', $claimId);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception("Error resolving claim: " . $e->getMessage());
        }
    }

    // Fetch all claims
    public function fetchAllClaims() {
        $sql = "SELECT c.*, u.username AS claimant_name, 
                i.description AS item_description 
                FROM claims c 
                JOIN users u ON c.user_id = u.user_id
                JOIN lost_items i ON c.item_id = i.item_id
                ORDER BY c.claim_date DESC";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
