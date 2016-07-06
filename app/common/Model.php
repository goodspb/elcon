<?php
namespace Common;

use Phalcon\Di;
use Phalcon\Db as PhalconDb;
use Phalcon\Mvc\Model as PhalconModel;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

abstract class Model extends PhalconModel
{
    public $pk = 'id';

    /**
     * @return string
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**]
     * @param array $data
     * @return $this
     */
    public function addData(array $data)
    {
        $data = array_merge($this->toArray(), $data);
        foreach ($data as $key => $val) {
            $this->setData($key, $val);
        }
        return $this;
    }

    /**
     * @param null $key
     * @return array|null|PhalconModel\Resultset|\Phalcon\Mvc\Model
     */
    public function getData($key = null)
    {
        return $key === null ? $this->toArray() : (property_exists($this, $key) ? $this->$key : null);
    }

    /**
     * @return array|null|PhalconModel\Resultset|\Phalcon\Mvc\Model
     */
    public function getId()
    {
        return $this->getData($this->pk);
    }

    /**
     * @param $key
     * @param null $value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->$k = $v;
            }
        } else {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * @param $id
     * @return $this|Model
     */
    public function setId($id)
    {
        return $this->setData($this->pk, $id);
    }

    /**
     * @param $sql
     * @param null $bind
     * @return bool
     */
    public function sqlExecute($sql, $bind = null)
    {
        $sql = $this->translatePhalconBindIntoPDO($sql, $bind);
        return $this->getReadConnection()->execute($sql, $bind);
    }

    /**
     * @param $sql
     * @param null $bind
     * @return array
     */
    public function sqlFetchAll($sql, $bind = null)
    {
        $sql = $this->translatePhalconBindIntoPDO($sql, $bind);
        return $this->getReadConnection()->fetchAll($sql, PhalconDb::FETCH_ASSOC, $bind);
    }

    /**
     * @param $sql
     * @param null $bind
     * @return array
     */
    public function sqlFetchOne($sql, $bind = null)
    {
        $sql = $this->translatePhalconBindIntoPDO($sql, $bind);
        return $this->getReadConnection()->fetchOne($sql, PhalconDb::FETCH_ASSOC, $bind);
    }

    /**
     * @param $sql
     * @param null $bind
     * @return mixed
     */
    public function sqlFetchColumn($sql, $bind = null)
    {
        $row = $this->sqlFetchOne($sql, $bind);
        return reset($row);
    }

    private function translatePhalconBindIntoPDO($sql, &$bind = null)
    {
        if (!empty($bind) && is_array($bind)) {
            foreach ($bind as $key => $val) {
                //优先处理int值,不使用PDO
                if (is_int($val)) {
                    $sql = str_replace(":{$key}:", $val, $sql);
                    unset($bind[$key]);
                    continue;
                }
                //字符串处理
                $search = array(":{$key}:");
                $replace = array(":{$key}");
                //数组处理
                if (strstr($sql, ($inReplace = "{{$key}:array}")) !== false) {
                    if (is_array($val)) {
                        $temp = '';
                        foreach ($val as $vkey => $vval) {
                            $realKey = "{$key}_{$vkey}";
                            $temp .= ($temp == '' ? '' : ' , ') . " :{$realKey} ";
                            $bind[$realKey] = $vval;
                        }
                        array_push($search, $inReplace);
                        array_push($replace, $temp);
                        unset($bind[$key]);
                    }
                }
                $sql = str_replace($search, $replace, $sql);
            }
        }
        return $sql;
    }

    /**
     * @param array | string | int $conditions
     * @param array $bind
     * @param string $order
     * @param string $columns
     * @return $this
     */
    public static function findFirstSimple($conditions, $bind = array(), $order = null, $columns = null)
    {
        $params = static::buildParams($conditions, $bind, $order, $columns);
        return static::findFirst($params);
    }

    /**
     * @param $conditions
     * @param array $bind
     * @param string $order
     * @param string $columns
     * @param string|int $limit
     * @return $this
     */
    public static function findSimple($conditions = array(), $bind = array(), $order = null, $columns = null, $limit = null)
    {
        $params = static::buildParams($conditions, $bind, $order, $columns, $limit);
        return static::find($params);
    }

    /**
     * @param array $conditions
     * @param array $bind
     * @return mixed
     */
    public static function countSimple($conditions = array(), $bind = array())
    {
        $params = static::buildParams($conditions, $bind);
        return static::count($params);
    }

    /**
     * @param $conditions
     * @param array $bind
     * @param string $order
     * @param string $columns
     * @param string|int $limit
     * @return array
     */
    public static function buildParams($conditions = array(), $bind = array(), $order = null, $columns = null, $limit = null)
    {
        $params = array();
        if (empty($bind)) {
            if (is_array($conditions)) {
                $params['conditions'] = "";
                $params['bind'] = array();
                foreach ($conditions as $key => $value) {
                    if (!is_array($value)) {
                        $operator = '=';
                        $realValue = $value;
                    } else {
                        $operator = reset($value);
                        $realValue = next($value);
                    }
                    $columnsKay = $key;
                    if (($__found = strpos($columnsKay, "__")) !== false && $__found > 0) {
                        $columnsKay = substr($columnsKay, 0, $__found);
                    }
                    /* 如果是 IN */
                    if (trim(strtolower($operator)) == 'in' && is_array($realValue) && !empty($realValue)) {
                        $inCondition = "";
                        foreach ($realValue as $realValueK => $realValueV) {
                            $inCondition .= ($inCondition == '' ? '' : " , ") . " :{$columnsKay}_{$realValueK}: ";
                            $params['bind']["{$columnsKay}_{$realValueK}"] = trim($realValueV);
                        }
                        $params['conditions'] .= ($params['conditions'] == "" ? "" : " AND ") . " {$columnsKay} IN ( {$inCondition} ) ";
                    } else {
                        $params['conditions'] .= ($params['conditions'] == "" ? "" : " AND ") . " {$columnsKay} {$operator} :{$key}: ";
                        $params['bind'][$key] = $realValue;
                    }
                }
            } else {
                $params['conditions'] = $conditions;
            }
        } else {
            $params['conditions'] = $conditions;
            $params['bind'] = $bind;
        }
        if (!is_null($order) && is_string($order)) {
            $params['order'] = $order;
        }
        if (!is_null($columns)) {
            $params['columns'] = is_array($columns) ? explode(',', $columns) : $columns;
        }
        if (!is_null($limit)) {
            if (is_int($limit)){
                $params['limit'] = $limit;
            } elseif(is_string($limit) && strpos($limit, ',') && count($limitOffset = explode(',', $limit)) == 2) {
                list($limit, $offset) = $limitOffset;
                $params['limit'] = intval(trim($limit));
                $params['offset'] = intval(trim($offset));
            }
        }
        return $params;
    }

    /**
     * Get pagination
     * @param $conditions
     * @param $order
     * @param $pageSize
     * @param $currentPage
     * @return Paginator
     */
    public static function pagination($conditions, $order, $pageSize, $currentPage)
    {
        $queryParams = self::buildParams($conditions, [], $order);
        $modelsManager = Di::getDefault()->getShared("modelsManager");
        $builder = $modelsManager->createBuilder($queryParams);
        $builder->from(static::class);
        return new Paginator([
            'builder' => $builder,
            'limit' => $pageSize,
            'page' => $currentPage,
        ]);
    }
}
