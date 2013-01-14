<?php

class EncryptedSessionHandler extends SessionHandler
{
	private $clave;

	public function __construct($clave)
	{
		$this->clave = $clave;
	}

	public function read($id)
	{
		$datos = parent::read($id);

		return mcrypt_decrypt(MCRYPT_3DES, $this->clave, $datos, MCRYPT_MODE_ECB);
	}

	public function write($id, $datos)
	{
		$datos = mcrypt_encrypt(MCRYPT_3DES, $this->clave, $datos, MCRYPT_MODE_ECB);

		return parent::write($id, $datos);
	}
}


?>