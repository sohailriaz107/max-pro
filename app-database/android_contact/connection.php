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
            error_log("Connection error in app-database connection: " . $ex->getMessage());
        }
    }

    public function get_connection(): ?mysqli
    {
        return $this->conn;
    }

    public function insert(string $query, array $params = []): int
    {
        if (!$this->conn) {
            return 0;
        }

        try {
            if (!empty($params) && function_exists('mysqli_execute_query')) {
                $result = mysqli_execute_query($this->conn, $query, $params);
            } else {
                $result = $this->conn->query($query);
            }

            if ($result === true) {
                return (int)$this->conn->insert_id;
            }
        } catch (Throwable $e) {
            error_log('Insert error: ' . $e->getMessage());
        }

        return 0;
    }

    public function select(string $query, array $params = []): array
    {
        $data = [];
        if (!$this->conn) {
            return $data;
        }

        try {
            if (!empty($params) && function_exists('mysqli_execute_query')) {
                $result = mysqli_execute_query($this->conn, $query, $params);
            } else {
                $result = $this->conn->query($query);
            }

            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_array(MYSQLI_BOTH)) {
                    $data[] = $row;
                }
                $result->free();
            }
        } catch (Throwable $e) {
            error_log('Select error: ' . $e->getMessage());
        }

        return $data;
    }

    public function count_row(string $query, array $params = []): int
    {
        if (!$this->conn) {
            return 0;
        }

        try {
            if (!empty($params) && function_exists('mysqli_execute_query')) {
                $result = mysqli_execute_query($this->conn, $query, $params);
            } else {
                $result = $this->conn->query($query);
            }

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

    public function select_where(string $query, array $params = []): array
    {
        $data = [];
        if (!$this->conn) {
            return $data;
        }

        try {
            if (!empty($params) && function_exists('mysqli_execute_query')) {
                $result = mysqli_execute_query($this->conn, $query, $params);
            } else {
                $result = $this->conn->query($query);
            }

            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $result->free();
            }
        } catch (Throwable $e) {
            error_log('Select where error: ' . $e->getMessage());
        }

        return $data;
    }

    public function update_where(string $query, array $params = []): int
    {
        if (!$this->conn) {
            return 0;
        }

        try {
            if (!empty($params) && function_exists('mysqli_execute_query')) {
                $result = mysqli_execute_query($this->conn, $query, $params);
            } else {
                $result = $this->conn->query($query);
            }

            return $result === true ? 1 : 0;
        } catch (Throwable $e) {
            error_log('Update error: ' . $e->getMessage());
            return 0;
        }
    }

    public function delete(string $query, array $params = []): int
    {
        if (!$this->conn) {
            return 0;
        }

        try {
            if (!empty($params) && function_exists('mysqli_execute_query')) {
                $result = mysqli_execute_query($this->conn, $query, $params);
            } else {
                $result = $this->conn->query($query);
            }

            return $result === true ? 1 : 0;
        } catch (Throwable $e) {
            error_log('Delete error: ' . $e->getMessage());
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
