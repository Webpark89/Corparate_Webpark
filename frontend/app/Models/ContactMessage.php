<?php

declare(strict_types=1);

/**
 * ContactMessage model — persistence and retrieval for contact_messages table.
 */
class ContactMessage
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance();
    }

    /**
     * Create a new contact message record.
     *
     * @param array{
     *     company_name?: ?string,
     *     first_name: string,
     *     last_name: string,
     *     phone: string,
     *     email: string,
     *     message: string,
     *     pdpa_consent?: int,
     *     pdpa_consent_at?: ?string,
     *     status?: string,
     *     ip_address?: ?string,
     *     user_agent?: ?string,
     *     source_page?: ?string,
     *     email_sent?: int
     * } $data
     * @return int Inserted message ID
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO contact_messages (
                company_name,
                first_name,
                last_name,
                phone,
                email,
                message,
                pdpa_consent,
                pdpa_consent_at,
                status,
                ip_address,
                user_agent,
                source_page,
                email_sent,
                created_at
            ) VALUES (
                :company_name,
                :first_name,
                :last_name,
                :phone,
                :email,
                :message,
                :pdpa_consent,
                :pdpa_consent_at,
                :status,
                :ip_address,
                :user_agent,
                :source_page,
                :email_sent,
                NOW()
            )
        ');

        $stmt->execute([
            ':company_name'     => !empty($data['company_name']) ? trim((string)$data['company_name']) : null,
            ':first_name'       => trim((string)$data['first_name']),
            ':last_name'        => trim((string)$data['last_name']),
            ':phone'            => trim((string)$data['phone']),
            ':email'            => trim((string)$data['email']),
            ':message'          => trim((string)$data['message']),
            ':pdpa_consent'     => !empty($data['pdpa_consent']) ? 1 : 0,
            ':pdpa_consent_at'  => $data['pdpa_consent_at'] ?? date('Y-m-d H:i:s'),
            ':status'           => $data['status'] ?? 'new',
            ':ip_address'       => $data['ip_address'] ?? null,
            ':user_agent'       => $data['user_agent'] ?? null,
            ':source_page'      => $data['source_page'] ?? null,
            ':email_sent'       => !empty($data['email_sent']) ? 1 : 0,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update the email_sent status of a message.
     */
    public function updateEmailSent(int $id, bool $sent = true): bool
    {
        $stmt = $this->pdo->prepare('UPDATE contact_messages SET email_sent = ? WHERE id = ?');
        return $stmt->execute([$sent ? 1 : 0, $id]);
    }

    /**
     * Count messages from a specific IP within the last N minutes (for rate limiting / anti-flood).
     */
    public function countRecentByIp(string $ip, int $minutes = 5): int
    {
        if ($ip === '') {
            return 0;
        }

        $stmt = $this->pdo->prepare('
            SELECT COUNT(*) FROM contact_messages 
            WHERE ip_address = :ip 
              AND created_at >= DATE_SUB(NOW(), INTERVAL :minutes MINUTE)
        ');
        $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
        $stmt->bindValue(':minutes', $minutes, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Update the status of a message (new, read, replied, archived).
     */
    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['new', 'read', 'replied', 'archived'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare('UPDATE contact_messages SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    /**
     * Find a single message by ID.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM contact_messages WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    /**
     * Fetch list of messages with optional status filter and pagination.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(string $status = '', int $limit = 20, int $offset = 0, string $search = ''): array
    {
        $sql = 'SELECT * FROM contact_messages WHERE 1=1';
        $params = [];

        if ($status !== '' && in_array($status, ['new', 'read', 'replied', 'archived'], true)) {
            $sql .= ' AND status = ?';
            $params[] = $status;
        }

        if ($search !== '') {
            $sql .= ' AND (first_name LIKE ? OR last_name LIKE ? OR company_name LIKE ? OR email LIKE ? OR phone LIKE ?)';
            $searchWild = '%' . $search . '%';
            $params[] = $searchWild;
            $params[] = $searchWild;
            $params[] = $searchWild;
            $params[] = $searchWild;
            $params[] = $searchWild;
        }

        $sql .= ' ORDER BY created_at DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count total messages matching criteria.
     */
    public function countAll(string $status = '', string $search = ''): int
    {
        $sql = 'SELECT COUNT(*) FROM contact_messages WHERE 1=1';
        $params = [];

        if ($status !== '' && in_array($status, ['new', 'read', 'replied', 'archived'], true)) {
            $sql .= ' AND status = ?';
            $params[] = $status;
        }

        if ($search !== '') {
            $sql .= ' AND (first_name LIKE ? OR last_name LIKE ? OR company_name LIKE ? OR email LIKE ? OR phone LIKE ?)';
            $searchWild = '%' . $search . '%';
            $params[] = $searchWild;
            $params[] = $searchWild;
            $params[] = $searchWild;
            $params[] = $searchWild;
            $params[] = $searchWild;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }
}
