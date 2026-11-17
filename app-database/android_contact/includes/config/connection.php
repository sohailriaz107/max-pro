<?php 
declare(strict_types=1);

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

date_default_timezone_set("Asia/Kolkata");
@include_once __DIR__ . "/credentials.php";

class connection
{	
    private string $hostname;
    private string $username;
    private string $password;
    private string $dbname;
    
    private ?mysqli $conn  = null;
    public ?mysqli $mysqli = null;

    public function __construct()
    {
        $this->hostname = defined('S3_DB_HOST') ? S3_DB_HOST : '51.89.97.89';
        $this->username = defined('S3_DB_USER') ? S3_DB_USER : 'dpbossapi';
        $this->password = defined('S3_DB_PASS') ? S3_DB_PASS : 'TfyrEt%768U';
        $this->dbname   = defined('S3_DB_NAME') ? S3_DB_NAME : 'dpboss_net_api';

        try {
            $this->conn = mysqli_init();
            if ($this->conn) {
                $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
                @$this->conn->real_connect($this->hostname, $this->username, $this->password, $this->dbname);
                if ($this->conn->connect_error) {
                    throw new Exception('Connection Error: ' . $this->conn->connect_error);
                }
                $this->conn->set_charset("utf8mb4");
                $this->mysqli = $this->conn;
            }
        } catch (Throwable $ex) {
            error_log("Connection error in includes connection: " . $ex->getMessage());
        }
    }

    public function get_connection(): ?mysqli
    {
        return $this->conn;
    }

    public function insert(string $data): int
    {
        if (!$this->conn) return 0;
        try {
            if ($this->conn->query($data) === true) {
                return (int)$this->conn->insert_id;
            }
        } catch (Throwable $e) {
            error_log('Insert error: ' . $e->getMessage());
        }
        return 0;
    }

