<?php

    namespace Core;

    /**
     * Class MySQLi
     *
     * Examples:
     *      Fetch a record from a database:
     *          $account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', 'test', 'test')->fetch_array();
     *          $account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', array('test', 'test'))->fetch_array();
     *
     *      Fetch multiple records from a database:
     *          $accounts = $db->query('SELECT * FROM accounts')->fetch_all();
     *          foreach ($accounts as $account) {
     *              echo $account['name'] . '<br>';
     *          }
     *
     *          $db->query('SELECT * FROM accounts')->fetch_all(function($account) {
     *              echo $account['name'];
     *          });
     *
     *      Get the number of rows:
     *          $accounts = $db->query('SELECT * FROM accounts');
     *          echo $accounts->num_rows();
     *
     *      Get the affected number of rows:
     *          $insert = $db->query('INSERT INTO accounts (username,password,email,name) VALUES (?,?,?,?)', 'test', 'test', 'test@gmail.com', 'Test');
     *          echo $insert->affected_rows();
     *
     *      Get the total number of queries:
     *          echo $db->query_count;
     *
     *      Get the last insert ID:
     *          echo $db->lastInsertID();
     *
     *      Close the database:
     *          $db->close();
     */
    class MySQLi
    {
        protected $connection;
        protected $query;
        protected $show_errors  = TRUE;
        protected $query_closed = TRUE;
        public    $query_count  = 0;

        /**
         * MySQLi constructor.
         * @param string $dbhost
         * @param string $dbuser
         * @param string $dbpass
         * @param string $dbname
         * @param string $charset
         */
        public function __construct()
        {
            $keys = array(
                'host',
                'user',
                'password',
                'schema',
                'charset'
            );

            // Over load the constructor
            if (func_num_args() > 1) // Load from the constructor
            {
                $arguments = array_pad(func_get_args(),     count($keys), '');
                $config = array_combine($keys, $arguments);
            }
            else // Load from the config file.
            {
                try
                {
                    $config = include APPLICATION_PATH . "/config/MySQLi.php";
                }
                catch (\Exception $e)
                {
                    show_error(501);
                }
            }

            try
            {
                $this->connection = new \mysqli($config['host'], $config['user'], $config['password'], $config['schema']);
            } catch(\Exception $e)
            {
                $code = \Core\Constants\HttpStatusCode::SERVICE_UNAVAILABLE;
                show_error($code, $e->getMessage());
            }

            if ($this->connection->connect_error)
            {
                $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
                $code = \Core\Constants\HttpStatusCode::SERVICE_UNAVAILABLE;
                show_error($code);
            }
            $this->connection->set_charset($config['charset']);

        }

        /**
         * @param $query
         * @return $this
         */
        public function query($query)
        {
            if (!$this->query_closed)
            {
                $this->query->close();
            }
            if ($this->query = $this->connection->prepare($query))
            {
                if (func_num_args() > 1)
                {
                    $x        = func_get_args();
                    $args     = array_slice($x, 1);
                    $types    = '';
                    $args_ref = array();
                    foreach ($args as $k => &$arg)
                    {
                        if (is_array($args[$k]))
                        {
                            foreach ($args[$k] as $j => &$a)
                            {
                                $types      .= $this->_get_type($args[$k][$j]);
                                $args_ref[] = &$a;
                            }
                        }
                        else
                        {
                            $types      .= $this->_get_type($args[$k]);
                            $args_ref[] = &$arg;
                        }
                    }
                    array_unshift($args_ref, $types);
                    call_user_func_array(array($this->query, 'bind_param'), $args_ref);
                }
                $this->query->execute();
                if ($this->query->errno)
                {
                    $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
                }
                $this->query_closed = FALSE;
                $this->query_count++;
            }
            else
            {
                $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
            }
            return $this;
        }

        /**
         * @param null $callback
         * @return array
         */
        public function fetch_all($callback = NULL)
        {
            $params = array();
            $row    = array();
            $meta   = $this->query->result_metadata();
            while ($field = $meta->fetch_field())
            {
                $params[] = &$row[$field->name];
            }
            call_user_func_array(array($this->query, 'bind_result'), $params);
            $result = array();
            while ($this->query->fetch())
            {
                $r = array();
                foreach ($row as $key => $val)
                {
                    $r[$key] = $val;
                }
                if ($callback != NULL && is_callable($callback))
                {
                    $value = call_user_func($callback, $r);
                    if ($value == 'break')
                    {
                        break;
                    }
                }
                else
                {
                    $result[] = $r;
                }
            }
            $this->query->close();
            $this->query_closed = TRUE;
            return $result;
        }

        /**
         * @return array
         */
        public function fetch_array()
        {
            $params = array();
            $row    = array();
            $meta   = $this->query->result_metadata();
            while ($field = $meta->fetch_field())
            {
                $params[] = &$row[$field->name];
            }
            call_user_func_array(array($this->query, 'bind_result'), $params);
            $result = array();
            while ($this->query->fetch())
            {
                foreach ($row as $key => $val)
                {
                    $result[$key] = $val;
                }
            }
            $this->query->close();
            $this->query_closed = TRUE;
            return $result;
        }

        /**
         * @return mixed
         */
        public function close()
        {
            return $this->connection->close();
        }

        /**
         * @return mixed
         */
        public function num_rows()
        {
            $this->query->store_result();
            return $this->query->num_rows;
        }

        /**
         * @return mixed
         */
        public function affected_rows()
        {
            return $this->query->affected_rows;
        }

        /**
         * @return mixed
         */
        public function last_ID()
        {
            return $this->connection->insert_id;
        }

        /**
         * @param $error
         */
        public function error($error)
        {
            if ($this->show_errors)
            {
                exit($error);
            }
        }

        /**
         * @param $var
         * @return string
         */
        private function _get_type($var)
        {
            if (is_string($var))
            {
                return 's';
            }
            if (is_float($var))
            {
                return 'd';
            }
            if (is_int($var))
            {
                return 'i';
            }
            return 'b';
        }

    }
