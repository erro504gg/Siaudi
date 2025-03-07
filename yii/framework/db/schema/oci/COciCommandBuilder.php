<?php
/**
 * COciCommandBuilder class file.
 *
 * @author Ricardo Grana <rickgrana@yahoo.com.br>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * COciCommandBuilder provides basic methods to create query commands for tables.
 *
 * @author Ricardo Grana <rickgrana@yahoo.com.br>
 * @version $Id: COciCommandBuilder.php 3515 2011-12-28 12:29:24Z mdomba $
 * @package system.db.schema.oci
 */
class COciCommandBuilder extends CDbCommandBuilder
{
	/**
	 * @var integer the last insertion ID
	 */
	public $returnID;

	/**
	 * Returns the last insertion ID for the specified table.
	 * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
	 * @return mixed last insertion id. Null is returned if no sequence name.
	 */
	public function getLastInsertID($table)
	{
		return $this->returnID;
	}

	/**
	 * Alters the SQL to apply LIMIT and OFFSET.
	 * Default implementation is applicable for PostgreSQL, MySQL and SQLite.
	 * @param string $sql SQL query string without LIMIT and OFFSET.
	 * @param integer $limit maximum number of rows, -1 to ignore limit.
	 * @param integer $offset row offset, -1 to ignore offset.
	 * @return string SQL with LIMIT and OFFSET
	 */
	public function applyLimit($sql,$limit,$offset)
	{
		if (($limit < 0) and ($offset < 0)) return $sql;

		$filters = array();
		if($offset>0){
			$filters[] = 'rowNumId > '.(int)$offset;
		}

		if($limit>=0){
			$filters[]= 'rownum <= '.(int)$limit;
		}

		if (count($filters) > 0){
			$filter = implode(' and ', $filters);
			$filter= " WHERE ".$filter;
		}else{
			$filter = '';
		}


		$sql = <<<EOD
				WITH USER_SQL AS ({$sql}),
				   PAGINATION AS (SELECT USER_SQL.*, rownum as rowNumId FROM USER_SQL)
				SELECT *
				FROM PAGINATION
				{$filter}
EOD;

		return $sql;
	}

	/**
	 * Creates an INSERT command.
	 * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
	 * @param array $data data to be inserted (column name=>column value). If a key is not a valid column name, the corresponding value will be ignored.
	 * @return CDbCommand insert command
	 */
	public function createInsertCommand($table,$data)
	{
		$this->ensureTable($table);
		$fields=array();
		$values=array();
		$placeholders=array();
		$i=0;
		foreach($data as $name=>$value)
		{
			if(($column=$table->getColumn($name))!==null && ($value!==null || $column->allowNull))
			{
				$fields[]=$column->rawName;
				if($value instanceof CDbExpression)
				{
					$placeholders[]=$value->expression;
					foreach($value->params as $n=>$v)
						$values[$n]=$v;
				}
				else
				{
					$placeholders[]=self::PARAM_PREFIX.$i;
					$values[self::PARAM_PREFIX.$i]=$column->typecast($value);
					$i++;
				}
			}
		}

		$sql="INSERT INTO {$table->rawName} (".implode(', ',$fields).') VALUES ('.implode(', ',$placeholders).')';

		if(is_string($table->primaryKey) && ($column=$table->getColumn($table->primaryKey))!==null && $column->type!=='string')
		{
			$sql.=' RETURNING '.$column->rawName.' INTO :RETURN_ID';
			$command=$this->getDbConnection()->createCommand($sql);
			$command->bindParam(':RETURN_ID', $this->returnID, PDO::PARAM_INT, 12);
			$table->sequenceName='RETURN_ID';
		}
		else
			$command=$this->getDbConnection()->createCommand($sql);

		foreach($values as $name=>$value)
			$command->bindValue($name,$value);

		return $command;
	}
}
