<?php

//require 'Connection-class.php';

Class Videos
{

	private $_pdo;
	private $_stmt;

	public function __construct()
	{

		$this->_pdo = Connection::getInstance()->getConnection();
		$this->_stmt = null;

	}

	public function insert($ext, $url, $stream_key)
	{

		$req = "INSERT INTO videos(video_extension, stream_url, stream_key) VALUES(?, ?, ?)";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute(array($ext, $url, $stream_key));

	}

	public function update($id, $url, $stream_key)
	{

		$req = "UPDATE videos SET stream_url = ?, stream_key = ? WHERE id = ?";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute(array($url, $stream_key, $id));

		return "ok";

	}

	public function delete($id)
	{

		$req = "DELETE FROM videos WHERE id = ?";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute(array($id));

	}

	public function getStream($id): int
	{

		$req = "SELECT is_in_stream FROM videos WHERE id = ?";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute(array($id));

		$res = $this->_stmt->fetchAll();

		return $res[0]['is_in_stream'];

	}

	public function setStream($id)
	{

		$currentStreamStatus = $this->getStream($id);
		$newStreamStatus = 0;

		if ($currentStreamStatus === 0) $newStreamStatus = 1;

		$req = "UPDATE videos SET is_in_stream = ? WHERE id = ?";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute(array($newStreamStatus, $id));

	}

	public function getPID($id)
	{

		$req = "SELECT pid_of_stream FROM videos WHERE id = ?";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute(array($id));

		$res = $this->_stmt->fetchAll();

		return $res[0]['pid_of_stream'];

	}

	public function setPID($id, $pid)
	{

		$currentStreamStatus = $this->getStream($id);
		$newStreamStatus = 0;

		if ($currentStreamStatus === 0) $newStreamStatus = 1;

		$req = "UPDATE videos SET pid_of_stream = ? WHERE id = ?";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute(array($pid, $id));

	}

	public function getLastID(): int
	{

		$res = $this->_pdo->lastInsertId();

		/*$req = "SELECT COALESCE(MAX(id), -1) FROM videos";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute();

		$res = $this->_stmt->fetchAll();*/

		return $res;

	}

	public function getAllVideos(): array
	{

		$req = "SELECT * FROM videos";
		$this->_stmt = $this->_pdo->prepare($req);
		$this->_stmt->execute();

		$res = $this->_stmt->fetchAll();

		return $res;

	}

}