    public function select(string $data): array
    {
        $rows = [];
        if (!$this->conn) return $rows;
        try {
            $result = $this->conn->query($data);
            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_array(MYSQLI_BOTH)) {
                    $rows[] = $row;
                }
                $result->free();
            }
        } catch (Throwable $e) {
            error_log('Select error: ' . $e->getMessage());
        }
        return $rows;
    }

    public function count_row(string $data): int
    {
        if (!$this->conn) return 0;
        try {
            $result = $this->conn->query($data);
            if ($result instanceof mysqli_result) {
                $count = (int)$result->num_rows;
                $result->free();
                return $count;
            }
        } catch (Throwable $e) {
            error_log('Count error: ' . $e->getMessage());
        }
        return 0;
    } 

    public function select_where(string $data): array
    {
        $rows = [];
        if (!$this->conn) return $rows;
        try {
            $result = $this->conn->query($data);
            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                $result->free();
            }
        } catch (Throwable $e) {
            error_log('Select where error: ' . $e->getMessage());
        }
        return $rows;
    }

    public function select_where_with_param(string $table, string $where_clause): array
    {
        $data = [];
        if (!$this->conn) return $data;

        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $whereSQL = '';
        if (trim($where_clause) !== '') {
            $trimmed = trim($where_clause);
            if (strncasecmp($trimmed, 'WHERE', 5) !== 0) {
                $whereSQL = " WHERE " . $trimmed;
            } else {
                $whereSQL = " " . $trimmed;
            }
        }

        $qry = "SELECT * FROM `" . $safeTable . "`" . $whereSQL;
        try {
            $result = $this->conn->query($qry);
            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $result->free();
            }
        } catch (Throwable $e) {
            error_log('select_where_with_param error: ' . $e->getMessage());
        }
        return $data;
    }

    public function select_where_with_param_order(string $table, string $where_clause, string $order): array
    {
        $data = [];
        if (!$this->conn) return $data;

        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $whereSQL = '';
        if (trim($where_clause) !== '') {
            $trimmed = trim($where_clause);
            if (strncasecmp($trimmed, 'WHERE', 5) !== 0) {
                $whereSQL = " WHERE " . $trimmed;
            } else {
                $whereSQL = " " . $trimmed;
            }
        }

        $qry = "SELECT * FROM `" . $safeTable . "`" . $whereSQL . ' ' . $order;
        try {
            $result = $this->conn->query($qry);
            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $result->free();
            }
        } catch (Throwable $e) {
            error_log('select_where_with_param_order error: ' . $e->getMessage());
        }
        return $data;
    }

    public function select_where_with_fields(string $table, string $where_clause, string $fields): array
    {
        $data = [];
        if (!$this->conn) return $data;

        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $whereSQL = '';
        if (trim($where_clause) !== '') {
            $trimmed = trim($where_clause);
            if (strncasecmp($trimmed, 'WHERE', 5) !== 0) {
                $whereSQL = " WHERE " . $trimmed;
            } else {
                $whereSQL = " " . $trimmed;
            }
        }

        $qry = "SELECT " . $fields . " FROM `" . $safeTable . "`" . $whereSQL;
        try {
            $result = $this->conn->query($qry);
            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $result->free();
            }
        } catch (Throwable $e) {
            error_log('select_where_with_fields error: ' . $e->getMessage());
        }
        return $data;
    }

    public function update_where(string $data): int
    {
        if (!$this->conn) return 0;
        try {
            return ($this->conn->query($data) === true) ? 1 : 0;
        } catch (Throwable $e) {
            error_log('Update error: ' . $e->getMessage());
            return 0;
        }
    }

    public function delete(string $data): int
    {
        if (!$this->conn) return 0;
        try {
            return ($this->conn->query($data) === true) ? 1 : 0;
        } catch (Throwable $e) {
            error_log('Delete error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getExcerpt(string $str, int $startPos, int $maxLength): string 
    {
        if (mb_strlen($str, 'UTF-8') > $maxLength) {
            $excerpt = mb_substr($str, $startPos, $maxLength - 3, 'UTF-8');
            $lastSpace = mb_strrpos($excerpt, ' ', 0, 'UTF-8');
            if ($lastSpace !== false) {
                $excerpt = mb_substr($excerpt, 0, $lastSpace, 'UTF-8');
            }
            $excerpt .= '...';
        } else {
            $excerpt = $str;
        }

        return $excerpt;
    }

    public function InsertData(string $table_name, array $form_data): int
    {
        if (!$this->conn || empty($form_data)) return 0;

        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
        $fields = [];
        $values = [];

        foreach ($form_data as $column => $value) {
            $fields[] = "`" . preg_replace('/[^a-zA-Z0-9_]/', '', (string)$column) . "`";
            $values[] = "'" . $this->conn->real_escape_string((string)$value) . "'";
        }

        $sql = "INSERT INTO `" . $safeTable . "` (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";

        try {
            if ($this->conn->query($sql) === true) {
                return (int)$this->conn->insert_id;
            }
        } catch (Throwable $e) {
            error_log('InsertData error: ' . $e->getMessage());
        }
        return 0;
    }

    public function ReturnInsertData(string $table_name, array $form_data): string
    {
        if (!$this->conn || empty($form_data)) return '';

        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
        $fields = [];
        $values = [];

        foreach ($form_data as $column => $value) {
            $fields[] = "`" . preg_replace('/[^a-zA-Z0-9_]/', '', (string)$column) . "`";
            $values[] = "'" . $this->conn->real_escape_string((string)$value) . "'";
        }

        return "INSERT INTO `" . $safeTable . "` (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
    }

    public function DeleteData(string $table_name, string $where_clause = ''): int
    {
        if (!$this->conn) return 0;

        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
        $whereSQL = '';
        if (trim($where_clause) !== '') {
            $trimmed = trim($where_clause);
            if (strncasecmp($trimmed, 'WHERE', 5) !== 0) {
                $whereSQL = " WHERE " . $trimmed;
            } else {
                $whereSQL = " " . $trimmed;
            }
        }

        $sql = "DELETE FROM `" . $safeTable . "`" . $whereSQL;

        try {
            return ($this->conn->query($sql) === true) ? 1 : 0;
        } catch (Throwable $e) {
            error_log('DeleteData error: ' . $e->getMessage());
            return 0;
        }
    }

    public function UpdateData(string $table_name, array $form_data, string $where_clause = ''): int
    {
        if (!$this->conn || empty($form_data)) return 0;

        $safeTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
        $whereSQL = '';
        if (trim($where_clause) !== '') {
            $trimmed = trim($where_clause);
            if (strncasecmp($trimmed, 'WHERE', 5) !== 0) {
                $whereSQL = " WHERE " . $trimmed;
            } else {
                $whereSQL = " " . $trimmed;
            }
        }

        $sets = [];
        foreach ($form_data as $column => $value) {
            $sets[] = "`" . preg_replace('/[^a-zA-Z0-9_]/', '', (string)$column) . "` = '" . $this->conn->real_escape_string((string)$value) . "'";
        }

        $sql = "UPDATE `" . $safeTable . "` SET " . implode(', ', $sets) . $whereSQL;

        try {
            return ($this->conn->query($sql) === true) ? 1 : 0;
        } catch (Throwable $e) {
            error_log('UpdateData error: ' . $e->getMessage());
            return 0;
        }
    }

    public function now_date(): string
    {
        date_default_timezone_set('Asia/Kolkata');
        return date("Y-m-d H:i:s");
    }

    public function now_date_with_param(string $string): string
    {
        date_default_timezone_set('Asia/Kolkata');
        return date($string);
    }
}
