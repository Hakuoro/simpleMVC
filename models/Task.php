<?php
/*
CREATE TABLE IF NOT EXISTS `task` (
`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('open','close') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/
namespace models;

/**
 * Class Task
 * @package models
 *
 * @property int id
 * @property string username
 * @property string email
 * @property string body
 * @property string image
 * @property string status
 */
class Task extends Base
{

	public function getOne(int $id)
	{
		$query = 'SELECT id, username, email, body, image, status FROM task WHERE id = ?';

		$ret = null;
		if ($stmt = $this->connection->prepare($query)) {

			$stmt->bind_param("i", $id);
			$stmt->execute();

			$stmt->bind_result($this->id, $this->username, $this->email, $this->body, $this->image, $this->status);

			if ($stmt->fetch()) {

			}
			$stmt->close();
		}

		return $this;
	}

	public function save()
	{
		if ($this->id){
			$this->update();
		} else {
			$this->id = $this->save();
		}
	}

	protected function insert()
	{
		$query = 'INSERT INTO task (username, email, body, image, status) VALUES (?,?,?,?,?)';

		if ($stmt = $this->connection->prepare($query)) {
			$stmt->bind_param("sssss", $this->username, $this->email, $this->body, $this->image, $this->status);
			$stmt->execute();
			$stmt->close();
		}

		return $this->connection->insert_id;
	}

	protected function update()
	{
		$query = 'UPDATE task SET username=?, email=?, body=?,  image=?, status=? WHERE id = ?';

		if ($stmt = $this->connection->prepare($query)) {
			$stmt->bind_param("sssssi", $this->username, $this->email, $this->body, $this->image, $this->status, $this->id);
			$stmt->execute();
			$stmt->close();
		}
	}

	/**
	 * @param int $offset
	 * @param int $limit
	 * @param null $sortBy
	 * @return \Generator
	 */
	public function getList($offset = 0, $limit = 3, $sortBy = null)
	{
		$sort = '';
		if ($sortBy) {
			$sort .= ' ORDER BY ';
			foreach ($sortBy as  $name => $order) {
				$sort .= $name . ' ' . $order . ',';
			}

			$sort = substr($sort, 0, -1);
		}

		$query = 'SELECT id, username, email, body, image, status FROM task ' . $sort . ' LIMIT ?,?';

		if ($stmt = $this->connection->prepare($query)) {

			$stmt->bind_param("ii", $offset, $limit);

			$stmt->execute();

			$stmt->bind_result($this->id, $this->username, $this->email, $this->body, $this->image, $this->status);

			while ($stmt->fetch()) {
				yield $this;
			}

			$stmt->close();
		}
	}
}